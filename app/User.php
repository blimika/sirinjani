<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function Level(){
        return $this->hasOne('App\KodeLevel','level_id', 'level');
    }
    public function Role(){
        return $this->hasOne('App\KodeLevel','level_id', 'role');
    }
    public function Unitkerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'kodeunit');
    }
    public function TimKerja(){
        return $this->hasOne('App\UnitKerja','unit_kode', 'kodeunit');
    }
    public function NamaWilayah(){
        return $this->hasOne('App\KodeWilayah','bps_kode', 'kodebps');
    }
    public function HakAkses() {
        return $this->hasMany('App\HakAkses','hak_userid', 'id');
    }
}
