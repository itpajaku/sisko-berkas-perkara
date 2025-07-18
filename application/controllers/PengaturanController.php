<?php

use App\Libraries\AuthData;
use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\AccessMenu;
use App\Models\AccessMenuSection;
use App\Models\AllowedGroup;
use App\Models\Menu;
use App\Models\MenuSection;
use App\Models\SysGroup;
use App\Services\PengaturanService;

class PengaturanController extends APP_Controller
{
  private PengaturanService $pengaturanService;

  public function __construct()
  {
    parent::__construct();
    $this->pengaturanService = PengaturanService::getInstance();
  }

  public function access_menu_page() {}

  public function update()
  {
    // Logic to update system configuration
    // This is just a placeholder for the actual implementation
    redirect('pengaturan');
  }

  public function akun_page()
  {
    MethodFilter::must('get');

    // $data['user_groups'] = 
    Templ::render('pengaturan/akun_page', [
      "user_groups" => AllowedGroup::with('group')->get(),
      "sys_groups" => SysGroup::where("groupid", "!=", "-1")->get()
    ])
      ->layout('layouts/main_layout', [
        'title' => 'Pengaturan Akun'
      ]);
  }

  public function detail_akun($hash_id)
  {
    MethodFilter::must('get');
    $id = Hashid::singleDecode($hash_id);
    $data['selected_group'] = AllowedGroup::with(['group', 'access_menu_section'])
      ->where('group_id', $id)
      ->first();

    $data['allowed_menu'] = AccessMenu::where('group_id', $id)
      ->with('menu.menu_section')
      ->get();

    // printdie($data['allowed_menu']->toArray());
    if (MethodFilter::isHeader("Hx-Request")) {
      return $this->output->set_output(
        Templ::component('pengaturan/detail_akun', $data)
      );
    }

    Templ::render('pengaturan/detail_akun', [
      'user_group' => []
    ])->layout('layouts/modal_layout');
  }

  public function delete_akun($hash_id)
  {
    MethodFilter::must('delete');
    try {
      $id = Hashid::singleDecode($hash_id);
      $allowedGroup = AllowedGroup::findOrFail($id);
      if ($allowedGroup->group_id == 1) {
        throw new \Exception("Tidak dapat menghapus grup admin.");
      }
      $allowedGroup->access_menu_section()->delete();
      $allowedGroup->menu()->delete();

      $allowedGroup->delete();
    } catch (\Throwable $th) {
      $this->output
        ->set_header('HX-Trigger: ' . json_encode([
          'htmx:toastr' => [
            'message' => $th->getMessage(),
            'level' => 'error'
          ]
        ]))
        ->set_output($th->getMessage());
    }
  }

  public function add_akun()
  {
    MethodFilter::must("POST");
    try {
      $this->pengaturanService->addAllowedGroup();
      $this->output
        ->set_header("HX-Refresh: true")->set_output("Berhasil menambahkan group");
    } catch (\Throwable $th) {
      $this->output->set_output(
        Templ::component("components/exception_alert", ["message" => $th->getMessage()])
      );
    }
  }

  public function akun_fetch_form_access($hash_id)
  {
    MethodFilter::must("get");
    MethodFilter::mustHeader("HX-Request-Component");
    try {
      $data['en_group_id'] = $hash_id;
      $data['menu_sections'] = MenuSection::all();

      $this->output->set_output(
        Templ::component("pengaturan/akun_access_form", $data)
      );
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $th->getMessage(),
            "level" => "error"
          ]
        ]))
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }

  public function fetch_menu_section_form($en_group_id, $en_section_id)
  {
    MethodFilter::must("get");
    MethodFilter::mustHeader("HX-Request-Component");
    try {

      $section_id = Hashid::singleDecode($en_section_id);
      $data["menus"] = Menu::where("section_id", $section_id)->get();
      $data["menu_section_id"] = $en_section_id;
      $data["en_group_id"] = $en_group_id;
      $data["allowed_menu"] = AccessMenu::where('group_id', Hashid::singleDecode($en_group_id))->get();
      $this->output->set_output(Templ::component("pengaturan/menu_access_form", $data));
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $th->getMessage(),
            "level" => "error"
          ]
        ]))
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }

  public function add_access_menu($en_group_id)
  {
    MethodFilter::must("post");
    try {
      AccessMenu::firstOrCreate([
        'group_id' => Hashid::singleDecode($en_group_id),
        'menu_id' => Hashid::singleDecode(RequestBody::post('menu_id'))
      ]);
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => "Berhasil menambah akses",
            "level" => "success"
          ],
          "htmx:refresh" => true
        ]))
        ->set_output(
          Templ::component("components/success_alert", ["message" => "Berhasil menambah akses"])
        );
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $th->getMessage(),
            "level" => "error"
          ]
        ]))
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }
  public function batch_add_access_menu($en_group_id)
  {
    MethodFilter::must("post");
    try {
      $this->pengaturanService->attachMenuToGroup(Hashid::singleDecode($en_group_id));
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => "Berhasil menambah akses",
            "level" => "success"
          ],
          "htmx:refresh" => true
        ]))
        ->set_output(
          Templ::component("components/success_alert", ["message" => "Berhasil menambah akses"])
        );
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $th->getMessage(),
            "level" => "error"
          ]
        ]))
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }

  public function delete_menu_section($en_group_id, $en_section_id)
  {
    MethodFilter::must("delete");
    try {
      $group_id = Hashid::singleDecode($en_group_id);
      $section_id = Hashid::singleDecode($en_section_id);

      $this->pengaturanService->detach_section($group_id, $section_id);
      $message = "Berhasil menghapus akses ke session ini";
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $message,
            "level" => "success"
          ],
          "htmx:refresh" => true
        ]))
        ->set_output($message);
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $th->getMessage(),
            "level" => "error"
          ]
        ]))
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }

  public function add_menu_section($en_group_id)
  {
    MethodFilter::must('post');
    try {
      $id = Hashid::singleDecode($en_group_id);
      $group = AllowedGroup::where("group_id", $id)->first();
      if (!$group) {
        throw new Exception("Tidak ada group ini", 1);
      }

      $existedSection = $group->access_menu_section()->where([
        "menu_section_id" => RequestBody::post("section_id")
      ])->first();

      if ($existedSection) {
        throw new Exception("Sudah mendapatkan akses pada section ini", 1);
      }


      $group->access_menu_section()->create([
        "menu_section_id" => RequestBody::post("section_id")
      ]);

      $message = "Berhasil mendapat akses ke section ini";
      $this->output
        ->set_header("HX-Trigger : " . json_encode([
          "htmx:toastr" => [
            "message" => $message,
            "level" => "success"
          ],
          "htmx:refresh" => true
        ]))
        ->set_output(
          Templ::component("components/success_alert", ["message" => $message])
        );
    } catch (\Throwable $th) {
      $this->output
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }

  public function fetch_menu_form($en_group_id)
  {
    MethodFilter::must("get");
    MethodFilter::mustHeader("HX-Request-Component");
    try {
      $group_id = Hashid::singleDecode($en_group_id);

      $data['en_group_id'] = $en_group_id;
      $data['allowed_section'] = AccessMenuSection::with('menu_section')->where('group_id', $group_id)->get();
      $data['allowed_menu'] = AccessMenu::where("group_id", $group_id)->get();
      $this->output->set_output(Templ::component('pengaturan/menu_form', $data));
    } catch (\Throwable $th) {
      $this->output
        ->set_output(
          Templ::component("components/exception_alert", ["message" => $th->getMessage()])
        );
    }
  }
}
