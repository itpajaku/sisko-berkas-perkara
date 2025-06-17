<?php

namespace App\Libraries;

use App\Libraries\Templ;

class MiniCard
{
  private $text;
  private $color;
  private $value;
  private $icon;

  /**
   * Parameter icon adalah sebuah nama file assets
   */
  public function __construct(string $text, string $color, string $value, string $icon)
  {
    $this->color = $color;
    $this->text = $text;
    $this->value = $value;
    $this->icon = $icon;
  }

  public function showComponent()
  {
    echo Templ::component("components/minicard", [
      "text" => $this->text,
      "color" => $this->color,
      "icon" => $this->icon,
      "value" => $this->value
    ]);
  }
}
