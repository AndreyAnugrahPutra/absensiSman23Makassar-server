<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAbsensi extends Model
{
    use HasFactory;
    protected   $table = 'tb_form_absen',
    $primaryKey = 'id_form',
    $fillable = ['id_form', 'id_jadwal','id_kelas', 'kelas', 'id_mapel', 'mapel', 'id_lokasi','created_at', 'updated_at'],
    $casts = [
        'id_form' => 'string',
    ];

    protected $dates = ['created_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
