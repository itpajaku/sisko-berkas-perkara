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

		static::created(function ($m) {
			$stockThisYear = $m->stocks->where("tahun", date(("Y")))->first();
			$m->current_stock = $stockThisYear->stock;
			$m->after_stock = (int) $stockThisYear->stock + (int) $m->restock - (int) $m->pengeluaran;
			$m->save();
		});

		static::saved(function ($m) {
			$m->stocks()->where("tahun", date(("Y")))->update([
				"stock" => $m->after_stock
			]);
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
