<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected   $table = 'tb_kelas',
    $primaryKey = 'id_kelas',
    $fillable = ['id_kelas','nama_kelas', 'id_wali_kelas','nama_wali_kelas','created_at', 'updated_at'],
    $casts = [
        'id_kelas' => 'string',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }
}
