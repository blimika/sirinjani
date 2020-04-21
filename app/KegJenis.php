<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegJenis extends Model
{
    //
    protected $table = 't_keg_jenis';
    public $timestamps = false;
    public function Kegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_jenis', 'jkeg_id');
    }
}
