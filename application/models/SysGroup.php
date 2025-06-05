<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysGroup extends Model
{
  protected $table = 'sys_groups';
  protected $connection = 'sipp';
  protected $primaryKey = 'groupid';

  public function users()
  {
    return $this->belongsToMany(SysUser::class, 'sys_user_group', 'groupid', 'userid');
  }

  public function parentGroup()
  {
    return $this->belongsTo(SysGroup::class, 'parent_id', 'groupid');
  }
}
