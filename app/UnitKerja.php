<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    //
    protected $table = 't_unitkerja';
    public $timestamps = false;
    public function Kegiatan(){
        return $this->belongsTo('App\Kegiatan','keg_unitkerja','unit_kode');
    }
    public function Flag(){
        return $this->belongsTo('App\FlagUmum','unit_flag', 'kode');
    }
}
