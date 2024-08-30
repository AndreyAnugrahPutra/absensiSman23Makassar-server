<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiAbsen extends Model
{
    use HasFactory;
    protected $table = 'tb_lokasi_absen',
    $primaryKey = 'id_lokasi',
    $fillable = ['id_lokasi','nama_lokasi','alamat','latitude','longitude','radius','created_at','updated_at'],
    $casts = [
        'id_lokasi' => 'string',
    ];
}
