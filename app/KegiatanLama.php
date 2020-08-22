<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegiatanLama extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'kegiatan';
    protected $primaryKey = 'keg_id';
}