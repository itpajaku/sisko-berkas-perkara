<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
  protected $table = "menu_section";
  protected $guarded = [];

  public function menu()
  {
    return $this->hasMany(Menu::class, 'section_id', 'id');
  }

  public function access_menu()
  {
    return $this->hasMany(AccessMenu::class, 'menu_section_id', 'id');
  }
}
