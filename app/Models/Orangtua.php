<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Orangtua extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, Notifiable;
    protected   $table = 'tb_orangtua_siswa',
    $primaryKey = 'user_id',
    $fillable = ['user_id','nama', 'status','tgl_lahir','jkel','email','password', 'no_telp', 'pekerjaan','alamat', 'foto_profil', 'foto_path', 'remember_token', 'level', 'last_login', 'last_logout', 'created_at', 'updated_at'],
    $casts = [
        'user_id' => 'string',
    ];

    // public function user()
    // {
    //     return $this->morphOne(User::class, 'userable');
    // }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
