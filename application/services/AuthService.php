<?php

namespace App\Services;

use App\Libraries\Eloquent;

class AuthService
{
  public function getProfileData($user): object
  {
    $sipp =  Eloquent::get_instance()->connection("sipp");
    $profile = $sipp->table('sys_users')
      ->leftJoin('sys_user_group', 'sys_users.userid', '=', 'sys_user_group.userid',)
      ->leftJoin('sys_groups', 'sys_user_group.groupid', '=', 'sys_groups.groupid')
      ->leftJoin('sys_user_online', 'sys_user_online.userid', '=', 'sys_users.userid')
      ->leftJoin('user_hakim', 'user_hakim.userid', '=', 'sys_users.userid')
      ->leftJoin('user_panitera', 'user_panitera.userid', '=', 'sys_users.userid')
      ->leftJoin('user_jurusita', 'user_jurusita.userid', '=', 'sys_users.userid')
      ->where('sys_users.userid', $user->userid)->first();

    return $profile;
  }

  public static function getRoleMenu() {}
}
