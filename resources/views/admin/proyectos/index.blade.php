@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

@section('content')

{{-- Alerta de éxito con auto-cierre --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoCloseAlert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        // Auto-cerrar la alerta después de 5 segundos (5000ms)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alert = document.getElementById('autoCloseAlert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    </script>
@endif

{{-- SweetAlert automático --}}
@if(session('swal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
            icon: '{{ session('swal.icon') }}',
            confirmButtonText: 'OK',
            timer: 5000,  // Auto-cierre después de 5 segundos
            timerProgressBar: true
        });
    });
</script>
@endif

{{-- Botón flotante para panel de admin --}}
@auth
@if(Auth::user()->tipo_permiso_id == 1)
<a href="{{ route('admin.panel_administrador.panel2') }}" class="btn-float-admin" title="Ir al Panel de Administrador">
    <i class="bi bi-speedometer2"></i>
</a>
@endif
@endauth

{{-- Hero principal --}}
<div class="logo-container"></div>

@php
    $imagenFondo = \App\Models\Configuracion::obtener('imagen_fondo_index', 'imagenes/Fondo 72.jpg');
@endphp

<header class="hero d-flex align-items-center justify-content-center text-center text-white position-relative" 
    style="background: url('{{ 
        (str_contains($imagenFondo, 'cloudinary.com') || str_contains($imagenFondo, 'res.cloudinary.com') || str_starts_with($imagenFondo, 'http://') || str_starts_with($imagenFondo, 'https://')) 
            ? $imagenFondo 
            : asset($imagenFondo) 
    }}') no-repeat center center/cover;">
    
    
    {{-- Imagen destacada solo para admins - Posicionado en la esquina superior derecha --}}
    @auth
    @if(Auth::user()->tipo_permiso_id == 1)
    <div class="position-absolute top-0 end-0 m-3">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-image me-1"></i> Imagen Destacada
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="{{ route('admin.mostrar-mas-visto') }}"><i class="fas fa-chart-line me-2"></i>Proyecto más visitado</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.mostrar-selector-proyecto') }}"><i class="fas fa-hand-pointer me-2"></i>Seleccionar proyecto</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.mostrar-subir-imagen') }}"><i class="fas fa-upload me-2"></i>Subir imagen personalizada</a></li>
            </ul>
        </div>
        <div id="imagen-destacada" class="mt-2">
            @if(session('vista_imagen_destacada'))
                @include('admin.proyectos.partials.' . session('vista_imagen_destacada'))
            @endif
        </div>
    </div>
    @endif
    @endauth
    
    <div>
        <h1>Construyendo un futuro firme</h1>
        <p>Honestos y confiables desde el inicio</p>
        <a href="#proyectos" class="btn btn-warning fw-bold">Ver proyectos</a>
    </div>
</header>

<br><br>

