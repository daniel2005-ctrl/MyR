<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class Usuario extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $incrementing = true;     // Asegurar que el ID es auto-incremental
    protected $keyType = 'int';      // Tipo de clave primaria

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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

    /**
     * Enviar notificación de restablecimiento de contraseña personalizada
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
