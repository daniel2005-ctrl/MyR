<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SecurityCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'generated_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Generar un nuevo código de seguridad
     */
    public static function generateNew()
    {
        // Eliminar todos los códigos anteriores
        self::truncate();
        
        // Generar nuevo código
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $now = Carbon::now();
        
        return self::create([
            'code' => $code,
            'generated_at' => $now,
            'expires_at' => $now->addMinutes(10),
            'is_active' => true
        ]);
    }

    /**
     * Obtener el código activo actual
     */
    public static function getCurrent()
    {
        return self::where('is_active', true)
                  ->where('expires_at', '>', Carbon::now())
                  ->first();
    }

    /**
     * Validar un código
     */
    public static function validate($inputCode)
    {
        $current = self::getCurrent();
        return $current && $current->code === $inputCode;
    }

    /**
     * Verificar si el código ha expirado
     */
    public function isExpired()
    {
        return Carbon::now()->gt($this->expires_at);
    }

    /**
     * Obtener tiempo restante en segundos
     */
    public function getRemainingSeconds()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return Carbon::now()->diffInSeconds($this->expires_at, false);
    }
}
