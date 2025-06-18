<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecurityCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SecurityCodeController extends Controller
{
    /**
     * Obtener el código actual
     */
    public function current(): JsonResponse
    {
        $code = SecurityCode::getCurrent();
        
        if (!$code) {
            // Si no hay código válido, generar uno nuevo
            $code = SecurityCode::generateNew();
        }
        
        return response()->json([
            'success' => true,
            'code' => $code->code,
            'expires_at' => $code->expires_at->toISOString(),
            'remaining_seconds' => $code->getRemainingSeconds()
        ]);
    }

    /**
     * Generar un nuevo código
     */
    public function generate(): JsonResponse
    {
        $code = SecurityCode::generateNew();
        
        return response()->json([
            'success' => true,
            'code' => $code->code,
            'expires_at' => $code->expires_at->toISOString(),
            'remaining_seconds' => $code->getRemainingSeconds(),
            'message' => 'Nuevo código generado exitosamente'
        ]);
    }

    /**
     * Validar un código
     */
    public function validateCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);
        
        $inputCode = $request->input('code');
        $isValid = SecurityCode::validate($inputCode);
        
        return response()->json([
            'success' => true,
            'valid' => $isValid,
            'message' => $isValid ? 'Código válido' : 'Código inválido o expirado'
        ]);
    }

    /**
     * Obtener información del código para debugging
     */
    public function info(): JsonResponse
    {
        $code = SecurityCode::getCurrent();
        
        if (!$code) {
            return response()->json([
                'success' => true,
                'message' => 'No hay código activo',
                'data' => null
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'code' => $code->code,
                'generated_at' => $code->generated_at->toISOString(),
                'expires_at' => $code->expires_at->toISOString(),
                'remaining_seconds' => $code->getRemainingSeconds(),
                'is_expired' => $code->isExpired(),
                'is_active' => $code->is_active
            ]
        ]);
    }
}
