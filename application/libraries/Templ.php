<?php

namespace App\Libraries;

use CI_Controller;

class Templ
{
  protected CI_Controller $app;
  private string $layout = "";
  private string $sidebar = "layouts/sidebar_menu";
  private string $page_content = "";

  public function __construct($app)
  {
    $this->app = $app;
  }

  public static function render(string $view, array $data = []): Templ
  {
    $self = new self(get_instance());
    $self->page_content = $self->app->load->view($view, $data, true);
    return $self;
  }

  public function layout(string $layout, array $data = []): Templ
  {
    $this->layout = $layout;
    $this->app->load->view($layout, [
      "page_content" => $this->page_content,
      "sidebar_menu" => $this->sidebar,
      $data
    ]);
    return $this;
  }

  public function sidebar(string $sidebar, array $data = []): Templ
  {
    $this->sidebar = $this->app->load->view($sidebar, $data, true);
    return $this;
  }

  public static function component(string $view, array $data = []): string
  {
    return get_instance()->load->view($view, $data, true);
  }
}
