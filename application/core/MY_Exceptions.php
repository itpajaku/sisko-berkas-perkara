<?php


class MY_Exceptions extends CI_Exceptions
{

  // overide the default show_404 method
  public function show_404($page = '', $log_error = true)
  {
    set_status_header(200);
    echo "Page not found";
  }
}
