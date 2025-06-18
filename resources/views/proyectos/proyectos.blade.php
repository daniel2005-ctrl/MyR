@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilo 2.css') }}">
@endpush

@section('content')

<header class="text-center py-3 bg-white">
    <h1>{{ $proyecto->titulo }}</h1>
</header>

{{-- Imágenes del Proyecto --}}
@php
    $imagenes_pro = is_array($proyecto->imagenes_pro) ? $proyecto->imagenes_pro : json_decode($proyecto->imagenes_pro, true);
@endphp
@if(is_array($imagenes_pro) && !empty($imagenes_pro))
<div class="container mb-4">
    <div class="row d-flex justify-content-center">
        @foreach ($imagenes_pro as $img)
            <div class="col-md-4 mb-3">
                <img src="{{ asset($img) }}" class="img-fluid imagenes-proyecto" alt="Imagen del proyecto" />
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Descripción --}}
@php
    $descripcion = is_array($proyecto->descripcion) ? $proyecto->descripcion : json_decode($proyecto->descripcion, true);
@endphp
<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold">Descripción</h2>
            @if(is_array($descripcion))
                @foreach ($descripcion as $parrafo)
                    <p class="text-muted" style="font-size: 20px;">{{ $parrafo }}</p>
                @endforeach
            @else
                <p class="text-muted" style="font-size: 20px;">{{ $proyecto->descripcion }}</p>
            @endif
        </div>

        {{-- Precio y Área --}}
        <div class="col-md-4">
            <div class="border rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('imagenes/otros/home.png') }}" alt="precio" width="30" class="me-2">
                    <div>
                        @php
                            $precio = is_array($proyecto->precio) ? $proyecto->precio : json_decode($proyecto->precio, true);
                        @endphp
                        <span>Desde {{ $precio['min'] ?? 'N/A' }} SMMLV</span><br>
                        @if(isset($precio['max']) && $precio['max'])
                            <span>Hasta {{ $precio['max'] }} SMMLV</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('imagenes/otros/metros.png') }}" alt="área" width="30" class="me-2">
                    <div>
                        @php
                            $area = is_array($proyecto->area) ? $proyecto->area : json_decode($proyecto->area, true);
                        @endphp
                        <span>Área: {{ $area['min'] ?? 'N/A' }} m²</span><br>
                        @if(isset($area['max']) && $area['max'])
                            <span>Hasta {{ $area['max'] }} m²</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Apartamentos --}}
@php
    $apartamentos = is_array($proyecto->apartamentos) ? $proyecto->apartamentos : json_decode($proyecto->apartamentos, true);
@endphp
@if(is_array($apartamentos) && !empty($apartamentos))
<div class="container mb-5">
    <div class="row">
        <div class="col-12 text-center mb-3">
            <h4 class="titulo-apartamentos">
                Tipos de apartamento
            </h4>
        </div>
        @foreach ($apartamentos as $img)
            <div class="col-12 col-md-6 mb-4 d-flex align-items-center justify-content-center">
                <img src="{{ asset($img) }}" class="img-fluid imagenes-apartamento" alt="Apartamento">
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Zonas Sociales --}}
@if ($proyecto->zonasCatalogo && $proyecto->zonasCatalogo->count())
<section id="zonas" class="my-5">
    <div class="container">
        <h4 class="text-center mb-4" style="background-color: orange; padding: 5px 20px; color: #fff; border-radius: 5px;">
            Zonas Sociales
        </h4>
        <div class="row justify-content-center text-center align-items-center">
            @foreach ($proyecto->zonasCatalogo as $zona)
                <div class="col-auto mb-3 px-4">
                    <img src="{{ asset($zona->icono) }}" alt="{{ $zona->titulo }}" style="width: 40px;">
                    <p class="mt-2">{{ $zona->titulo }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Ubicación --}}
@if(!empty($proyecto->direccion))
<div class="ubicacion text-center" id="ubicacion">
    <h2>Ubicación</h2>
    <p>{{ $proyecto->direccion }}</p>
    <div style="max-width: 100%; overflow: hidden;">
        <iframe 
            src="https://www.google.com/maps?q={{ urlencode($proyecto->direccion) }}&output=embed"
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
@endif

{{-- Botones flotantes --}}
<div class="d-flex justify-content-between align-items-center my-3 px-4">
    <div style="position: fixed; top: 20%; left: 10px; transform: translateY(-50%); z-index: 999;">
        <a href="{{ url('/') }}" class="btn btn-light shadow-sm d-flex align-items-center mb-2" style="border-radius: 10px; color: orange; font-weight: bold;">
            <img src="{{ asset('imagenes/otros/home.png') }}" alt="Inicio" style="height: 20px; margin-right: 8px;">
            Inicio
        </a>
    </div>

    <div style="position: fixed; top: 20%; right: 10px; transform: translateY(-50%); z-index: 999;">
        <a href="{{ url('cotizacion') }}" class="btn btn-light shadow-sm d-flex align-items-center" style="border-radius: 10px; color: orange; font-weight: bold;">
            <img src="{{ asset('imagenes/otros/cotiza.jpg') }}" alt="Cotiza Ya" style="height: 20px; margin-right: 8px;">
            <span>Cotiza Ya</span>
        </a>
    </div>
</div>

@endsection
