<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $fillable = [
        'proyecto_id',
        'nombre_pro',
        'tipo_apto',
        'valor_apartamento',
        'incluye_parqueadero',
        'valor_parqueadero',
        'cuota_inicial',
        'separacion'
    ];

    /**
     * Relación con el modelo Proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
