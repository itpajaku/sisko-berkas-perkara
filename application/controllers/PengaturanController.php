<?php

use App\Libraries\AuthData;
use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\AllowedGroup;
use Cake\Utility\Hash;

class PengaturanController extends APP_Controller

{
  public function __construct()
  {
    parent::__construct();
  }

  public function access_menu_page() {}

  public function update()
  {
    // Logic to update system configuration
    // This is just a placeholder for the actual implementation
    $this->sysconf->updateVar($this->input->post());
    redirect('pengaturan');
  }

  public function akun_page()
  {
    MethodFilter::must('get');

    // $data['user_groups'] = 
    Templ::render('pengaturan/akun_page', [
      "user_groups" => AllowedGroup::with('group')->get()
    ])
      ->layout('layouts/main_layout', [
        'title' => 'Pengaturan Akun'
      ]);
  }

  public function detail_akun($hash_id)
  {
    MethodFilter::must('get');
    $id = Hashid::singleDecode($hash_id);
    $userGroup = AllowedGroup::with(['group', 'access_menu_section.menu_section.menu.access_menu' => function ($query) use ($id) {
      $query->where('group_id', $id);
    }])
      ->where('id', $id)
      ->first();

    if (isset($this->input->request_headers()['Hx-Request-Component'])) {
      return $this->output->set_output(
        Templ::component('pengaturan/detail_akun', [
          'allowed' => $userGroup
        ])
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
}
