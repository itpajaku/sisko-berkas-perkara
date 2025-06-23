<?php

namespace App\Models;

use App\Libraries\Hashid;
use App\Libraries\Sysconf;
use Illuminate\Database\Eloquent\Model;

class BerkasAkta extends Model
{
    protected $table = "berkas_akta";
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($model) {
            $model->hash_id = Hashid::encode($model->id);
            $model->save();

            $konf = KonfigurasiAkta::findOrFail(1);
            $konf->update([
                "nomor_akta_terakhir" => $konf->nomor_akta_terakhir + 1,
                "nomor_seri_terakhir" => $konf->nomor_seri_terakhir + 1,
            ]);
        });
    }

    public function getNomorAktaCeraiAttribute()
    {
        return "$this->nomor_akta/AC/" . date("Y") . "/" . Sysconf::getVar()->KodePN;
    }

    public function getNomorSeriAktaAttribute()
    {
        return "$this->prefix $this->nomor_seri";
    }


    public function ekspedisi()
    {
        return $this->morphToMany(PosisiEkspedisi::class, "berkas", "berkas_ekspedisi", "berkas_id", "save_point");
    }

    public function perkara()
    {
        return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
    }

    public function berkas_ekspedisi()
    {
        return $this->morphMany(BerkasEkspedisi::class, "berkas");
    }
}
