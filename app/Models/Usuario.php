<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable, HasFactory; // ← bien escrito aquí

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'tipo_permiso_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class);
    }

    public function isAdmin()
    {   
    return $this->tipo_permiso_id === 1; // 1 es admin
    }

}
