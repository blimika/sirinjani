<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    //
    protected $table = 't_hak_akses';
    public function Role(){
        return $this->hasOne('App\KodeLevel','level_id', 'hak_role');
    }
    public function TimKerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'hak_kodeunit');
    }
    public function User(){
        return $this->hasOne('App\User','username', 'hak_username');
    }
    public function Userid(){
        return $this->belongsTo('App\User','id', 'hak_userid');
    }
}
