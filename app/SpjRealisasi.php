<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpjRealisasi extends Model
{
    //
    protected $table = 'm_spj_realisasi';
    protected $primaryKey = 'spj_r_id';
    public function MasterKegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_id', 'keg_id');
    }
    public function Unitkerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'spj_r_unitkerja');
    }
    public function JenisRealisasi(){
        return $this->hasOne('App\JenisRealisasi','rkeg_id', 'spj_r_jenis');
    }
}

