<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkItem extends Model
{
	protected $table = "atk_item";

	protected $guarded = [];

	public function stocks()
	{
		return $this->hasMany(AtkStock::class, "atk_item_id", "id");
	}
}
