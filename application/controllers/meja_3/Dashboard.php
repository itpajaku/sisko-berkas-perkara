<?php

use App\Libraries\MethodFilter;
use App\Libraries\AuthData;
use App\Libraries\Templ;

class Dashboard extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    MethodFilter::must("get");
    Templ::render("meja_3/dashboard_content")
      ->sidebar("layouts/sidebar_menu")
      ->layout("layouts/main_layout");
  }
}
