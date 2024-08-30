<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Siswa extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, Notifiable;
    protected   $table = 'tb_siswa',
    $primaryKey = 'user_id',
    $fillable = ['user_id','induk','nisn', 'id_kelas','nama','tgl_lahir','jkel','email','password', 'no_telp', 'alamat', 'foto_profil', 'remember_token', 'level', 'orangtua_1', 'orangtua_2', 'last_login', 'last_logout', 'created_at', 'updated_at'],
    $casts = [
        'user_id' => 'string',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
