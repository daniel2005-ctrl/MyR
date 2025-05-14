@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
@endpush

@section('content')

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

<!-- Proyectos -->
<section id="proyectos" class="container my-5">
    <h2 class="text-center">Nuestros Proyectos</h2>
    <br><br>
    <div class="row text-center">
        @foreach ($proyectos as $proyecto)
        <div class="col-md-3 mb-4">
            <div class="card position-relative shadow-sm h-100">
                
                <!-- Bandera de estado con el nombre del estado -->
                <div class="estado-bandera {{ $proyecto->estado ? ($proyecto->estado->nombre_estado == 'Terminado' ? 'bg-success' : 'bg-warning') : 'bg-secondary' }}">
                    {{ $proyecto->estado->nombre_estado ?? 'Sin Estado' }} <!-- Mostrar el nombre del estado -->
                </div>

                <a href="{{ url('proyectos/' . $proyecto->id_pro) }}" class="stretched-link"></a>

                <img src="{{ asset($proyecto->imagenes_pro[0] ?? 'default-image.jpg') }}" class="card-img-top" alt="{{ $proyecto->nombre_pro }}">

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
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>


    <!-- Paginación -->
    <div class="d-flex justify-content-center my-4">
        {{ $proyectos->links() }}
    </div>
</section>

<!-- Contacto y Carrusel -->
<section id="contacto" class="container-fluid my-5">
    <div class="row">
        <div class="contenedor-formulario posicion-inferior-izquierda">
            <div class="formulario">
                <iframe src="{{ url('formulario') }}" width="70%" height="500px" style="border: none;"></iframe>
            </div>
        </div>

        <div class="carrusel-container">
            <div id="carouselProyectos" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner border border-warning border-5">
                    @foreach ($proyectos as $i => $proyecto)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <a href="{{ url('proyecto/' . $proyecto->id_pro) }}">
                            <img src="{{ asset($proyecto->imagenes_pro[0] ?? 'default-image.jpg') }}" class="d-block w-100" alt="{{ $proyecto->nombre_pro }}">
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
                timer: 5000,  // Duración de la alerta en milisegundos
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
