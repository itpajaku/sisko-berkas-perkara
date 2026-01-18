<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkStock extends Model
{
	protected $table = "atk_stock";
	protected $guarded = [];

	public function atk_item()
	{
		return $this->belongsTo(AtkItem::class);
	}
}
