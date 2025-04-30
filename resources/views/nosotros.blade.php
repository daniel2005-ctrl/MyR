@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/nosotros.css') }}">
@endpush

@section('content')



{{-- Sección de fondo con imagen --}}
<section class="section-background position-relative text-center text-white">
    <img src="{{ asset('imagenes/conocenos.png') }}" alt="Fondo" class="background-image img-fluid w-100">
    <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <h2 class="position-absolute top-50 start-50 translate-middle">Conoce nuestra experiencia, calidad y buen precio</h2>
</section>

{{-- Línea separadora --}}
<div class="separator"></div>

{{-- Sección Visión --}}
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start order-md-1">
            <h2 class="text-primary hover-underline">Visión</h2>
            <p>
                Ser una constructora reconocida por ofrecer sus servicios en Ibagué, planteando la posibilidad de extenderse
                a otras partes del territorio nacional con viviendas de alta calidad y a precios accesibles, contribuyendo
                al desarrollo sostenible y al bienestar de la comunidad.
            </p>
        </div>
        <div class="col-md-6 text-center order-md-2">
            <img src="{{ asset('imagenes/vision.png') }}" alt="Visión progreso" class="img-fluid rounded">
        </div>
    </div>
</div>

{{-- Sección Misión --}}
<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center order-md-1">
            <img src="{{ asset('imagenes/micion.jpg') }}" alt="Misión" class="img-fluid rounded">
        </div>
        <div class="col-md-6 text-center text-md-start order-md-2">
            <h2 class="text-primary hover-underline">Misión</h2>
            <p>
                Crear proyectos de vivienda VIS que garanticen la seguridad, calidad y asequibilidad, 
                construyendo hogares que mejoren la calidad de vida de las familias colombianas, 
                con integridad y responsabilidad.
            </p>
        </div>
    </div>
</div>

{{-- Valores institucionales --}}
<section class="valores text-center my-5">
    <h2 class="text-primary">Valores Institucionales</h2>
    <div class="valores-container d-flex flex-wrap justify-content-center mt-4">
        @php
            $valores = [
                ['img' => 'colabora.jpg', 'texto' => 'Colaboración'],
                ['img' => 'respeto.jpg', 'texto' => 'Respeto'],
                ['img' => 'trans.jpg', 'texto' => 'Transparencia'],
                ['img' => 'renspo.jpg', 'texto' => 'Responsabilidad'],
                ['img' => 'soli.jpg', 'texto' => 'Solidaridad'],
            ];
        @endphp

        @foreach ($valores as $valor)
        <div class="valor-card text-center mx-3 mb-4">
            <img src="{{ asset('imagenes/' . $valor['img']) }}" alt="{{ $valor['texto'] }}" class="img-fluid" style="height: 150px;">
            <p class="mt-2">{{ $valor['texto'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- Línea separadora --}}
<div class="separator"></div>



@endsection
