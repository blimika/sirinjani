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
    public function Kegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_id', 'keg_id');
    }
    public function Pengirim(){
        return $this->hasOne('App\User','username', 'notif_dari');
    }
    public function Penerima(){
        return $this->hasOne('App\User','username', 'notif_untuk');
    }
}
