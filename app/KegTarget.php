<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegTarget extends Model
{
    //
    protected $table = 'm_keg_target';
    protected $primaryKey = 'keg_t_id';
    public function MasterKegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_id', 'keg_id');
    }
    public function Unitkerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'keg_t_unitkerja');
    }
    public function TargetSpj() {
        return $this->belongsTo('App\SpjTarget', 'keg_id', 'keg_id')->where('spj_t_unitkerja',$this->keg_t_unitkerja);
    }
}
