<?php

namespace App\Libraries;

class Templ
{
  protected static $CI;

  protected string $layout = '';
  protected string $sidebarViewPath = 'layouts/sidebar_menu';
  protected string $sidebarContent = '';
  protected string $pageContent = '';

  // Get CI instance (lazy init)
  protected static function ci()
  {
    if (self::$CI === null) {
      self::$CI = &get_instance();
    }
    return self::$CI;
  }

  // Constructor private untuk memaksa pakai static::render()
  private function __construct() {}

  public static function render(string $view, array $data = []): Templ
  {
    $self = new self();
    $self->pageContent = self::ci()->load->view($view, $data, true);
    $self->sidebarContent = self::ci()->load->view($self->sidebarViewPath, [], true);
    return $self;
  }

  public function layout(string $layout, array $data = []): Templ
  {
    $this->layout = $layout;

    $viewData = array_merge($data, [
      'page_content' => $this->pageContent,
      'sidebar_menu' => $this->sidebarContent
    ]);

    self::ci()->load->view($layout, $viewData);
    return $this;
  }

  public function sidebar(string $sidebarView, array $data = []): Templ
  {
    $this->sidebarViewPath = $sidebarView;
    $this->sidebarContent = self::ci()->load->view($sidebarView, $data, true);
    return $this;
  }

  public static function component(string $view, array $data = []): string
  {
    return self::ci()->load->view($view, $data, true);
  }
}
