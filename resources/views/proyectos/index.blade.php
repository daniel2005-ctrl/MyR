@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
<style>
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
        pointer-events: none;
    }
    .hero > * {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/index.js') }}"></script>
@endpush

@section('content')

<!-- Hero -->
<div class="logo-container"></div>

@php
    $imagenFondo = \App\Models\Configuracion::obtener('imagen_fondo_index', 'imagenes/Fondo 72.jpg');
    // Detectar si es URL de Cloudinary o ruta local
    $urlFondo = (str_contains($imagenFondo, 'cloudinary.com') || str_contains($imagenFondo, 'res.cloudinary.com')) 
        ? $imagenFondo 
        : asset($imagenFondo);
@endphp

<header class="hero d-flex align-items-center justify-content-center text-center text-white position-relative" 
    style="background: url('{{ $urlFondo }}') no-repeat center center/cover;">
    <div>
        <h1>Construyendo un futuro firme</h1>
        <p>Honestos y confiables desde el inicio</p>
        <a href="#proyectos" class="btn btn-warning fw-bold">Ver proyectos</a>
    </div>
</header>

<!-- Proyectos -->
<section id="proyectos" class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0">Nuestros Proyectos</h2>
        <button id="toggleFilters" class="btn btn-filter-toggle">
            <i class="fas fa-filter"></i> Filtros
        </button>
    </div>
    
    <!-- Panel de Filtros Colapsible -->
    <div id="filterPanel" class="filter-panel">
        <div class="filter-section">
            <h3 class="filter-title">🔍 Filtrar Proyectos</h3>
            
            <form method="GET" action="{{ route('proyectos.index') }}" id="filterForm">
                <div class="row">
                    <!-- Filtro por Tipo de Vivienda -->
                    <div class="col-md-4 filter-group">
                        <label class="filter-label">Tipo de Vivienda</label>
                        <select name="tipo_pro" class="form-select">
                            <option value="">Todos los tipos</option>
                            <option value="VIS" {{ request('tipo_pro') == 'VIS' ? 'selected' : '' }}>VIS</option>
                            <option value="No VIS" {{ request('tipo_pro') == 'No VIS' ? 'selected' : '' }}>No VIS</option>
                            <option value="VIP" {{ request('tipo_pro') == 'VIP' ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>
                    
                    <!-- Filtro por Estado del Proyecto -->
                    <div class="col-md-4 filter-group">
                        <label class="filter-label">Estado del Proyecto</label>
                        <select name="estado_proyecto" class="form-select">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ request('estado_proyecto') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Filtro por Zonas Sociales (Dropdown con Checkboxes) -->
                    <div class="col-md-4 filter-group">
                        <label class="filter-label">Zonas Sociales</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="zonasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="zonasSelected">
                                    @if(request('zonas_sociales'))
                                        {{ count(request('zonas_sociales')) }} zona(s) seleccionada(s)
                                    @else
                                        Seleccionar zonas sociales
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="zonasDropdown">
                                @foreach($zonasCatalogo as $zona)
                                    <li>
                                        <div class="form-check dropdown-item-check">
                                            <input class="form-check-input zona-checkbox" type="checkbox" 
                                                   name="zonas_sociales[]" value="{{ $zona->id }}" 
                                                   id="zona_{{ $zona->id }}"
                                                   {{ in_array($zona->id, request('zonas_sociales', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="zona_{{ $zona->id }}">
                                                {{ $zona->titulo }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                                @if($zonasCatalogo->count() == 0)
                                    <li><span class="dropdown-item text-muted">No hay zonas disponibles</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-filter me-2">
                            <i class="fas fa-filter"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('proyectos.index') }}" class="btn btn-clear">
                            <i class="fas fa-times"></i> Limpiar Filtros
                        </a>
                    </div>
                </div>
                
                <!-- Mostrar filtros activos -->
                @if(request()->hasAny(['tipo_pro', 'estado_proyecto', 'zonas_sociales']))
                    <div class="active-filters">
                        <small class="text-muted">Filtros activos:</small>
                        @if(request('tipo_pro'))
                            <span class="filter-tag">Tipo: {{ request('tipo_pro') }}</span>
                        @endif
                        @if(request('estado_proyecto'))
                            @php
                                $estadoSeleccionado = $estados->where('id_estado', request('estado_proyecto'))->first();
                            @endphp
                            <span class="filter-tag">Estado: {{ $estadoSeleccionado->nombre_estado ?? 'N/A' }}</span>
                        @endif
                        @if(request('zonas_sociales'))
                            @php
                                $zonasSeleccionadas = $zonasCatalogo->whereIn('id', request('zonas_sociales'));
                            @endphp
                            @foreach($zonasSeleccionadas as $zona)
                                <span class="filter-tag">Zona: {{ $zona->titulo }}</span>
                            @endforeach
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    @if($proyectos->count() > 0)
        <p class="text-center text-muted mb-4">Mostrando {{ $proyectos->count() }} de {{ $proyectos->total() }} proyectos</p>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> No se encontraron proyectos con los filtros seleccionados.
        </div>
    @endif
    
    <br><br>
    <div class="row text-center">
        @foreach ($proyectos as $proyecto)
        <div class="col-md-3 mb-4">
            <div class="card position-relative shadow-sm h-100">
                
                <!-- Bandera de estado con el nombre del estado -->
                <div class="estado-bandera {{ $proyecto->estado ? ($proyecto->estado->nombre_estado == 'Terminado' ? 'bg-success' : 'bg-warning') : 'bg-secondary' }}">
                    {{ $proyecto->estado->nombre_estado ?? 'Sin Estado' }}
                </div>
                
                <!-- Etiqueta de tipo de vivienda -->
                <div class="proyecto-tipo-badge">
                    <span class="badge 
                        @if($proyecto->tipo_pro == 'VIS') bg-primary
                        @elseif($proyecto->tipo_pro == 'VIP') bg-success
                        @else bg-secondary
                        @endif">
                        @if($proyecto->tipo_pro == 'VIS')
                            VIS
                        @elseif($proyecto->tipo_pro == 'No VIS')
                            NO VIS
                        @elseif($proyecto->tipo_pro == 'VIP')
                            VIP
                        @else
                            {{ $proyecto->tipo_pro }}
                        @endif
                    </span>
                </div>

                <a href="{{ route('proyectos.show', $proyecto->slug) }}" class="stretched-link"></a>

                @if($proyecto->imagenes_header)
                    @php
                        // Detectar si es URL de Cloudinary o ruta local
                        $imagenUrl = (str_contains($proyecto->imagenes_header, 'cloudinary.com') || str_contains($proyecto->imagenes_header, 'res.cloudinary.com')) 
                            ? $proyecto->imagenes_header 
                            : asset('imagenes/' . $proyecto->imagenes_header);
                    @endphp
                    <img src="{{ $imagenUrl }}" class="card-img-top" alt="{{ $proyecto->nombre_pro }}">
                @else
                    <img src="{{ asset('default-image.jpg') }}" class="card-img-top" alt="{{ $proyecto->nombre_pro }}">
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $proyecto->nombre_pro }}</h5>
                    <p class="card-text text-muted">
                        Construcción moderna en {{ $proyecto->ubicacion_pro }}.
                    </p>

                    @if ($proyecto->zonasCatalogo->count())
                        <div class="text-start">
                            <strong>Zonas sociales:</strong>
                            <ul class="list-unstyled small mb-0">
                                @foreach ($proyecto->zonasCatalogo as $zona)
                                    <li>🔹 {{ $zona->titulo }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-start">
                            <small class="sin-zonas-sociales">Sin zonas sociales</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Paginación -->
@if($proyectos->hasPages())
    <div class="d-flex justify-content-center my-4">
        {{ $proyectos->links() }}
    </div>
@endif

<!-- Contacto y Carrusel -->
<!-- Replace the existing contacto section with this responsive version -->
<section id="contacto" class="container-fluid my-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Formulario de contacto -->
            <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                <div class="contenedor-formulario">
                    <div class="formulario">
                        <iframe src="{{ url('formulario') }}" class="w-100"></iframe>
                    </div>
                </div>
            </div>
            
            <!-- Carrusel -->
            <div class="col-lg-7 col-md-6">
                <div class="carrusel-container">
                    <div id="carouselProyectos" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner border border-warning border-5">
                            @foreach ($proyectos as $i => $proyecto)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <a href="{{ route('proyectos.show', $proyecto->slug) }}" class="stretched-link"></a>
                                    @php
                                        $imagenCarrusel = $proyecto->imagenes_header ?? 'default-image.jpg';
                                        // Detectar si es URL de Cloudinary o ruta local
                                        $urlCarrusel = (str_contains($imagenCarrusel, 'cloudinary.com') || str_contains($imagenCarrusel, 'res.cloudinary.com')) 
                                            ? $imagenCarrusel 
                                            : asset('imagenes/' . $imagenCarrusel);
                                    @endphp
                                    <img src="{{ $urlCarrusel }}" class="card-img-top" alt="{{ $proyecto->nombre_pro }}">
                                </a>
                            </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProyectos" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProyectos" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>

                        <div class="carousel-indicators">
                            @foreach ($proyectos as $i => $proyecto)
                            <button type="button" data-bs-target="#carouselProyectos" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




@if (session('warning'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Acceso Denegado',
                text: @json(session('warning')),
                confirmButtonText: 'Aceptar',
                timer: 5000,
                timerProgressBar: true
            });
        });
    </script>
    @endpush
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const mensaje = localStorage.getItem("loginSuccess");
  const usuario = localStorage.getItem("usuario");

  if (mensaje) {
    Swal.fire({
      icon: "success",
      title: "¡Bienvenido!",
      text: `${mensaje} Hola, ${usuario}`,
      confirmButtonColor: "#ff6600"
    });

    localStorage.removeItem("loginSuccess");
  }
});
</script>
@endpush

@endsection