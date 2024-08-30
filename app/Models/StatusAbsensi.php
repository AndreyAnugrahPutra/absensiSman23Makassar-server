<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAbsensi extends Model
{
    use HasFactory;
    protected   $table = 'tb_status_absensi',
    $primaryKey = 'id_status_absensi',
    $fillable = ['id_status_absensi','status_absensi','created_at', 'updated_at'],
    $casts = [
        'id_status_absensi' => 'string',
    ];
}
