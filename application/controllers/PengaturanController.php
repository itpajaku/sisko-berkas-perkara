<?php

use App\Libraries\AuthData;
use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\AllowedGroup;

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

  public function detail_akun($id)
  {
    MethodFilter::must('get');

    if ($this->input->request_headers()['Hx-Request-Component']) {
    }

    Templ::render('pengaturan/detail_akun', [
      'user_group' => []
    ])->layout('layouts/modal_layout');
  }
}
