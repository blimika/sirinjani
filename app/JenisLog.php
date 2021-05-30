<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisLog extends Model
{
    //
    protected $table = 't_jenislog';
    public $timestamps = false;
    public function TabelAktivitas(){
        return $this->belongsTo('App\LogAktivitas','log_jenis', 'jlog_id');
    }
}
