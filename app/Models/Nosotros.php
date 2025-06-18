<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nosotros extends Model
{
    protected $table = 'nosotros';

    protected $fillable = [
        'titulo', 'introduccion', 'vision', 'mision', 'valores',
        'imagen_fondo', 'imagen_vision', 'imagen_mision',
    ];

    protected $casts = [
        'valores' => 'array',
    ];
}
