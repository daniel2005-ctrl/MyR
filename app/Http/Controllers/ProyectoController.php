<?php


namespace App\Http\Controllers;
use App\Models\Configuracion;
use App\Models\Proyecto;
use App\Models\ZonaCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estado;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProyectoController extends Controller
{
   public function index(Request $request)
{
    // Construir la consulta base
    $query = Proyecto::with(['estado', 'zonasCatalogo']);
    
    // Determina si el usuario está autenticado y su tipo
    if (Auth::check()) {
        // Si es administrador, mostrar todos los proyectos
        if (Auth::user()->tipo_permiso_id != 1) {
            // Si no es administrador, mostrar solo los proyectos terminados
            $query->where('terminado', 1);
        }
    } else {
        // Si no está autenticado, solo mostrar proyectos terminados
        $query->where('terminado', 1);
    }
    
    // Aplicar filtros
    if ($request->filled('tipo_pro')) {
        $query->where('tipo_pro', $request->tipo_pro);
    }
    
    // ELIMINADO: Filtro de estado de construcción
    
    if ($request->filled('estado_proyecto')) {
        $query->where('id_estado', $request->estado_proyecto);
    }
    
    // Filtro por zonas sociales específicas (selección múltiple)
    if ($request->filled('zonas_sociales')) {
        $zonasSeleccionadas = $request->zonas_sociales;
        $query->whereHas('zonasCatalogo', function($q) use ($zonasSeleccionadas) {
            $q->whereIn('zonas_catalogo.id', $zonasSeleccionadas);
        });
    }
    
    // Obtener los proyectos paginados
    $proyectos = $query->paginate(10)->appends($request->query());
    
    // Obtener datos para los filtros
    $estados = \App\Models\Estado::all();
    $zonasCatalogo = ZonaCatalogo::all(); // Agregar todas las zonas para el filtro
    
    // Retorna la vista con los proyectos y datos para filtros
    return view('proyectos.index', compact('proyectos', 'estados', 'zonasCatalogo'));
}
// Agregar este método después del método index existente
public function adminIndex(Request $request)
{
    // Para admin, mostrar TODOS los proyectos sin filtrar por terminado
    $query = Proyecto::with(['estado', 'zonasCatalogo']);
    
    // Aplicar filtros
    if ($request->filled('tipo_pro')) {
        $query->where('tipo_pro', $request->tipo_pro);
    }
    
    if ($request->filled('estado_proyecto')) {
        $query->where('id_estado', $request->estado_proyecto);
    }
    
    // Filtro por zonas sociales específicas (selección múltiple)
    if ($request->filled('zonas_sociales')) {
        $zonasSeleccionadas = $request->zonas_sociales;
        $query->whereHas('zonasCatalogo', function($q) use ($zonasSeleccionadas) {
            $q->whereIn('zonas_catalogo.id', $zonasSeleccionadas);
        });
    }
    
    // Obtener los proyectos paginados
    $proyectos = $query->paginate(10)->appends($request->query());
    
    // Obtener datos para los filtros
    $estados = \App\Models\Estado::all();
    $zonasCatalogo = ZonaCatalogo::all();
    
    // Obtener proyecto más visitado con imagen (CORREGIDO para tu formato)
    $proyectoMasVisto = Proyecto::where('terminado', 1)
        ->whereNotNull('imagenes_header')
        ->where('imagenes_header', '!=', '')
        ->where('imagenes_header', '!=', 'NULL')
        ->orderBy('visitas', 'desc')
        ->first();
    
    // Obtener solo proyectos activos para el selector (CORREGIDO)
    $proyectosActivos = Proyecto::where('terminado', 1)
        ->whereNotNull('imagenes_header')
        ->where('imagenes_header', '!=', '')
        ->where('imagenes_header', '!=', 'NULL')
        ->orderBy('nombre_pro')
        ->get(['id_pro', 'nombre_pro', 'slug', 'imagenes_header', 'visitas']);
    
    // Preparar datos para JavaScript
    $proyectosData = [
        'proyectoMasVisto' => $proyectoMasVisto ? [
            'id_pro' => $proyectoMasVisto->id_pro,
            'nombre_pro' => $proyectoMasVisto->nombre_pro,
            'imagenes_header' => $proyectoMasVisto->imagenes_header,
            'visitas' => $proyectoMasVisto->visitas,
            'slug' => $proyectoMasVisto->slug
        ] : null,
        'proyectosActivos' => $proyectosActivos->map(function($proyecto) {
            return [
                'id_pro' => $proyecto->id_pro,
                'nombre_pro' => $proyecto->nombre_pro,
                'slug' => $proyecto->slug,
                'imagenes_header' => $proyecto->imagenes_header,
                'visitas' => $proyecto->visitas
            ];
        }),
        'routes' => [
            'usarProyectoMasVisitado' => route('admin.usar-proyecto-mas-visitado'),
            'cambiarFondoProyecto' => route('admin.cambiar-fondo-proyecto', ['slug' => 'PLACEHOLDER']),
            'subirImagenPersonalizada' => route('admin.subir-imagen-personalizada')
        ]
    ];
    
    // Retorna la vista de admin
    return view('admin.proyectos.index', compact('proyectos', 'estados', 'zonasCatalogo', 'proyectosData'));
}


   public function show(Proyecto $proyecto)
{
    // Laravel automáticamente buscará por slug gracias a getRouteKeyName()
    $proyecto->load('zonasCatalogo');
    $proyecto->increment('visitas');

    // Asignación de datos del proyecto con valores predeterminados si es necesario
    $proyecto->titulo       = $proyecto->nombre_pro;
    // Decodificar la descripción si está en formato JSON
    $descripcionDecodificada = is_string($proyecto->descripcion) ? json_decode($proyecto->descripcion, true) : $proyecto->descripcion;
    $proyecto->descripcion  = $descripcionDecodificada ?? [];
    $proyecto->precio       = $proyecto->precio ?? ['min' => null, 'max' => null];
    $proyecto->area         = $proyecto->area ?? ['min' => null, 'max' => null];
    $proyecto->direccion    = $proyecto->ubicacion_pro;
    $proyecto->apartamentos = $proyecto->apartamentos ?? [];

    // Genera el enlace del mapa con la dirección, asegurándose de codificar la URL correctamente
    $proyecto->mapa         = 'https://www.google.com/maps?q=' . urlencode($proyecto->direccion) . '&output=embed';

    // Retorna la vista con la información del proyecto
    return view('proyectos.proyectos', compact('proyecto'));
}

public function create()
{
    // Obtener todos los estados disponibles
    $estados = Estado::all();

    // Obtener las zonas sociales
    $zonas = ZonaCatalogo::all();

    $proyectos = Proyecto::where('terminado', 0)->get();

    return view('admin.proyectos.crear', compact('zonas', 'estados'));
}

public function edit(Proyecto $proyecto)
{
    $proyecto->load('zonasCatalogo'); // Carga las zonas relacionadas
    $estados = Estado::all();
    $zonasCatalogo = ZonaCatalogo::all(); // Todas las zonas posibles (catálogo)

    return view('admin.proyectos.edit', compact('proyecto', 'estados', 'zonasCatalogo'));
}

public function destroy(Proyecto $proyecto)
{
    // Eliminar imágenes de Cloudinary si existen
    $imagenesPro = $proyecto->imagenes_pro;
    if ($imagenesPro) {
        // Si es string JSON, decodificar
        if (is_string($imagenesPro)) {
            $imagenesPro = json_decode($imagenesPro, true) ?? [];
        }
        
        if (is_array($imagenesPro)) {
            foreach ($imagenesPro as $imagen) {
                try {
                    $publicId = $this->extractPublicIdFromUrl($imagen);
                    if ($publicId) {
                        Cloudinary::uploadApi()->destroy($publicId);
                    }
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar imagen de Cloudinary: ' . $imagen . ' - ' . $e->getMessage());
                }
            }
        }
    }
    
    // Eliminar imagen header de Cloudinary si existe
    if ($proyecto->imagenes_header) {
        try {
            $publicId = $this->extractPublicIdFromUrl($proyecto->imagenes_header);
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
        } catch (Exception $e) {
            Log::warning('No se pudo eliminar imagen header de Cloudinary: ' . $proyecto->imagenes_header . ' - ' . $e->getMessage());
        }
    }
    
    // Eliminar imágenes de apartamentos de Cloudinary si existen
    $apartamentos = $proyecto->apartamentos;
    if ($apartamentos) {
        // Si es string JSON, decodificar
        if (is_string($apartamentos)) {
            $apartamentos = json_decode($apartamentos, true) ?? [];
        }
        
        if (is_array($apartamentos)) {
            foreach ($apartamentos as $apartamento) {
                try {
                    $publicId = $this->extractPublicIdFromUrl($apartamento);
                    if ($publicId) {
                        Cloudinary::uploadApi()->destroy($publicId);
                    }
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar imagen de apartamento de Cloudinary: ' . $apartamento . ' - ' . $e->getMessage());
                }
            }
        }
    }
    
    // Intentar eliminar la carpeta completa del proyecto en Cloudinary
    try {
        $folderName = Str::slug($proyecto->nombre_pro);
        // Primero eliminar todos los assets dentro de la carpeta
        Cloudinary::adminApi()->deleteAssetsByPrefix('proyectos/' . $folderName . '/');
        
        // Luego eliminar la carpeta vacía
        Cloudinary::adminApi()->deleteFolder('proyectos/' . $folderName);
    } catch (Exception $e) {
        Log::warning('No se pudo eliminar la carpeta del proyecto en Cloudinary: ' . $e->getMessage());
    }
    
    // Desasociar zonas sociales
    $proyecto->zonasCatalogo()->detach();
    
    // Eliminar el proyecto
    $proyecto->delete();
    
    // Verifica si es una petición AJAX (fetch)
    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado correctamente.'
        ]);
    }
    
    // Petición normal (no fetch)
    return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
}

