@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/crear.css') }}">
@endpush

@section('title', 'Editar Proyecto')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold">Editar Proyecto: {{ $proyecto->nombre_pro }}</h2>

    <form action="{{ route('admin.proyectos.update', $proyecto->slug) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Estado del proyecto -->
        <div class="mb-4">
            <label for="estado_proyecto" class="form-label fw-bold">Estado del proyecto</label>
            <select name="id_estado" id="estado_proyecto" class="form-select" required>
                <option value="">Seleccione una opción</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado->id_estado }}" {{ $proyecto->id_estado == $estado->id_estado ? 'selected' : '' }}>
                        {{ $estado->nombre_estado }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Título del proyecto -->
        <div class="mb-4">
            <label class="form-label fw-bold">Título del proyecto</label>
            <input type="text" name="nombre_pro" class="form-control" value="{{ old('nombre_pro', $proyecto->nombre_pro) }}" required>
        </div>

        <!-- Imágenes del proyecto -->
        <div class="mb-4">
            <label class="form-label fw-bold">Imágenes del proyecto</label>
            @php
                $imagenes = is_string($proyecto->imagenes_pro) ? json_decode($proyecto->imagenes_pro, true) : $proyecto->imagenes_pro;
            @endphp
            @if (!empty($imagenes) && is_array($imagenes))
                <div class="mb-2">
                    @foreach ($imagenes as $img)
                        <div class="d-inline-block me-2 mb-2">
                            @php
                                // Usar directamente la URL de Cloudinary si es una URL completa, sino usar asset()
                                $imagePath = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : asset($img);
                            @endphp
                            <img src="{{ $imagePath }}" class="img-thumbnail" width="100" 
                                 onerror="this.src='{{ asset('imagenes/placeholder.jpg') }}'; this.onerror=null;">
                            <div class="text-center mt-1">
                                <label>
                                    <input type="checkbox" name="imagenes_a_eliminar[]" value="{{ $img }}">
                                    <small>Eliminar</small>
                                </label>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">{{ basename($img) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No hay imágenes cargadas para este proyecto.</p>
            @endif
            <input type="file" name="imagenes_pro[]" class="form-control" accept="image/*" multiple>
        </div>

        <!-- Imagen de portada -->
        <div class="mb-4">
            <label class="form-label fw-bold">Imagen de portada</label>
            @if($proyecto->imagenes_header)
                <div class="mb-2">
                    @php
                        // Usar directamente la URL de Cloudinary si es una URL completa, sino usar asset()
                        $headerPath = (str_starts_with($proyecto->imagenes_header, 'http://') || str_starts_with($proyecto->imagenes_header, 'https://')) ? $proyecto->imagenes_header : asset($proyecto->imagenes_header);
                    @endphp
                    <img src="{{ $headerPath }}" class="img-thumbnail" width="150">
                    <div class="mt-1">
                        <label>
                            <input type="checkbox" name="eliminar_header" value="1">
                            <small>Eliminar imagen de portada</small>
                        </label>
                    </div>
                </div>
            @endif
            <input type="file" name="imagenes_header" class="form-control" accept="image/*">
            <small class="form-text text-muted">Esta imagen se mostrará como portada principal del proyecto en la página de inicio.</small>
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label class="form-label fw-bold">Descripción</label>
            @php
                // Decodificar la descripción si está en formato JSON
                $descripcionArray = is_string($proyecto->descripcion) ? json_decode($proyecto->descripcion, true) : $proyecto->descripcion;
                $descripcion = is_array($descripcionArray) ? implode("\n", $descripcionArray) : $descripcionArray;
            @endphp
            <textarea name="descripcion[]" class="form-control" rows="3" required>{{ old('descripcion.0', $descripcion) }}</textarea>
            <small class="form-text text-muted">Describe las características principales del proyecto.</small>
        </div>

        <!-- Zonas Sociales -->
        <div class="mb-4 border-top pt-3">
            <h6 class="text-uppercase fw-bold text-muted">Zonas Sociales</h6>
            <p class="text-muted">Selecciona las zonas sociales disponibles para este proyecto</p>
            <div class="row g-3">
                @foreach ($zonasCatalogo as $zona)
                    <div class="col-md-4 col-sm-6">
                        <label for="zona_{{ $zona->id }}" class="card h-100 p-3 border shadow-sm rounded-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($zona->icono)
                                    <img src="{{ asset($zona->icono) }}" alt="{{ $zona->titulo }}" width="24" height="24">
                                @else
                                    <i class="bi bi-building text-secondary fs-5"></i>
                                @endif
                                <span class="fw-semibold text-dark">{{ $zona->titulo }}</span>
                                <input type="checkbox" class="form-check-input ms-auto" name="zonas_sociales[]" value="{{ $zona->id }}" id="zona_{{ $zona->id }}" {{ $proyecto->zonasCatalogo->contains($zona->id) ? 'checked' : '' }}>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Precio -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Precio apto 1</label>
                @php
                    $precioData = is_array($proyecto->precio) ? $proyecto->precio : [];
                @endphp
                <input type="number" name="precio_min" step="0.01" class="form-control" value="{{ old('precio_min', $precioData['min'] ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Precio apto 2</label>
                <input type="number" name="precio_max" step="0.01" class="form-control" value="{{ old('precio_max', $precioData['max'] ?? '') }}">
            </div>
        </div>

        <!-- Área -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Área apto 1 (m²)</label>
                @php
                    $areaData = is_array($proyecto->area) ? $proyecto->area : [];
                @endphp
                <input type="number" name="area_min" step="0.01" class="form-control" value="{{ old('area_min', $areaData['min'] ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Área apto 2 (m²)</label>
                <input type="number" name="area_max" step="0.01" class="form-control" value="{{ old('area_max', $areaData['max'] ?? '') }}">
            </div>
        </div>

        <!-- Parqueadero -->
        <div class="mb-4">
            <label class="form-label fw-bold">Precio del parqueadero (SMMLV)</label>
            <input type="number" name="parqueadero" step="0.01" class="form-control" value="{{ old('parqueadero', $proyecto->parqueadero) }}" placeholder="Ej: 3.5">
            <small class="form-text text-muted">Precio en Salarios Mínimos Mensuales Legales Vigentes. Dejar vacío si no aplica.</small>
        </div>

        <!-- Tipos de apartamento -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipos de apartamento</label>
            @php
                $apartamentos = is_string($proyecto->apartamentos) ? json_decode($proyecto->apartamentos, true) : $proyecto->apartamentos;
            @endphp
            @if (!empty($apartamentos) && is_array($apartamentos))
                <div class="mb-2">
                    @foreach ($apartamentos as $apt)
                        <div class="d-inline-block me-2 mb-2">
                            @php
                                // Usar directamente la URL de Cloudinary si es una URL completa, sino usar asset()
                                $apartamentoPath = (str_starts_with($apt, 'http://') || str_starts_with($apt, 'https://')) ? $apt : asset($apt);
                            @endphp
                            <img src="{{ $apartamentoPath }}" class="img-thumbnail" width="100" 
                                 onerror="this.src='{{ asset('imagenes/placeholder.jpg') }}'; this.onerror=null;">
                            <div class="text-center mt-1">
                                <label>
                                    <input type="checkbox" name="apartamentos_a_eliminar[]" value="{{ $apt }}">
                                    <small>Eliminar</small>
                                </label>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">{{ basename($apt) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No hay tipos de apartamento cargados para este proyecto.</p>
            @endif
            <input type="file" name="apartamentos[]" class="form-control" accept="image/*" multiple>
        </div>

        <!-- Videos (solo si existen) -->
        @php
            $videos = is_string($proyecto->videos_pro) ? json_decode($proyecto->videos_pro, true) : $proyecto->videos_pro;
        @endphp
        @if (!empty($videos) && is_array($videos))
        <div class="mb-4">
            <label class="form-label fw-bold">Videos del Proyecto</label>
            <div id="video-fields">
                @foreach ($videos as $index => $video)
                    <div class="input-group mb-2">
                        <input type="url" name="videos_pro[]" class="form-control" value="{{ $video }}" placeholder="URL del video">
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarVideo(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarCampoVideo()">
                <i class="fas fa-plus"></i> Agregar Video
            </button>
        </div>
        @endif

        <!-- Ubicación -->
        <div class="mb-4">
            <label class="form-label fw-bold">Ubicación</label>
            <input type="text" name="ubicacion_pro" class="form-control" value="{{ old('ubicacion_pro', $proyecto->ubicacion_pro) }}" placeholder="Ej: Calle 123 #45-67">
            <div class="mt-3">
                <iframe src="https://www.google.com/maps?q=Bogotá&output=embed" width="100%" height="300" style="border:0;"></iframe>
            </div>
        </div>

        <!-- Tipo de vivienda (VIS, No VIS, VIP) -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Vivienda</label>
            <select name="tipo_pro" class="form-select" required>
                <option value="">Seleccione una opción</option>
                <option value="VIS" {{ $proyecto->tipo_pro == 'VIS' ? 'selected' : '' }}>Vivienda de Interés Social (VIS)</option>
                <option value="No VIS" {{ $proyecto->tipo_pro == 'No VIS' ? 'selected' : '' }}>No VIS</option>
                <option value="VIP" {{ $proyecto->tipo_pro == 'VIP' ? 'selected' : '' }}>Vivienda de Interés Prioritario (VIP)</option>
            </select>
        </div>

        <!-- Tipo de permiso (fijo a 1, oculto) -->
        <input type="hidden" name="id_tipo_permiso" value="1">

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
    function agregarCampoVideo() {
        const container = document.getElementById('video-fields');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="url" name="videos_pro[]" class="form-control" placeholder="URL del video">
            <button type="button" class="btn btn-outline-danger" onclick="eliminarVideo(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function eliminarVideo(button) {
        button.parentElement.remove();
    }
</script>
@endsection
