<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessMenuSection extends Model
{
  protected $table = "access_menu_section";
  protected $guarded = [];

  public function menu_section()
  {
    return $this->belongsTo(MenuSection::class, 'menu_section_id', 'id');
  }
}
