<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;

class Dashboard extends APP_Controller
{
  public function index()
  {
    MethodFilter::must('get');
    Templ::render('admin/dashboard_page')->layout('layouts/main_layout', ['title' => 'Dashboard Admin']);
  }
}