// Cambiar el método update
public function update(Request $request, Proyecto $proyecto)
{
$request->validate([
'nombre_pro' => 'required|string|max:255',
'id_estado' => 'required|exists:estados,id_estado',
'imagenes_pro' => 'nullable|array',
'imagenes_pro.' => 'image|mimes:jpeg,png,jpg,gif,svg',
'imagenes_header' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
'imagenes_a_eliminar' => 'nullable|array',
'imagenes_a_eliminar.' => 'string',
'descripcion' => 'required|array',
'descripcion.' => 'string|max:1000',
'zonas_sociales' => 'nullable|array',
'precio_min' => 'required|numeric',
'precio_max' => 'nullable|numeric',
'area_min' => 'required|numeric',
'area_max' => 'nullable|numeric',
'parqueadero' => 'nullable|numeric|min:0',
'ubicacion_pro' => 'required|string|max:255',
'tipo_pro' => 'required|string|in:VIS,No VIS,VIP',
'apartamentos' => 'nullable|array',
'apartamentos.' => 'image|mimes:jpeg,png,jpg,gif,svg',
'apartamentos_a_eliminar' => 'nullable|array',
'apartamentos_a_eliminar.*' => 'string',
]);

    // Eliminar imágenes de Cloudinary marcadas para eliminar
    $imagenesAEliminar = $request->input('imagenes_a_eliminar', []);
    foreach ($imagenesAEliminar as $imgEliminar) {
        try {
            // Extraer public_id de la URL de Cloudinary
            $publicId = $this->extractPublicIdFromUrl($imgEliminar);
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
        } catch (Exception $e) {
            Log::warning('No se pudo eliminar la imagen de Cloudinary: ' . $imgEliminar . ' - ' . $e->getMessage());
        }
    }
    
    // Filtrar imágenes que quedan
    $imagenesExistentes = $proyecto->imagenes_pro ?? [];
    // Asegurar que sea un array
    if (!is_array($imagenesExistentes)) {
        $imagenesExistentes = [];
    }
    $imagenesActualizadas = array_filter($imagenesExistentes, fn($img) => !in_array($img, $imagenesAEliminar));
    
    // Crear nombre de carpeta basado en el nombre del proyecto
    $folderName = Str::slug($request->input('nombre_pro'));
    
    // Subir nuevas imágenes a Cloudinary
    if ($request->hasFile('imagenes_pro')) {
        foreach ($request->file('imagenes_pro') as $imagen) {
            try {
                $uploadedFile = Cloudinary::uploadApi()->upload($imagen->getRealPath(), [
                    'folder' => 'proyectos/' . $folderName . '/imagenes',
                    'public_id' => 'proyecto_' . $proyecto->id_pro . '_' . time() . '_' . uniqid(),
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $imagenesActualizadas[] = $uploadedFile['secure_url'];
            } catch (Exception $e) {
                Log::error('Error al subir imagen a Cloudinary: ' . $e->getMessage());
                return back()->withErrors(['imagenes_pro' => 'Error al subir una de las imágenes.']);
            }
        }
    }
    
    // Manejo de imagen de portada (imagenes_header) con Cloudinary
    $imagenHeader = $proyecto->imagenes_header; // Mantener la actual por defecto
    $warningMessage = '';
    if ($request->hasFile('imagenes_header')) {
        // Eliminar imagen anterior de Cloudinary si existe
        if ($proyecto->imagenes_header) {
            try {
                $publicId = $this->extractPublicIdFromUrl($proyecto->imagenes_header);
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }
            } catch (Exception $e) {
                Log::warning('No se pudo eliminar la imagen anterior de Cloudinary: ' . $e->getMessage());
            }
        }
        
        // Subir nueva imagen de portada a Cloudinary
        $headerFile = $request->file('imagenes_header');
        
        // Validar resolución de imagen
        $imageInfo = getimagesize($headerFile->getPathname());
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        try {
            $uploadedHeader = Cloudinary::uploadApi()->upload($headerFile->getRealPath(), [
                'folder' => 'proyectos/' . $folderName . '/headers',
                'public_id' => 'header_proyecto_' . $proyecto->id_pro . '_' . time(),
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'width' => 1920,
                    'height' => 1080,
                    'crop' => 'limit'
                ]
            ]);
            $imagenHeader = $uploadedHeader['secure_url'];
        } catch (Exception $e) {
            Log::error('Error al subir imagen header a Cloudinary: ' . $e->getMessage());
            return back()->withErrors(['imagenes_header' => 'Error al subir la imagen de portada.']);
        }
        
        // Agregar advertencia si la imagen no es HD
        if ($width < 1280 || $height < 720) {
            $warningMessage = ' (Advertencia: La imagen de portada tiene una resolución de ' . $width . 'x' . $height . ', que es menor a la recomendada de 1280x720 para mejor calidad)';
        }
    }
    
    // Eliminar apartamentos de Cloudinary marcados para eliminar
    $apartamentosAEliminar = $request->input('apartamentos_a_eliminar', []);
    foreach ($apartamentosAEliminar as $aptoEliminar) {
        try {
            // Extraer public_id de la URL de Cloudinary
            $publicId = $this->extractPublicIdFromUrl($aptoEliminar);
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
        } catch (Exception $e) {
            Log::warning('No se pudo eliminar la imagen del apartamento de Cloudinary: ' . $aptoEliminar . ' - ' . $e->getMessage());
        }
    }
    
    // Filtrar apartamentos que quedan
    $apartamentosExistentes = $proyecto->apartamentos ?? [];
    
    // Asegurar que tenemos un array antes de usar array_filter
    if (is_string($apartamentosExistentes)) {
        $apartamentosExistentes = json_decode($apartamentosExistentes, true) ?? [];
    }
    
    // Verificar que es un array válido
    if (!is_array($apartamentosExistentes)) {
        $apartamentosExistentes = [];
    }
    
    $apartamentosActualizados = array_filter($apartamentosExistentes, function($apto) use ($apartamentosAEliminar) {
        return !in_array($apto, $apartamentosAEliminar);
    });
    
    // Subir nuevos apartamentos a Cloudinary
    if ($request->hasFile('apartamentos')) {
        foreach ($request->file('apartamentos') as $apto) {
            try {
                $uploadedApto = Cloudinary::uploadApi()->upload($apto->getRealPath(), [
                    'folder' => 'proyectos/' . $folderName . '/apartamentos',
                    'public_id' => 'apartamento_proyecto_' . $proyecto->id_pro . '_' . time() . '_' . uniqid(),
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $apartamentosActualizados[] = $uploadedApto['secure_url'];
            } catch (Exception $e) {
                Log::error('Error al subir imagen de apartamento a Cloudinary: ' . $e->getMessage());
                return back()->withErrors(['apartamentos' => 'Error al subir una de las imágenes de apartamentos.']);
            }
        }
    }
    
    // Actualizar datos del proyecto
    $proyecto->update([
        'nombre_pro'    => $request->input('nombre_pro'),
        'tipo_pro'      => $request->input('tipo_pro'),
        'descripcion'   => json_encode($request->input('descripcion')),
        'precio'        => [
            'min' => $request->input('precio_min'),
            'max' => $request->input('precio_max'),
        ],
        'area'          => [
            'min' => $request->input('area_min'),
            'max' => $request->input('area_max'),
        ],
        'parqueadero'   => $request->input('parqueadero'),
        'ubicacion_pro' => $request->input('ubicacion_pro'),
        'imagenes_pro'  => array_values($imagenesActualizadas),
        'imagenes_header' => $imagenHeader, // Campo agregado para imagen de portada
        'apartamentos'  => array_values($apartamentosActualizados),
        'id_estado'     => $request->input('id_estado'),
    ]);
    
    // Sincronizar zonas sociales
    $zonas = $request->input('zonas_sociales', []);
    $proyecto->zonasCatalogo()->sync($zonas);
    
    // Modificar el mensaje de éxito para incluir la advertencia
    $successMessage = 'Proyecto actualizado correctamente.' . $warningMessage;
    
    return redirect()->route('proyectos.show', $proyecto->slug)
                 ->with('success', $successMessage);
}


// Agregar estos métodos al ProyectoController

/**
 * Cambiar el estado terminado del proyecto
 */
public function toggleStatus(Request $request, $id)
{
    try {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->terminado = $request->input('terminado');
        $proyecto->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Estado del proyecto actualizado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el estado del proyecto'
        ], 500);
    }
}

