<?php

namespace App\Models;

use App\Models\Perkara;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $connection = "sipp";
    protected $table = "arsip";

    public function perkara()
    {
        return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
    }
}
