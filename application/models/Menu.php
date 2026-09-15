<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  protected $guarded = [];

  public function access_menu()
  {
    return $this->hasMany(AccessMenu::class, 'menu_id', 'id');
  }

  public function menu_section()
  {
    return $this->belongsTo(MenuSection::class, 'section_id', 'id');
  }
}