/**
 * Eliminar proyecto por ID
 */
public function destroyById($id)
{
    try {
        $proyecto = Proyecto::findOrFail($id);
        
        // Eliminar imágenes de Cloudinary si existen
        if ($proyecto->imagenes_pro && is_array($proyecto->imagenes_pro)) {
            foreach ($proyecto->imagenes_pro as $imagen) {
                try {
                    $publicId = $this->extractPublicIdFromUrl($imagen);
                    if ($publicId) {
                        Cloudinary::uploadApi()->destroy($publicId);
                    }
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar imagen de Cloudinary: ' . $imagen . ' - ' . $e->getMessage());
                }
            }
        }
        
        // Eliminar imagen header de Cloudinary si existe
        if ($proyecto->imagenes_header) {
            try {
                $publicId = $this->extractPublicIdFromUrl($proyecto->imagenes_header);
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }
            } catch (Exception $e) {
                Log::warning('No se pudo eliminar imagen header de Cloudinary: ' . $proyecto->imagenes_header . ' - ' . $e->getMessage());
            }
        }
        
        // Eliminar imágenes de apartamentos de Cloudinary si existen
        if ($proyecto->apartamentos && is_array($proyecto->apartamentos)) {
            foreach ($proyecto->apartamentos as $apartamento) {
                try {
                    $publicId = $this->extractPublicIdFromUrl($apartamento);
                    if ($publicId) {
                        Cloudinary::uploadApi()->destroy($publicId);
                    }
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar imagen de apartamento de Cloudinary: ' . $apartamento . ' - ' . $e->getMessage());
                }
            }
        }
        
        $proyecto->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el proyecto'
        ], 500);
    }
}

