<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';
    protected $primaryKey = 'id_pro';     // ← tu clave primaria personalizada
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre_pro',
        'descripcion',
        'precio_min',
        'precio_max',
        'area_min',
        'area_max',
        'ubicacion_pro',
        'imagenes_pro',
        'videos_pro',
        'id_tipo_permiso',
    ];

    protected $casts = [
        'imagenes_pro' => 'array',
        'videos_pro' => 'array',
    ];

    public function getImagenesProAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setImagenesProAttribute($value)
    {
        $this->attributes['imagenes_pro'] = json_encode(is_array($value) ? $value : json_decode($value, true));
    }

    public function getVideosProAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setVideosProAttribute($value)
    {
        $this->attributes['videos_pro'] = json_encode(is_array($value) ? $value : json_decode($value, true));
    }
}
