

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
        @php
            $proyectos = [
                ['id' => 1, 'img' => 'imagenes/myr72/Fondo72.png', 'titulo' => 'MYR72', 'estado' => 'En Construcción'],
                ['id' => 2, 'img' => 'imagenes/altos/Altos1.jpeg', 'titulo' => 'Alto de Rincón de Varsovia', 'estado' => 'Terminado'],
                ['id' => 3, 'img' => 'imagenes/rincon/Rincon3.jpeg', 'titulo' => 'Prados de Varsovia', 'estado' => 'Terminado'],
                ['id' => 4, 'img' => 'imagenes/rincon/Rincon1.jpeg', 'titulo' => 'Rincón de Varsovia', 'estado' => 'Terminado']
            ];
        @endphp

        @foreach ($proyectos as $p)
        <div class="col-md-3">
            <div class="card">
                <a href="{{ url('proyecto/' . $p['id']) }}" class="stretched-link"></a>
                <img src="{{ asset($p['img']) }}" class="card-img-top" alt="{{ $p['titulo'] }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $p['titulo'] }}</h5>
                    <span class="badge {{ $p['estado'] === 'En Construcción' ? 'bg-warning' : 'bg-success' }}">{{ $p['estado'] }}</span>
                    <p class="card-text">Construcción moderna en Ibagué.</p>
                </div>
            </div>
        </div>
        @endforeach
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
                    @foreach ($proyectos as $i => $p)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <a href="{{ url('proyecto/' . $p['id']) }}">
                            <img src="{{ asset($p['img']) }}" class="d-block w-100" alt="{{ $p['titulo'] }}">
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
                    @foreach ($proyectos as $i => $p)
                    <button type="button" data-bs-target="#carouselProyectos" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@if (session('status'))
    @push('scripts')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('status') }}',
            confirmButtonText: 'Aceptar',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    @endpush
@endif

@if (session('warning'))
    @push('scripts')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Acceso Denegado',
            text: '{{ session('warning') }}',
            confirmButtonText: 'Aceptar',
            timer: 5000,  // Duración de la alerta en milisegundos
            timerProgressBar: true
        });
    </script>
    @endpush
@endif


@endsection
