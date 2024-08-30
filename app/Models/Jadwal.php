<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected   $table = 'tb_jadwal',
    $primaryKey = 'id_jadwal',
    $fillable = ['id_jadwal','hari', 'id_kelas', 'kelas', 'id_mapel', 'mapel', 'id_tahun_ajar','created_at', 'updated_at'],
    $casts = [
        'id_jadwal' => 'string',
    ];

    protected $dates = ['waktu_mulai', 'waktu_selesai'];

    public function getWaktuMulaiAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getWaktuSelesaiAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
}
