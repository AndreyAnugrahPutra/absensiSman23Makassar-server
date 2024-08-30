<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected   $table = 'tb_absensi',
    $primaryKey = 'id_absensi',
    $fillable = ['id_absensi', 'id_form','id_siswa', 'nama_siswa', 'lampiran_file', 'lampiran_path', 'deskripsi', 'status','waktu_absen','created_at', 'updated_at'],
    $casts = [
        'id_absensi' => 'string',
    ];

    public function siswa()
    {
         return $this->belongsTo(Siswa::class);
    }
}
