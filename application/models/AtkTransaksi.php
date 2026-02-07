<?php

namespace App\Models;

use App\Libraries\AuthData;
use Exception;
use Illuminate\Database\Eloquent\Model;

class AtkTransaksi extends Model
{
	protected $table = "atk_transaksi";

	protected $guarded = [];

	protected static function booted()
	{
		static::creating(function ($m) {
			$m->input_by = AuthData::getUserData()->username;
		});
	}

	public function atk_item()
	{
		return $this->belongsTo(AtkItem::class);
	}

	public function stocks()
	{
		return $this->hasMany(AtkStock::class, "atk_item_id", "atk_item_id");
	}
}
