<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubsidioCredito extends Model
{
    use HasFactory;

    protected $table = 'subsidios_creditos';

    protected $fillable = [
        'tipo',
        'nombre',
        'url',
        'orden',
        'activo'
    ];

    public function scopeOrdenados($query)
    {
        return $query->orderby('orden', 'asc');
    }

    // Add scope for active records
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
