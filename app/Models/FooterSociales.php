<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSociales extends Model

{
    use HasFactory;

    protected $table = 'footer_sociales';

    protected $fillable = [
        'nombre',
        'icono',
        'url',
    ];

    public $timestamps = true; // o false si no usás created_at/updated_at
}
