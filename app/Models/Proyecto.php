<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario; // 🔍 Importa el modelo Usuario

class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $primaryKey = 'id_pro';

    protected $fillable = [
        'nombre_pro',
        'tipo_pro',
        'tamano_pro',
        'descripcion',
        'terminado',
        'imagenes_pro',
        'videos_pro',
        'ubicacion_pro',
        'id_usu',
    ];

    public $timestamps = true;

    /**
     * Relación con Usuario (propietario del proyecto)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usu', 'id');
    }

    /**
     * Si en el futuro quieres relacionarlo con Clientes:
     * 
     * public function cliente()
     * {
     *     return $this->hasOne(Cliente::class, 'id_pro', 'id_pro');
     * }
     * 
     * O con Subsidios:
     * 
     * public function subsidio()
     * {
     *     return $this->hasOne(Subsidio::class, 'id_pro', 'id_pro');
     * }
     */
}
