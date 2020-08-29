<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLama extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table = 'users';
    protected $primaryKey = 'user_no';
}
