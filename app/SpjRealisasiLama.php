<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpjRealisasiLama extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table = 'spj_detil';
    protected $primaryKey = 'spj_d_id';
}
