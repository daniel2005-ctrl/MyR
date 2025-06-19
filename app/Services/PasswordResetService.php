<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetService
{
    /**
     * Crear un token de reset y guardarlo en la base de datos
     */
    public function createToken($email)
    {
        // Eliminar tokens anteriores para este email
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        
        // Crear nuevo token
        $token = Str::random(64);
        
        // Guardar en la base de datos
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
            'used' => false
        ]);
        
        return $token;
    }
    
    /**
     * Verificar si el token es válido y no ha sido usado
     */
    public function validateToken($email, $token)
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
            
        if (!$record) {
            return false;
        }
        
        // Verificar si el token ya fue usado
        if ($record->used) {
            return false;
        }
        
        // Verificar si el token no ha expirado (60 minutos)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            // Eliminar token expirado
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return false;
        }
        
        // Verificar si el token coincide
        return Hash::check($token, $record->token);
    }
    
    /**
     * Marcar el token como usado
     */
    public function markTokenAsUsed($email)
    {
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->update(['used' => true]);
    }
    
    /**
     * Eliminar el token después de usarlo
     */
    public function deleteToken($email)
    {
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }
}