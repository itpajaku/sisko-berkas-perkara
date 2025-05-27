<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
  protected $table = 'avatars';
  protected $fillable = ['user_id', 'dice_bear_query'];
  public $timestamps = true;


  public function getAvatarUrlAttribute()
  {
    return "https://api.dicebear.com/9.x/micah/svg?seed={$this->dice_bear_query}.svg";
  }
}
