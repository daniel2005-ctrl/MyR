<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\File;
use Exception;

class SubirImagenesCloudinary extends Command
{
    protected $signature = 'cloudinary:subir-imagenes';
    protected $description = 'Sube todas las imágenes desde public/imagenes a Cloudinary, incluyendo subcarpetas';

    public function handle()
    {
        // Verificar que la configuración de Cloudinary esté disponible
        if (!env('CLOUDINARY_URL')) {
            $this->error('CLOUDINARY_URL no está configurado en el archivo .env');
            return 1;
        }

        // Obtener todos los archivos de la carpeta public/imagenes
        $rutaImagenes = public_path('imagenes');
        $archivos = File::allFiles($rutaImagenes);

        $this->info('Iniciando subida de imágenes a Cloudinary...');
        $this->info('Total de archivos encontrados: ' . count($archivos));

        foreach ($archivos as $archivo) {
            // Obtener información del archivo
            $nombreArchivo = $archivo->getFilename();
            $rutaCompleta = $archivo->getPathname();
            $tamanoArchivo = filesize($rutaCompleta);
            
            // Crear la ruta de la carpeta en Cloudinary basada en la estructura local
            $rutaRelativa = str_replace($rutaImagenes . DIRECTORY_SEPARATOR, '', $archivo->getPath());
            $carpetaCloudinary = 'proyectos';
            
            // Corregir la lógica de carpetas para evitar rutas absolutas
            if (!empty($rutaRelativa) && $rutaRelativa !== $rutaImagenes) {
                $carpetaCloudinary .= '/' . str_replace(DIRECTORY_SEPARATOR, '/', $rutaRelativa);
            }

            $this->info("Subiendo: {$nombreArchivo} a carpeta: {$carpetaCloudinary}");
            
            // Verificar tamaño del archivo (10MB = 10485760 bytes)
            $limiteTamano = 10485760;
            if ($tamanoArchivo > $limiteTamano) {
                $this->warn("⚠️  Archivo {$nombreArchivo} ({$this->formatBytes($tamanoArchivo)}) excede 10MB, se redimensionará automáticamente");
            }

            try {
                // Configurar opciones de upload con transformaciones automáticas
                $opciones = [
                    'folder' => $carpetaCloudinary,
                    'use_filename' => true,
                    'unique_filename' => false,
                    'overwrite' => true
                ];
                
                // Si el archivo es muy grande, aplicar transformaciones para reducir tamaño
                if ($tamanoArchivo > $limiteTamano) {
                    $opciones['transformation'] = [
                        'quality' => 'auto:good',
                        'fetch_format' => 'auto',
                        'width' => 1920,
                        'height' => 1920,
                        'crop' => 'limit'
                    ];
                }

                $resultado = Cloudinary::uploadApi()->upload($rutaCompleta, $opciones);

                // Verificar que el resultado no sea null y contenga los datos esperados
                if ($resultado && isset($resultado['secure_url'])) {
                    $this->info("✓ Subido exitosamente: {$resultado['secure_url']}");
                    $this->info("  Public ID: {$resultado['public_id']}");
                    if ($tamanoArchivo > $limiteTamano) {
                        $this->info("  📉 Tamaño optimizado automáticamente");
                    }
                } else {
                    $this->error("✗ Error: El resultado del upload es null o no contiene secure_url para {$nombreArchivo}");
                }

            } catch (Exception $e) {
                $this->error("✗ Error subiendo {$nombreArchivo}: " . $e->getMessage());
            }
        }

        $this->info('Proceso de subida completado.');
        return 0;
    }

    // Función auxiliar para formatear bytes
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }
}
