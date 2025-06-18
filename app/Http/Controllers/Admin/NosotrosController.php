<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nosotros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Quality;

class NosotrosController extends Controller
{
    public function edit()
    {
        $nosotros = Nosotros::first();
        
        return view('admin.nosotros.nosotros', compact('nosotros'));

    }

    public function showPublic()
{
$nosotros = Nosotros::first();
return view('proyectos.nosotros', compact('nosotros'));
}

    public function update(Request $request)
    {
        $request->validate([
            'imagen_fondo' => 'nullable|image',
            'imagen_vision' => 'nullable|image',
            'imagen_mision' => 'nullable|image',
            'valores' => 'nullable|string',
        ]);

        $nosotros = Nosotros::first();
        
        // Actualizar campo valores
        if ($request->has('valores')) {
            $nosotros->valores = $request->valores;
        }
        
        // Configurar Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);
        
        foreach (['imagen_fondo', 'imagen_vision', 'imagen_mision'] as $campo) {
            if ($request->hasFile($campo)) {
                $archivo = $request->file($campo);
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaDestino = public_path('imagenes/nosotros');
                
                // Mover archivo a /public/imagenes/nosotros
                $archivo->move($rutaDestino, $nombreArchivo);
                
                // Guardar ruta relativa para usar en las vistas
                $nosotros->$campo = 'imagenes/nosotros/' . $nombreArchivo;
            }
        }
        
        $nosotros->save();
        
        return redirect()->back()->with('success', 'Sección "Nosotros" actualizada');
    }
}


