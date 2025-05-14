<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonaCatalogo extends Model
{
    protected $table = 'zonas_catalogo';
    protected $fillable = ['titulo', 'icono'];

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_zona_social', 'zona_catalogo_id', 'proyecto_id');
    }
}
