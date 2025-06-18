@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/nosotros.css') }}">
@endpush

@section('content')

{{-- Sección de fondo con imagen --}}
<section class="section-background position-relative text-center text-white"> 
    <img src="{{ 
        (str_contains($nosotros->imagen_fondo, 'cloudinary.com') || str_contains($nosotros->imagen_fondo, 'res.cloudinary.com')) 
            ? $nosotros->imagen_fondo 
            : asset($nosotros->imagen_fondo) 
    }}" alt="Fondo" class="background-image img-fluid w-100">
     <div class="overlay position-absolute top-0 start-0 w-100 h-100">
        </div> 
        <h2 class="position-absolute top-50 start-50 translate-middle">Conoce nuestra experiencia, calidad y buen precio</h2> 
    </section>

{{-- Línea separadora --}}
<div class="separator"></div>

{{--seccio intro--}}
<div class="card">
        <div class="header">
            <div class="title">MyR Proyectos y Construcciones SAS</div>
            <div class="subtitle">Experiencia, Calidad y Precio Justo</div>
        </div>
        <div>
            <p>Durante una <span class="highlight">década de trayectoria</span> (2015-2025), MyR Proyectos y Construcciones SAS ha consolidado su posición en el sector de la construcción en Ibagué.</p>
            
            <p>Especializados en <span class="highlight">diseño, construcción y comercialización</span> de vivienda, con énfasis en proyectos VIS que transforman Ibagué.</p>
            
            <p>Nuestro compromiso: </span> - Reimaginamos espacios, adaptamos soluciones, superamos expectativas.</p>
            
            <div class="anniversary">Celebrando 10 años construyendo sueños</div>
        </div>
    </div>
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
            <img src="{{ 
                (str_contains($nosotros->imagen_vision, 'cloudinary.com') || str_contains($nosotros->imagen_vision, 'res.cloudinary.com')) 
                    ? $nosotros->imagen_vision 
                    : asset($nosotros->imagen_vision) 
            }}" alt="Visión progreso" class="img-fluid rounded">
        </div>
    </div>
</div>

{{-- Sección Misión --}}
<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center order-md-1">
            <img src="{{ 
                (str_contains($nosotros->imagen_mision, 'cloudinary.com') || str_contains($nosotros->imagen_mision, 'res.cloudinary.com')) 
                    ? $nosotros->imagen_mision 
                    : asset($nosotros->imagen_mision) 
            }}" alt="Misión" class="img-fluid rounded">
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
            // Orden específico de valores institucionales
            $valoresOrdenados = [
                'Colaboración',
                'Respeto', 
                'Transparencia',
                'Responsabilidad',
                'Solidaridad'
            ];
            
            // Obtener URLs de imágenes desde el campo valores (separadas por comas)
            $imagenesFromDB = $nosotros->valores ?? '';
            
            // Convertir string separado por comas a array
            $imagenesArray = [];
            if (is_string($imagenesFromDB)) {
                $imagenesArray = array_map('trim', explode(',', $imagenesFromDB));
            } elseif (is_array($imagenesFromDB)) {
                $imagenesArray = $imagenesFromDB;
            }
            
            // Mapear cada valor con su imagen correspondiente por índice
            $imagenesValores = [];
            foreach ($valoresOrdenados as $index => $valor) {
                $imagenesValores[$valor] = $imagenesArray[$index] ?? 'imagenes/default.jpg';
            }
        @endphp

        <div class="d-flex justify-content-between align-items-center my-3 px-4">
    <!-- Botón flotante izquierdo (Inicio) -->
    <div style="position: fixed; top: 20%; left: 10px; transform: translateY(-50%); z-index: 999;">
        <a href="{{ url('/') }}" class="btn btn-light shadow-sm d-flex align-items-center mb-2" style="border-radius: 10px; color: orange; font-weight: bold;">
            <img src="{{ 
                (str_contains('imagenes/otros/home.png', 'cloudinary.com') || str_contains('imagenes/otros/home.png', 'res.cloudinary.com')) 
                    ? 'imagenes/otros/home.png' 
                    : asset('imagenes/otros/home.png') 
            }}" alt="Inicio" style="height: 20px; margin-right: 8px;">
            Inicio
        </a>
    </div>

     <!-- Botón flotante derecho (Cotiza Ya) -->
    <div style="position: fixed; top: 20%; right: 10px; transform: translateY(-50%); z-index: 999;">
        <a href="{{ url('cotizacion') }}" class="btn btn-light shadow-sm d-flex align-items-center" style="border-radius: 10px; color: orange; font-weight: bold;">
            <img src="{{ 
                (str_contains('imagenes/otros/cotiza.jpg', 'cloudinary.com') || str_contains('imagenes/otros/cotiza.jpg', 'res.cloudinary.com')) 
                    ? 'imagenes/otros/cotiza.jpg' 
                    : asset('imagenes/otros/cotiza.jpg') 
            }}" alt="Cotiza Ya" style="height: 20px; margin-right: 8px;">
            <span>Cotiza Ya</span>
        </a>
    </div>

        @foreach ($valoresOrdenados as $valor)
            @php
                $imagenValor = $imagenesValores[$valor];
            @endphp
            <div class="valor-card text-center mx-3 mb-4">
                <img src="{{ 
                    (str_contains($imagenValor, 'cloudinary.com') || str_contains($imagenValor, 'res.cloudinary.com')) 
                        ? $imagenValor 
                        : asset($imagenValor) 
                }}" alt="{{ $valor }}" class="img-fluid" style="height: 150px;">
                <p class="mt-2">{{ $valor }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Línea separadora --}}
<div class="separator"></div>



@endsection
