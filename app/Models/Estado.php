<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    // Opcional: Si la tabla tiene un nombre diferente al pluralizado del modelo
    protected $table = 'estados';  // Solo si la tabla se llama de manera diferente
    protected $primaryKey = 'id_estado';  // Si la clave primaria tiene un nombre diferente a 'id'

    // Puedes añadir relaciones u otros métodos si es necesario
}
