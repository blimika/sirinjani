<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegTargetLama extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'keg_target';
    protected $primaryKey = 'keg_t_id';
}