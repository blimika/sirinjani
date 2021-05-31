<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    //
    protected $table = 't_aktivitas';
    protected $fillable = ['log_username','log_pesan','log_ip','log_jenis'];
    public function JenisLog(){
        return $this->hasOne('App\JenisLog','jlog_id', 'log_jenis');
    }
    public function Operator(){
        return $this->hasOne('App\User','username', 'log_username');
    }
}
