<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpjTarget extends Model
{
    //
    protected $table = 'm_spj_target';
    protected $primaryKey = 'spj_t_id';
    public function MasterKegiatan() {
        return $this->belongsTo('App\Kegiatan','keg_id', 'keg_id');
    }
}
