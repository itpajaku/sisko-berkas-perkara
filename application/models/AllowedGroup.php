<?php

namespace App\Models;

use App\Models\AccessMenu;
use App\Models\AccessMenuSection;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class AllowedGroup extends Model
{
  protected $table = 'allowed_group';
  protected $guarded = [];

  public function menu()
  {
    return $this->belongsToMany(Menu::class, AccessMenu::class, 'group_id', 'menu_id', 'group_id');
  }

  public function access_menu_section()
  {
    return $this->hasMany(AccessMenuSection::class, 'group_id', 'group_id');
  }

  public function group()
  {
    return $this->belongsTo(SysGroup::class, 'group_id', 'groupid');
  }
}
