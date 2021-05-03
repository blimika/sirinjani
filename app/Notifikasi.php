<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    //
    protected $table = 't_notifikasi';
    public function JenisNotif(){
        return $this->hasOne('App\JenisNotifikasi','jnotif_id', 'notif_jenis');
    }
}
