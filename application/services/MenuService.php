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
      return self::$menu;
    }

    self::$menu = AccessMenuSection::with(['menu_section.menu' => function ($q) {
      $q->whereHas('access_menu', function ($qq) {
        $qq->where('group_id', AuthData::getUserData()->groupid);
      });
    }])
      ->where("group_id", AuthData::getUserData()->groupid)
      ->get();

    return self::$menu;
  }
}
