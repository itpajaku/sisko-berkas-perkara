<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessMenu extends Model
{
  protected $table = "access_menu";
  protected $guarded = [];

  public function menu()
  {
    return $this->belongsTo(Menu::class, 'menu_id', 'id');
  }
}
