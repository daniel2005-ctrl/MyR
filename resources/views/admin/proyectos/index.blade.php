@extends('layouts.admin')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush


@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Hero -->
    <div class="logo-container"></div>

    <header class="hero d-flex align-items-center justify-content-center text-center text-white" 
        style="background: url('{{ asset('imagenes/Fondo 72.jpg') }}') no-repeat center center/cover;">
        <div>
            <h1>Construyendo un futuro firme</h1>
            <p>Honestos y confiables desde el inicio</p>
            <a href="#proyectos" class="btn btn-warning fw-bold">Ver proyectos</a>
        </div>
    </header>

    <br><br>

    {{-- Sección Proyectos --}}
    <section id="proyectos" class="py-12 bg-white text-center">
        <h2 class="text-3xl font-bold mb-6">Nuestros Proyectos</h2>
        <br><br>
        <div class="container">
            <div class="row justify-content-start">
        
                {{-- Tarjeta "Agregar nuevo proyecto" como la primera --}}
                @auth
                @if(auth()->user()->isAdmin())
                <div class="col-6 col-sm-4 col-md-3 col-lg-3 mb-4">
                    <a href="{{ route('admin.proyectos.crear') }}"
                       class="d-flex flex-column align-items-center justify-content-center text-center text-dark fw-bold"
                       style="background-color: #ff8c00; width: 100%; height: 220px; border-radius: 15px; text-decoration: none; box-shadow: 0 4px 8px rgba(0,0,0,0.2); transition: transform 0.3s ease, box-shadow 0.3s ease;"
                       onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.3)'"
                       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="black" class="mb-2" viewBox="0 0 16 16">
                            <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098zM7.25 4h1.5v3.25H12v1.5H8.75V12h-1.5V8.75H4v-1.5h3.25z"/>
                        </svg>
                        Agrega un nuevo<br>proyecto
                    </a>
                </div>
                @endif
                @endauth
        
                @foreach ($proyectos as $proyecto)
                <div class="col-6 col-sm-4 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100 text-start shadow-sm position-relative">
                
                        <!-- Imagen -->
                        <img src="{{ asset($proyecto->imagenes_pro[0] ?? 'imagenes/default-image.jpg') }}" 
                            class="card-img-top" 
                            style="height: 150px; object-fit: cover;" 
                            alt="{{ $proyecto->nombre_pro }}">
                
                        <div class="card-body p-2 d-flex flex-column justify-content-between h-100">
                            <!-- Contenido del proyecto -->
                            <div>
                                <span class="badge {{ $proyecto->terminado ? 'bg-success' : 'bg-warning' }} mb-1">
                                    {{ $proyecto->terminado ? 'Terminado' : 'En Construcción' }}
                                </span>
                                <h6 class="card-title mb-1" style="font-size: 15px">{{ $proyecto->nombre_pro }}</h6>
                                <p class="card-text" style="font-size: 13px">{{ Str::limit($proyecto->descripcion[0], 50) }}</p>
                                <p class="card-text">
                                    @foreach ($proyecto->zonasCatalogo as $zona)
                                        <span class="badge bg-info">{{ $zona->titulo }}</span>
                                    @endforeach
                                </p>
                            </div>
                
                            <!-- Acciones -->
                            <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                                <!-- Editar -->
                                <a href="{{ route('admin.proyectos.edit', $proyecto->id_pro) }}" class="text-warning position-relative z-1" title="Editar">
                                    <i class="fas fa-pencil-alt fa-lg"></i>
                                </a>
                
                                <!-- Eliminar -->
                                <form action="{{ route('admin.proyectos.destroy', $proyecto->id_pro) }}" method="POST" class="position-relative z-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 m-0 border-0 bg-transparent text-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>
                
                                <!-- Activar / Desactivar -->
                                <form action="{{ route('proyectos.toggle', $proyecto->id_pro) }}" method="POST" class="position-relative z-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn p-0 m-0 border-0 bg-transparent text-secondary" title="{{ $proyecto->terminado ? 'Ocultar' : 'Mostrar' }}">
                                        @if ($proyecto->terminado)
                                            <i class="fas fa-eye fa-lg"></i>
                                        @else
                                            <i class="fas fa-eye-slash fa-lg"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                
                        <!-- Enlace completo de la tarjeta, por debajo de los íconos -->
                        <a href="{{ url('proyectos/' . $proyecto->id_pro) }}" class="stretched-link" style="z-index: 0;"></a>
                    </div>
                </div>
                @endforeach
            

            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ELIMINAR PROYECTO
            document.querySelectorAll('form[action*="proyectos"][method="POST"]').forEach(form => {
                const deleteBtn = form.querySelector('button[type="submit"].text-danger');
                if (deleteBtn) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Eliminar proyecto?',
                            text: 'Esta acción no se puede deshacer.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: new FormData(form)
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error('Error de red');
                                    return response.json();
                                })
                                .then(data => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: data.message || 'Proyecto eliminado con éxito',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    form.closest('.col-6').remove(); // Eliminar visualmente la tarjeta
                                })
                                .catch(error => {
                                    Swal.fire('Error', 'No se pudo eliminar el proyecto.', 'error');
                                    console.error(error);
                                });
                            }
                        });
                    });
                }
            });
        
            // TOGGLE VISIBILIDAD (mostrar/ocultar proyecto)
            document.querySelectorAll('form[action*="toggle"]').forEach(form => {
                const toggleBtn = form.querySelector('button[type="submit"]');
                if (toggleBtn) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: new FormData(form)
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Estado actualizado',
                                text: data.message,
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => window.location.reload());
                        })
                        .catch(error => {
                            Swal.fire('Error', 'No se pudo cambiar el estado.', 'error');
                            console.error(error);
                        });
                    });
                }
            });
        });
        </script>
        
    @push('scripts')

@endpush


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
