<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisNotifikasi extends Model
{
    //
    protected $table = 't_jenis_notif';
    public $timestamps = false;
    public function Notif(){
        return $this->belongsTo('App\Notifikasi','notif_jenis', 'jnotif_id');
    }
}
