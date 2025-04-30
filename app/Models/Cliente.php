<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // 1️⃣ Nombre de la tabla
    protected $table = 'clientes';

    // 2️⃣ Llave primaria personalizada
    protected $primaryKey = 'id_cliente';

    // 3️⃣ Campos asignables
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'id_pro',
        'fecha_envio',
    ];

    // 4️⃣ Timestamps (tienes created_at y updated_at)
    public $timestamps = true;

    // 5️⃣ Relación con Proyecto (id_pro → proyectos.id_pro)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_pro', 'id_pro');
    }
}
