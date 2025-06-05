<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUser extends Model
{
  protected $connection = 'sipp';
  protected $table = 'sys_users';
  protected $primaryKey = 'userid';

  public function group()
  {
    $this->belongsToMany(SysGroup::class, "sys_user_group", "userid", "groupid");
  }
}
