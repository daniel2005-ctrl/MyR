@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
@endpush

@section('content')

<header class="text-center py-3 bg-white">
    <h1>{{ $proyecto['titulo'] }}</h1>
</header>

{{-- Imágenes del header --}}
<div class="container mb-4">
    <div class="row d-flex justify-content-center">
        @foreach ($proyecto['imagenesHeader'] as $img)
            <div class="col-md-5 mb-2 mb-md-0">
                <img src="{{ asset($img) }}" class="img-fluid rounded-0 w-100" alt="Imagen del proyecto"
                    style="height: 400px; object-fit: cover; border-radius: 10px;">
            </div>
        @endforeach
    </div>
    
</div>

{{-- Descripción --}}
<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold">Descripción</h2>
            @foreach ($proyecto['descripcion'] as $parrafo)
                <p class="text-muted" style="font-size: 20px;">{{ $parrafo }}</p>
            @endforeach
        </div>

        <div class="col-md-4">
            <div class="border rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('imagenes/otros/home.png') }}" alt="precio" width="30" class="me-2">
                    <div>
                        <span>Desde {{ $proyecto['precio']['min'] }}</span><br>
                        @if($proyecto['precio']['max'])
                        <span>Hasta {{ $proyecto['precio']['max'] }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('imagenes/otros/metros.png') }}" alt="área" width="30" class="me-2">
                    <div>
                        <span>Área: {{ $proyecto['area']['min'] }}</span><br>
                        @if($proyecto['area']['max'])
                        <span>Hasta {{ $proyecto['area']['max'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zonas Sociales --}}
@if (!empty($proyecto['zonas']))
<section id="zonas" class="my-5">
    <div class="container">
        <h4 class="text-center mb-4" style="background-color: orange; padding: 5px 20px; color: #fff; border-radius: 5px;">
            Zonas Sociales
        </h4>
        <div class="row justify-content-center text-center align-items-center">
            @foreach ($proyecto['zonas'] as $zona)
                <div class="col-auto mb-3 px-4">
                    <img src="{{ asset($zona['icono']) }}" alt="{{ $zona['titulo'] }}" style="width: 40px;">
                    <p class="mt-2">{{ $zona['titulo'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- Apartamentos --}}
@if (!empty($proyecto['apartamentos']))
<div class="container mb-5">
    <div class="row">
        <div class="col-12 text-center mb-3">
            <h4 style="background: #FF9000; padding: 5px 15px; border-radius: 5px; color: white;">
                Tipos de apartamento
            </h4>
        </div>
        @foreach ($proyecto['apartamentos'] as $img)
        <div class="col-12 col-md-6 mb-4 d-flex align-items-center justify-content-center">
            <img src="{{ asset($img) }}" class="img-fluid" style="max-height: 300px;" alt="Apartamento">
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Ubicación --}}
<div class="ubicacion text-center" id="ubicacion">
    <h2>Ubicación</h2>
    <p>{{ $proyecto['direccion'] }}</p>
    <div style="max-width: 100%; overflow: hidden;">
        <iframe 
            src="{{ $proyecto['mapa'] }}" 
            width="80%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
            class="rounded shadow">
        </iframe>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center my-3 px-4">
   <!-- Botón flotante izquierdo (Inicio) - visible en todas las resoluciones -->
<div style="position: fixed; top: 20%; left: 10px; transform: translateY(-50%); z-index: 999;">
    <a href="{{ url('/') }}" class="btn btn-light shadow-sm d-flex align-items-center mb-2" style="border-radius: 10px; color: orange; font-weight: bold;">
        <img src="{{ asset('imagenes/otros/home.png') }}" alt="Inicio" style="height: 20px; margin-right: 8px;">
        Inicio
    </a>
</div>

<!-- Botón flotante derecho (Cotiza Ya) - visible en todas las resoluciones -->
<div style="position: fixed; top: 20%; right: 10px; transform: translateY(-50%); z-index: 999;">
    <a href="{{ url('cotizacion') }}" class="btn btn-light shadow-sm d-flex align-items-center" style="border-radius: 10px; color: orange; font-weight: bold;">
        <img src="{{ asset('imagenes/otros/cotiza.jpg') }}" alt="Cotiza Ya" style="height: 20px; margin-right: 8px;">
        <span>Cotiza Ya</span>
    </a>
</div>

<!-- Versión móvil abajo (opcional, si prefieres que se vean abajo en móviles) -->
{{-- 
<div class="d-flex d-md-none justify-content-between p-2 px-4" style="position: fixed; bottom: 0; left: 0; right: 0; background-color: white; z-index: 999; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
    <a href="{{ url('/') }}" class="btn btn-light d-flex align-items-center" style="border-radius: 10px; color: orange; font-weight: bold;">
        <img src="{{ asset('imagenes/otros/home.png') }}" alt="Inicio" style="height: 20px; margin-right: 5px;">
        Inicio
    </a>

    <a href="{{ url('cotizacion') }}" class="btn btn-light d-flex align-items-center" style="border-radius: 10px; color: orange; font-weight: bold;">
        <img src="{{ asset('imagenes/otros/cotiza.jpg') }}" alt="Cotiza Ya" style="height: 20px; margin-right: 5px;">
        Cotiza
    </a>
</div>
--}}

    
</div>


@endsection
