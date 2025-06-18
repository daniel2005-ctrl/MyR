<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'slug',
        'tipo_pro',
        'descripcion',
        'terminado',
        'precio',
        'area',
        'parqueadero',
        'ubicacion_pro',
        'imagenes_pro',
        'imagenes_header',
        'videos_pro',
        'apartamentos',
        'id_tipo_permiso',
        'id_estado',
        'visitas',
    ];

    // Cast de los atributos a arrays cuando se obtienen de la base de datos
    protected $casts = [
        'descripcion'   => 'array',
        'precio'        => 'array',
        'area'          => 'array',
        'imagenes_pro'  => 'array',
        'imagenes_header' => 'array',
        'videos_pro'    => 'array',
        'apartamentos'  => 'array',
    ];
    
    

    // Relaciones
    public function zonasCatalogo()
    {
        return $this->belongsToMany(
            ZonaCatalogo::class,
            'proyecto_zona_social',
            'proyecto_id',
            'zona_catalogo_id'
        );
    }

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'id_tipo_permiso', 'id_tipo');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    // Generar slug automáticamente al crear/actualizar
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proyecto) {
            $proyecto->slug = $proyecto->generateSlug($proyecto->nombre_pro);
        });

        static::updating(function ($proyecto) {
            if ($proyecto->isDirty('nombre_pro')) {
                $proyecto->slug = $proyecto->generateSlug($proyecto->nombre_pro);
            }
        });
    }

    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // Método para buscar por slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDescripcionTextAttribute()
    {
        return is_array($this->descripcion) ? implode(' ', $this->descripcion) : $this->descripcion;
    }
}

