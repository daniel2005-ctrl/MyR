<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsidio extends Model
{
    // 1️⃣ Nombre de la tabla
    protected $table = 'subsidios';

    // 2️⃣ Llave primaria (por defecto 'id', coincidente con tu tabla)
    protected $primaryKey = 'id';

    // 3️⃣ Campos asignables
    protected $fillable = [
        'nombre_subsidio',
        'monto',
    ];

    // 4️⃣ Timestamps (tienes created_at y updated_at)
    public $timestamps = true;

    // 5️⃣ (Opcional) Relación con proyectos, si en el futuro agregas foreign key
    // public function proyectos()
    // {
    //     return $this->hasMany(Proyecto::class, 'subsidio_id', 'id');
    // }
}
