<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegRealisasiLama extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table = 'keg_detil';
    protected $primaryKey = 'keg_d_id';
    
}