public function toggle(Proyecto $proyecto)
{
    $proyecto->terminado = $proyecto->terminado == 1 ? 0 : 1;
    $proyecto->save();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado'
        ]);
    }
    
    return redirect()->route('admin.proyectos.index')->with('success', 'Estado del proyecto actualizado.');
}

    public function store(Request $request)
{
$request->validate([
'nombre_pro' => 'required|string|max:255',
'id_estado' => 'required|exists:estados,id_estado',
'imagenes_pro' => 'required|array',
'imagenes_pro.' => 'image|mimes:jpeg,png,jpg,gif,svg',
'imagenes_header' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
'descripcion' => 'required|array',
'descripcion.' => 'string|max:1000',
'zonas' => 'nullable|array',
'precio_min' => 'required|numeric',
'precio_max' => 'nullable|numeric',
'area_min' => 'required|numeric',
'area_max' => 'nullable|numeric',
'parqueadero' => 'nullable|numeric|min:0',
'ubicacion_pro' => 'required|string|max:255',
'tipo_pro' => 'required|string|in:VIS,No VIS,VIP',
]);

    // Crear nombre de carpeta basado en el nombre del proyecto
    $folderName = Str::slug($request->input('nombre_pro'));
    
    // Manejo imagen de portada con Cloudinary
    $imagenHeader = null;
    $warningMessage = '';
    if ($request->hasFile('imagenes_header')) {
        $headerFile = $request->file('imagenes_header');
        
        // Validar resolución de imagen
        $imageInfo = getimagesize($headerFile->getPathname());
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        try {
            $uploadedHeader = Cloudinary::uploadApi()->upload($headerFile->getRealPath(), [
                'folder' => 'proyectos/' . $folderName,
                'public_id' => 'header_' . time(),
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'width' => 1920,
                    'height' => 1080,
                    'crop' => 'limit'
                ]
            ]);
            $imagenHeader = $uploadedHeader['secure_url'];
        } catch (Exception $e) {
            Log::error('Error al subir imagen header a Cloudinary: ' . $e->getMessage());
            return back()->withErrors(['imagenes_header' => 'Error al subir la imagen de portada.']);
        }
        
        // Agregar advertencia si la imagen no es HD
        if ($width < 1280 || $height < 720) {
            $warningMessage = ' (Advertencia: La imagen de portada tiene una resolución de ' . $width . 'x' . $height . ', que es menor a la recomendada de 1280x720 para mejor calidad)';
        }
    }

    // Manejo imágenes con Cloudinary
    $imagenesPro = [];
    if ($request->hasFile('imagenes_pro')) {
        foreach ($request->file('imagenes_pro') as $index => $imagen) {
            try {
                $uploadedFile = Cloudinary::uploadApi()->upload($imagen->getRealPath(), [
                    'folder' => 'proyectos/' . $folderName,
                    'public_id' => 'imagen_' . ($index + 1) . '_' . time(),
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $imagenesPro[] = $uploadedFile['secure_url'];
            } catch (Exception $e) {
                Log::error('Error al subir imagen a Cloudinary: ' . $e->getMessage());
                return back()->withErrors(['imagenes_pro' => 'Error al subir una de las imágenes.']);
            }
        }
    }

    // Manejo apartamentos con Cloudinary
    $apartamentos = [];
    if ($request->hasFile('apartamentos')) {
        foreach ($request->file('apartamentos') as $index => $apto) {
            try {
                $uploadedApto = Cloudinary::uploadApi()->upload($apto->getRealPath(), [
                    'folder' => 'proyectos/' . $folderName,
                    'public_id' => 'apartamento_' . ($index + 1) . '_' . time(),
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $apartamentos[] = $uploadedApto['secure_url'];
            } catch (Exception $e) {
                Log::error('Error al subir imagen de apartamento a Cloudinary: ' . $e->getMessage());
                return back()->withErrors(['apartamentos' => 'Error al subir una de las imágenes de apartamentos.']);
            }
        }
    }

    // Crear proyecto
  $proyecto = Proyecto::create([
'nombre_pro' => $request->input('nombre_pro'),
'slug' => Str::slug($request->input('nombre_pro')),
'tipo_pro' => $request->input('tipo_pro'),
'descripcion' => json_encode($request->input('descripcion')), // si es array
'imagenes_header' => $imagenHeader, // URL desde Cloudinary
'precio' => [
'min' => $request->input('precio_min'),
'max' => $request->input('precio_max'),
],
'area' => [
'min' => $request->input('area_min'),
'max' => $request->input('area_max'),
],
'parqueadero' => $request->input('parqueadero'),
'ubicacion_pro' => $request->input('ubicacion_pro'),
'imagenes_pro' => json_encode($imagenesPro), // Array de URLs desde Cloudinary
'videos_pro' => json_encode([]), // Puedes cambiar si subes videos luego
'apartamentos' => json_encode($apartamentos),
'id_tipo_permiso' => Auth::user()->tipo_permiso_id,
'terminado' => 1,
'id_estado' => $request->input('id_estado'),
]);
    

    // Asociar zonas sociales si vienen
    if ($request->filled('zonas')) {
        $proyecto->zonasCatalogo()->attach($request->input('zonas'));
    }

    return redirect()->route('proyectos.show', $proyecto->slug);
}

public function subirImagenDestacada(Request $request, Proyecto $proyecto)
{
    $request->validate([
        'imagen_destacada' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);
    
    $file = $request->file('imagen_destacada');
    
    // Eliminar imagen anterior de Cloudinary si existe
    if ($proyecto->imagenes_header) {
        try {
            $publicId = $this->extractPublicIdFromUrl($proyecto->imagenes_header);
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
        } catch (Exception $e) {
            Log::warning('No se pudo eliminar imagen anterior de Cloudinary: ' . $e->getMessage());
        }
    }
    
    try {
        $uploadedFile = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            'folder' => 'proyectos/destacadas',
            'public_id' => 'destacada_proyecto_' . $proyecto->id_pro . '_' . time(),
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
                'width' => 1920,
                'height' => 1080,
                'crop' => 'limit'
            ]
        ]);
        
        $proyecto->imagenes_header = $uploadedFile['secure_url'];
        $proyecto->save();
        
        return back()->with('success', 'Imagen de fondo actualizada correctamente.');
    } catch (Exception $e) {
        Log::error('Error al subir imagen destacada a Cloudinary: ' . $e->getMessage());
        return back()->withErrors(['imagen_destacada' => 'Error al subir la imagen destacada.']);
    }
}

public function usarProyectoMasVisitado()
{
    // Buscar el proyecto con más visitas que tenga imagen
    $proyectoMasVisitado = Proyecto::where('visitas', '>', 0)
        ->whereNotNull('imagenes_header')
        ->where('imagenes_header', '!=', '')
        ->orderBy('visitas', 'desc')
        ->first();
    
    if (!$proyectoMasVisitado) {
        return response()->json([
            'success' => false,
            'message' => 'No hay proyectos con visitas disponibles.'
        ]);
    }
    
    // Manejar imagenes_header de forma más robusta
    $imagenHeader = null;
    
    if (is_string($proyectoMasVisitado->imagenes_header)) {
        // Si es un string, puede ser JSON o un nombre de archivo directo
        $decoded = json_decode($proyectoMasVisitado->imagenes_header, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
            // Es un JSON válido con array
            $imagenHeader = $decoded[0];
        } elseif (!empty($proyectoMasVisitado->imagenes_header)) {
            // Es un string simple con el nombre del archivo
            $imagenHeader = $proyectoMasVisitado->imagenes_header;
        }
    } elseif (is_array($proyectoMasVisitado->imagenes_header) && !empty($proyectoMasVisitado->imagenes_header)) {
        // Ya es un array
        $imagenHeader = $proyectoMasVisitado->imagenes_header[0];
    }
    
    if (!$imagenHeader) {
        return response()->json([
            'success' => false,
            'message' => 'El proyecto más visitado no tiene imagen disponible.'
        ]);
    }
    
    // Validar y limpiar la URL
    $rutaImagen = $this->validarYLimpiarUrlImagen($imagenHeader);
    
    if (!$rutaImagen) {
        return response()->json([
            'success' => false,
            'message' => 'La imagen del proyecto más visitado no es válida.'
        ]);
    }
    
    \App\Models\Configuracion::establecer(
        'imagen_fondo_index',
        $rutaImagen,
        'Fondo automático del proyecto más visitado: ' . $proyectoMasVisitado->nombre_pro . ' (' . $proyectoMasVisitado->visitas . ' visitas)'
    );
    
    // Limpiar las sesiones relacionadas con la vista de imagen destacada
    session()->forget(['vista_imagen_destacada', 'proyecto_mas_visitado']);
    
    // Devolver JSON en lugar de redirect
    return response()->json([
        'success' => true,
        'message' => 'Fondo aplicado correctamente: ' . $proyectoMasVisitado->nombre_pro
    ]);
}

/**
 * Mostrar opción de proyecto más visitado
 */
public function mostrarMasVisto()
{
    // Buscar el proyecto con más visitas que tenga imagen
    $proyectoMasVisitado = Proyecto::where('visitas', '>', 0)
        ->whereNotNull('imagenes_header')
        ->orderBy('visitas', 'desc')
        ->first();
    
    // Guardar en la sesión que estamos mostrando esta vista
    session(['vista_imagen_destacada' => 'mas_visto']);
    session(['proyecto_mas_visitado' => $proyectoMasVisitado]);
    
    return redirect()->route('admin.proyectos.index');
}

/**
 * Mostrar selector de proyectos
 */
public function mostrarSelectorProyecto()
{
    // Obtener proyectos activos para el selector
    $proyectosActivos = Proyecto::where('terminado', 1)
        ->whereNotNull('imagenes_header')
        ->where('imagenes_header', '!=', '')
        ->orderBy('nombre_pro')
        ->get(['id_pro', 'nombre_pro', 'slug', 'imagenes_header', 'visitas']);
    
    // Guardar en la sesión que estamos mostrando esta vista
    session(['vista_imagen_destacada' => 'selector_proyecto']);
    session(['proyectos_activos' => $proyectosActivos]);
    
    return redirect()->route('admin.proyectos.index');
}

/**
 * Mostrar formulario para subir imagen personalizada
 */
public function mostrarSubirImagen()
{
    // Guardar en la sesión que estamos mostrando esta vista
    session(['vista_imagen_destacada' => 'subir_imagen']);
    
    return redirect()->route('admin.proyectos.index');
}

/**
 * Cambiar fondo con imagen personalizada
 */
public function cambiarFondoPersonalizado(Request $request)
{
    $request->validate([
        'imagen_fondo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);
    
    $file = $request->file('imagen_fondo');
    
    // Validar resolución de imagen
    $imageInfo = getimagesize($file->getPathname());
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    
    try {
        // Log para debugging
        Log::info('Intentando subir imagen a Cloudinary: ' . $file->getClientOriginalName());
        
        // Subir imagen a Cloudinary
        $uploadedFile = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            'folder' => 'configuracion/fondos',
            'public_id' => 'fondo_personalizado_' . time(),
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
                'width' => 1920,
                'height' => 1080,
                'crop' => 'limit'
            ]
        ]);
        
        // Log de la URL generada
        Log::info('Imagen subida exitosamente a Cloudinary: ' . $uploadedFile['secure_url']);
        
        \App\Models\Configuracion::establecer(
            'imagen_fondo_index',
            $uploadedFile['secure_url'],
            'Imagen de fondo personalizada del index'
        );
        
        $message = 'Fondo del index actualizado correctamente con URL: ' . $uploadedFile['secure_url'];
        
        // Agregar información sobre la resolución
        if ($width < 1280 || $height < 720) {
            $message .= ' (Nota: La imagen tiene una resolución de ' . $width . 'x' . $height . ', que es menor a la recomendada de 1280x720)';
        }
        
        // Limpiar las sesiones relacionadas con la vista de imagen destacada
        session()->forget(['vista_imagen_destacada', 'proyectos_activos']);
        
        return back()->with('success', $message);
    } catch (Exception $e) {
        Log::error('Error al subir imagen de fondo a Cloudinary: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return back()->withErrors(['imagen_fondo' => 'Error al subir la imagen de fondo: ' . $e->getMessage()]);
    }
}

/**
 * Cambiar fondo usando imagen de proyecto específico
 */
public function cambiarFondoProyecto(Request $request)
{
    $request->validate([
        'proyecto_id' => 'required|exists:proyectos,id_pro'
    ]);
    
    $proyecto = Proyecto::find($request->proyecto_id);
    
    if ($proyecto && $proyecto->imagenes_header) {
        $imagenUrl = null;
        
        // Manejar tanto si es string como si es array
        if (is_string($proyecto->imagenes_header)) {
            // Intentar decodificar como JSON primero
            $decoded = json_decode($proyecto->imagenes_header, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $imagenUrl = $decoded[0];
            } else {
                $imagenUrl = $proyecto->imagenes_header;
            }
        } else if (is_array($proyecto->imagenes_header) && count($proyecto->imagenes_header) > 0) {
            $imagenUrl = $proyecto->imagenes_header[0];
        }
        
        if ($imagenUrl) {
            // Validar y limpiar la URL
            $rutaFinal = $this->validarYLimpiarUrlImagen($imagenUrl);
            
            if (!$rutaFinal) {
                return back()->with('error', 'La imagen del proyecto seleccionado no es válida.');
            }
            
            \App\Models\Configuracion::establecer(
                'imagen_fondo_index',
                $rutaFinal,
                'Fondo del index desde proyecto: ' . $proyecto->nombre_pro . ' (' . ($proyecto->visitas ?? 0) . ' visitas)'
            );
        } else {
            return back()->with('error', 'El proyecto seleccionado no tiene imagen disponible.');
        }
        
        // Limpiar las sesiones relacionadas con la vista de imagen destacada
        session()->forget(['vista_imagen_destacada', 'proyectos_activos']);
        
        return back()->with('success', 'Fondo cambiado al proyecto: ' . $proyecto->nombre_pro);
    }
    
    return back()->with('error', 'El proyecto seleccionado no tiene imagen disponible.');
}

/**
 * Validar y limpiar URL de imagen
 */
private function validarYLimpiarUrlImagen($imagenUrl)
{
    if (empty($imagenUrl)) {
        return null;
    }
    
    // Log para debugging
    Log::info('Validando URL de imagen: ' . $imagenUrl);
    
    // Si es una URL de Cloudinary o cualquier URL HTTP válida
    if (str_contains($imagenUrl, 'cloudinary.com') || str_starts_with($imagenUrl, 'http')) {
        // Para URLs de Cloudinary, ser menos restrictivo
        if (str_contains($imagenUrl, 'cloudinary.com')) {
            Log::info('URL de Cloudinary detectada: ' . $imagenUrl);
            return $imagenUrl; // Devolver directamente la URL de Cloudinary
        }
        
        // Para otras URLs HTTP, validar que sean válidas
        if (filter_var($imagenUrl, FILTER_VALIDATE_URL)) {
            Log::info('URL HTTP válida: ' . $imagenUrl);
            return $imagenUrl;
        }
        
        // URL malformada
        Log::warning('URL malformada: ' . $imagenUrl);
        return null;
    } else {
        // Es una ruta local, agregar el prefijo imagenes/ si no lo tiene
        $rutaLocal = str_starts_with($imagenUrl, 'imagenes/') ? $imagenUrl : 'imagenes/' . $imagenUrl;
        
        // Verificar que el archivo existe físicamente
        if (file_exists(public_path($rutaLocal))) {
            Log::info('Archivo local encontrado: ' . $rutaLocal);
            return $rutaLocal;
        }
        
        // Archivo no existe
        Log::warning('Archivo local no encontrado: ' . $rutaLocal);
        return null;
    }
}

/**
 * Extraer public_id de una URL de Cloudinary
 */
private function extractPublicIdFromUrl($url)
{
    if (empty($url)) {
        return null;
    }
    
    // Patrón para URLs de Cloudinary
    // Ejemplo: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/public_id.jpg
    $pattern = '/\/upload\/(?:v\d+\/)?(.+?)(?:\.[a-zA-Z]{3,4})?$/';
    
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    
    return null;
}

/**
 * Método auxiliar para crear proyecto (mantenido para compatibilidad)
 */
public function createProject()
{
    // Obtener todos los estados disponibles
    $estados = Estado::all();

    // Obtener las zonas sociales
    $zonas = ZonaCatalogo::all();

    return view('admin.proyectos.crear', compact('zonas', 'estados'));
}
}