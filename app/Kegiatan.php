<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    //
    protected $table = 'm_keg';
    protected $primaryKey = 'keg_id';
    public function JenisKeg(){
        return $this->hasOne('App\KegJenis','jkeg_id', 'keg_jenis');
    }
    public function Target() {
        return $this->hasMany('App\KegTarget','keg_id', 'keg_id');
    }
    public function RealisasiKirim() {
        return $this->hasMany('App\KegRealisasi','keg_id', 'keg_id')->where('keg_r_jenis',1);
    }
    public function RealisasiTerima() {
        return $this->hasMany('App\KegRealisasi','keg_id', 'keg_id')->where('keg_r_jenis',2);
    }
    public function TargetSpj() {
        return $this->hasMany('App\SpjTarget','keg_id', 'keg_id');
    }
    public function RealisasiKirimSpj() {
        return $this->hasMany('App\SpjRealisasi','keg_id', 'keg_id')->where('spj_r_jenis',1);
    }
    public function RealisasiTerimaSpj() {
        return $this->hasMany('App\SpjRealisasi','keg_id', 'keg_id')->where('spj_r_jenis',2);
    }
    public function Unitkerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'keg_unitkerja');
    }
    public function FlagKegiatan(){
        return $this->belongsTo('App\FlagKegiatan','keg_flag', 'kode');
    }
}
