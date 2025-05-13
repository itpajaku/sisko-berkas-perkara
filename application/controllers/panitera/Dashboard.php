<?php

class Dashboard extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->method_filter->must("get");
    echo "Panitera ";
  }
}
