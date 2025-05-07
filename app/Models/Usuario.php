<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;  // Importa la notificación personalizada

class Usuario extends Authenticatable
{

   public function isAdmin()
{
    return $this->tipo_permiso_id === 1;
}

    use Notifiable;

    // Nombre de la tabla en BD
    protected $table = 'usuarios';
    public function esAdmin(): bool
    {
        return $this->tipo_permiso_id === 1;
    }

    // Primaria (opcional si es 'id')
    protected $primaryKey = 'id';

    // Campos asignables
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'tipo_permiso_id',
    ];

    // Campos ocultos en array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    

    // Laravel gestiona created_at/updated_at
    public $timestamps = true;

   

    

    // Relación muchos a muchos con permisos
    public function permisos()
    {
        return $this->belongsToMany(
            TipoPermiso::class,
            'usuarios_permisos',
            'usuario_id',
            'permiso_id'
        );
    }

    // Sobrescribir este método para usar la notificación personalizada
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
   
    

   

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'tipo_permiso_id');
    }

   

}
    






