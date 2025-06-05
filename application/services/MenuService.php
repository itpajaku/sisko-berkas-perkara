<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Models\AccessMenuSection;
use App\Models\SectionMenu;

class MenuService
{
  protected static $menu = [];


  public static function getMenu()
  {
    if (!empty(self::$menu)) {
      echo "1";
      return self::$menu;
    }

    self::$menu = AccessMenuSection::whereHas('menu_section.menu.access_menu', function ($query) {
      $query->where('group_id', intval(AuthData::getUserData()->groupid));
    })
      ->where("group_id", AuthData::getUserData()->groupid)
      ->get();

    return self::$menu;
  }
}
