<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogPosisi extends Model
{
    //
    protected $table = 'tg_posisi';
    protected $fillable = ['user_tg','chatid_tg','command','msg_id','update_id','waktu_tg'];
}
