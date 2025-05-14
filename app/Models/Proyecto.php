<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    // Configuración de la tabla y clave primaria
    protected $table = 'proyectos';
    protected $primaryKey = 'id_pro';
    public $incrementing = true;
    protected $keyType = 'int';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_pro',
        'tipo_pro',
        'descripcion',
        'terminado',
        'precio',
        'area',
        'ubicacion_pro',
        'imagenes_pro',
        'videos_pro',
        'apartamentos',
        'id_tipo_permiso',
        'id_estado',
    ];

    // Cast de los atributos a arrays cuando se obtienen de la base de datos
    protected $casts = [
        'descripcion'      => 'array',
        'precio'           => 'array',
        'area'             => 'array',
        'imagenes_pro'     => 'array',
        'videos_pro'       => 'array',
        'apartamentos'     => 'array',
    ];

    // Relaciones
    public function zonasCatalogo()
    {
        return $this->belongsToMany(
            ZonaCatalogo::class,               // El modelo relacionado
            'proyecto_zona_social',            // La tabla pivote
            'proyecto_id',                     // Clave foránea de este modelo
            'zona_catalogo_id'                 // Clave foránea del modelo relacionado
        );
    }

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'id_tipo_permiso', 'id_tipo');
    }

    // En el modelo Proyecto
public function estado()
{
    return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
}

}