{{-- Sección Proyectos --}}
<section id="proyectos" class="py-5 bg-white">
    {{-- Título centrado --}}
    <div class="text-center mb-5">
        <h2 class="display-4 fw-bold mb-3" style="color: #ff6a00;">Administración de Proyectos</h2>
        <div class="d-flex justify-content-center">
            <span class="badge bg-info fs-6 px-3 py-2 rounded-pill">Mostrando todos los proyectos</span>
        </div>
    </div>
    
    <div class="container-fluid px-4">
        <div class="row g-4 justify-content-start">

            {{-- Agregar proyecto (solo admins) --}}
            @auth
            @if(auth()->user()->isAdmin())
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card h-100 shadow-sm border-2" style="border-color: #ff6a00 !important;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-4">
                        <a href="{{ route('admin.proyectos.crear') }}" class="text-decoration-none" style="color: #ff6a00;">
                            <div class="mb-3">
                                <i class="fas fa-plus-circle fa-4x" style="color: #ff6a00;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Agregar Proyecto</h5>
                            <p class="card-text small text-muted">Crear nuevo proyecto</p>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @endauth

            {{-- Listado de proyectos --}}
            @foreach($proyectos as $proyecto)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 proyecto-card {{ $proyecto->terminado == 0 ? 'proyecto-inactivo' : '' }}" 
                 data-terminado="{{ $proyecto->terminado }}">
                <div class="card h-100 shadow-sm border-0 position-relative proyecto-item" 
                     style="transition: all 0.3s ease;">

                    {{-- Estado (badge) --}}
                    @auth
                    @if(auth()->user()->isAdmin())
                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                        <span class="badge {{ $proyecto->terminado ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                            {{ $proyecto->terminado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    @endif
                    @endauth

                    {{-- Imagen --}}
                    <div class="card-img-container" style="height: 200px; overflow: hidden;">
                        @if($proyecto->imagenes_header)
                            <img src="{{ 
                                (str_contains($proyecto->imagenes_header, 'cloudinary.com') || str_contains($proyecto->imagenes_header, 'res.cloudinary.com') || str_starts_with($proyecto->imagenes_header, 'http://') || str_starts_with($proyecto->imagenes_header, 'https://')) 
                                    ? $proyecto->imagenes_header 
                                    : asset('imagenes/' . $proyecto->imagenes_header) 
                            }}" 
                                 class="card-img-top w-100 h-100" 
                                 alt="{{ $proyecto->nombre_pro }}" 
                                 style="object-fit: cover; transition: transform 0.3s ease;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Contenido del proyecto --}}
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem;">{{ $proyecto->nombre_pro }}</h6>
                        <p class="card-text text-muted flex-grow-1" style="font-size: 0.8rem; line-height: 1.3;">
                            @php
                                // Decodificar la descripción si está en formato JSON
                                $descripcionArray = is_string($proyecto->descripcion) ? json_decode($proyecto->descripcion, true) : $proyecto->descripcion;
                                $descripcionTexto = is_array($descripcionArray) ? implode(' ', $descripcionArray) : $descripcionArray;
                            @endphp
                            {{ Str::limit($descripcionTexto, 80) }}
                        </p>

                        {{-- Botones mejorados --}}
                        @auth
                        @if(auth()->user()->isAdmin())
                        <div class="admin-buttons mt-auto" onclick="event.stopPropagation();">
                            <div class="d-grid gap-1">
                                {{-- Botón de editar --}}
                                <a href="{{ route('admin.proyectos.edit', $proyecto->slug) }}" 
                                   class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" 
                                   title="Editar proyecto" style="font-size: 0.75rem;">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                
                                {{-- Fila de botones de acción --}}
                                <div class="btn-group w-100" role="group">
                                    {{-- Toggle estado --}}
                                    <form method="POST" action="{{ route('admin.proyectos.toggle', $proyecto->slug) }}" class="toggle-form flex-fill">
                                        @csrf
                                        <button type="button" class="btn {{ $proyecto->terminado ? 'btn-secondary' : 'btn-success' }} btn-sm w-100 toggle-btn"
                                        title="{{ $proyecto->terminado ? 'Desactivar' : 'Activar' }} proyecto"
                                        data-action="{{ $proyecto->terminado ? 'desactivar' : 'activar' }}"
                                        data-proyecto="{{ $proyecto->nombre_pro }}">
                                    <i class="fas {{ $proyecto->terminado ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                                    </form>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('admin.proyectos.destroy', $proyecto->slug) }}" class="delete-form flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm w-100 delete-btn" 
                                                title="Eliminar proyecto"
                                                data-proyecto="{{ $proyecto->nombre_pro }}" style="font-size: 0.7rem;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="mt-auto">
                            <a href="{{ route('proyectos.show', $proyecto->slug) }}" 
                               class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center" 
                               onclick="event.stopPropagation();" style="font-size: 0.75rem;">
                                <i class="fas fa-eye me-1"></i>Ver más
                            </a>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Pasar datos de proyectos al JavaScript --}}
@auth
@if(Auth::user()->tipo_permiso_id == 1)
<script>
    window.proyectosData = @json($proyectosData ?? []);
</script>
@endif
@endauth

<script src="{{ asset('js/admin/proyectos-index.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para cerrar dropdown automáticamente
    function cerrarDropdown() {
        const dropdownButton = document.querySelector('#dropdownMenuButton');
        if (dropdownButton) {
            // Método más directo para cerrar el dropdown
            const dropdown = new bootstrap.Dropdown(dropdownButton);
            dropdown.hide();
        }
    }

    // Cerrar dropdown cuando se hace clic en cualquier opción del menú imagen destacada
    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Agregar clase visual de procesamiento
            this.classList.add('processing');
            
            // Cerrar dropdown inmediatamente
            setTimeout(() => {
                cerrarDropdown();
            }, 50);
        });
    });

    // Cerrar dropdown cuando se hace clic fuera de él
    document.addEventListener('click', function(e) {
        const dropdownContainer = document.querySelector('.dropdown');
        if (dropdownContainer && !dropdownContainer.contains(e.target)) {
            cerrarDropdown();
        }
    });

    // Cerrar dropdown con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarDropdown();
        }
    });

    // Toggle estado
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.toggle-form');
            const action = this.dataset.action;
            const proyecto = this.dataset.proyecto;

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Quieres ${action} el proyecto "${proyecto}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'activar' ? '#28a745' : '#6c757d',
                cancelButtonColor: '#d33',
                confirmButtonText: `Sí, ${action}`,
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Confirmar eliminación
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            const proyecto = this.dataset.proyecto;

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Quieres eliminar el proyecto "${proyecto}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Detectar cuando la página se recarga (después de una acción)
    window.addEventListener('pageshow', function(event) {
        // Si la página se carga desde el cache del navegador, cerrar cualquier dropdown abierto
        if (event.persisted) {
            setTimeout(cerrarDropdown, 100);
        }
    });
});
</script>
@endpush
