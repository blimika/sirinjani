<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    //
    protected $table = 'kegiatan';
    protected $primaryKey = 'keg_id';
    public function JenisKeg(){
        return $this->hasOne('App\KegJenis','jkeg_id', 'keg_jenis');
    }
}
