<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecurityCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SecurityCodeController extends Controller
{
    /**
     * Obtener el código actual
     */
    public function current(): JsonResponse
    {
        try {
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
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error de base de datos en SecurityCodeController::current: ' . $e->getMessage());
            Log::error('Código de error SQL: ' . $e->getCode());
            Log::error('Info de conexión: Host=' . config('database.connections.mysql.host') . ', DB=' . config('database.connections.mysql.database'));
            
            $errorMessage = 'Error de conexión a la base de datos Railway';
            if (str_contains($e->getMessage(), 'Connection refused')) {
                $errorMessage = 'No se puede conectar al servidor Railway. Verifique la conectividad.';
            } elseif (str_contains($e->getMessage(), 'Access denied')) {
                $errorMessage = 'Credenciales de Railway incorrectas. Verifique usuario y contraseña.';
            } elseif (str_contains($e->getMessage(), "Table 'railway.security_codes' doesn't exist")) {
                $errorMessage = 'La tabla security_codes no existe en Railway. Ejecute las migraciones.';
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión a la base de datos',
                'message' => $errorMessage,
                'sql_error_code' => $e->getCode(),
                'debug' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error en SecurityCodeController::current: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => 'No se pudo obtener el código de seguridad',
                'debug' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Generar un nuevo código
     */
    public function generate(): JsonResponse
    {
        try {
            $code = SecurityCode::generateNew();
            
            return response()->json([
                'success' => true,
                'code' => $code->code,
                'expires_at' => $code->expires_at->toISOString(),
                'remaining_seconds' => $code->getRemainingSeconds(),
                'message' => 'Nuevo código generado exitosamente'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error de base de datos en SecurityCodeController::generate: ' . $e->getMessage());
            Log::error('Código de error SQL: ' . $e->getCode());
            Log::error('Info de conexión: Host=' . config('database.connections.mysql.host') . ', DB=' . config('database.connections.mysql.database'));
            
            $errorMessage = 'Error de conexión a la base de datos Railway';
            if (str_contains($e->getMessage(), 'Connection refused')) {
                $errorMessage = 'No se puede conectar al servidor Railway. Verifique la conectividad.';
            } elseif (str_contains($e->getMessage(), 'Access denied')) {
                $errorMessage = 'Credenciales de Railway incorrectas. Verifique usuario y contraseña.';
            } elseif (str_contains($e->getMessage(), "Table 'railway.security_codes' doesn't exist")) {
                $errorMessage = 'La tabla security_codes no existe en Railway. Ejecute las migraciones.';
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión a la base de datos',
                'message' => $errorMessage,
                'sql_error_code' => $e->getCode(),
                'debug' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error en SecurityCodeController::generate: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => 'No se pudo generar el código de seguridad',
                'debug' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
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

    /**
     * Health check para el sistema de códigos de seguridad
     */
    public function health(): JsonResponse
    {
        try {
            $activeCodesCount = SecurityCode::where('is_active', true)->count();
            $lastCodeGenerated = SecurityCode::latest('created_at')->first();
            
            return response()->json([
                'status' => 'healthy',
                'active_codes' => $activeCodesCount,
                'last_generated' => $lastCodeGenerated?->created_at,
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unhealthy',
                'error' => 'Database connection failed',
                'timestamp' => now()
            ], 503);
        }
    }
    // Agregar en el constructor del controlador
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $response = $next($request);
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            return $response;
        });
    }
}
