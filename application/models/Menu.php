<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  protected $guarded = [];

  public function access_menu()
  {
    return $this->belongsTo(AccessMenu::class, 'id', 'menu_id');
  }
}
