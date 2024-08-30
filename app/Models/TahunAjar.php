<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjar extends Model
{
    use HasFactory;
    protected   $table = 'tb_tahun_ajar',
    $primaryKey = 'id_tahun_ajar',
    $fillable = ['id_tahun_ajar','semester','mulai','selesai', 'created_at', 'updated_at'],
    $casts = [
        'id_tahun_ajar' => 'string',
    ];
}
