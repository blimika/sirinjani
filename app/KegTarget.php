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
}
