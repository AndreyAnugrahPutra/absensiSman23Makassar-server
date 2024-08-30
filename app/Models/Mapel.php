<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;
    protected   $table = 'tb_mapel',
    $primaryKey = 'id_mapel',
    $fillable = ['id_mapel','nama_mapel', 'id_guru', 'nama_guru', 'waktu_mulai', 'waktu_selesai', 'created_at', 'updated_at'],
    $casts = [
        'id_mapel' => 'string',
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
