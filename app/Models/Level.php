<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected   $table = 'tb_level',
    $primaryKey = 'id_level',
    $fillable = ['id_level','nama_level','created_at', 'updated_at'],
    $casts = [
        'id_level' => 'string',
    ];
}
