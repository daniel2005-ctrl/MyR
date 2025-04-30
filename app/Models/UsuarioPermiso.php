<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioPermiso extends Model
{
    protected $table = 'usuarios_permisos';

    public $timestamps = true; // Ya que tienes 'created_at' y 'updated_at'

    protected $fillable = [
        'usuario_id',
        'permiso_id',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    // Relación con el modelo TipoPermiso
    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'permiso_id', 'id');
    }
}
