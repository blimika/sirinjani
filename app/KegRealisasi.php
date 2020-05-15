<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegRealisasi extends Model
{
    //
    protected $table = 'm_keg_realisasi';
    protected $primaryKey = 'keg_r_id';
    public function JenisRealisasi(){
        return $this->hasOne('App\JenisRealisasi','rkeg_id', 'keg_r_jenis');
    }
    public function MasterKegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_id', 'keg_id');
    }
    public function Unitkerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'keg_r_unitkerja');
    }
}
