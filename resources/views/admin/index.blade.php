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

{{-- Hero Banner --}}
<section class="bg-cover bg-center h-[500px] flex items-center justify-center text-white relative" style="background-image: url('/images/proyecto72.jpg')">
    <div class="bg-black/50 absolute inset-0"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-4xl font-bold">Construyendo un futuro firme</h1>
        <p class="mt-2">Honestos y confiables desde el inicio</p>
        <a href="#proyectos" class="mt-4 inline-block bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition">Ver proyectos</a>
    </div>
</section>

{{-- Sección Proyectos --}}
<section id="proyectos" class="py-12 bg-white text-center">
    <h2 class="text-3xl font-bold mb-6">Nuestros Proyectos</h2>

    @auth
        @if(auth()->user()->isAdmin())
            <div class="mb-6">
                 <a href="{{ route('proyectos.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">
                    ➕ Agrega un nuevo proyecto
                </a> 
            </div>
        @endif
    @endauth

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-4 max-w-6xl mx-auto">
        @foreach($proyectos as $proyecto)
            <div class="bg-white shadow-md rounded overflow-hidden">
                <img src="{{ asset('storage/' . $proyecto->imagen) }}" alt="{{ $proyecto->nombre }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-semibold">{{ $proyecto->nombre }}</h3>
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




