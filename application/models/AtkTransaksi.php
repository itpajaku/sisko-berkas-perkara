<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkTransaksi extends Model
{
	protected $table = "atk_transaksi";

	protected $guarded = [];

	public function atk_item()
	{
		return $this->belongsTo(AtkItem::class);
	}
}
