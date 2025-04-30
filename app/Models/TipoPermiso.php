<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    // 1️⃣ Nombre de la tabla
    protected $table = 'tipo_permiso';

    // 2️⃣ Llave primaria (por defecto “id” coincide con la tabla)
    protected $primaryKey = 'id';

    // 3️⃣ Campos asignables
    protected $fillable = [
        'nombre_permiso',
    ];

    // 4️⃣ Timestamps
    public $timestamps = true;

    // 5️⃣ Relación muchos-a-muchos con Usuario a través de la tabla pivot usuarios_permisos
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuarios_permisos',   // nombre de la tabla pivot
            'permiso_id',          // FK hacia esta tabla (tipo_permiso.id)
            'usuario_id'           // FK hacia Usuario (usuarios.id)
        );
    }
}
