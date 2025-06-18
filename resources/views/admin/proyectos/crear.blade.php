@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/crear.css') }}">
@endpush

@section('title', 'Crear nuevo proyecto')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold">Crear nuevo proyecto</h2>

    <form action="{{ route('proyectos.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Estado del proyecto -->
        <div class="mb-4">
            <label for="estado_proyecto" class="form-label fw-bold">Estado del proyecto</label>
            <select name="id_estado" id="estado_proyecto" class="form-select" required>
                <option value="">Seleccione una opción</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
                @endforeach
            </select>
        </div>

        <!-- Título del proyecto -->
        <div class="mb-4">
            <label class="form-label fw-bold">Título del proyecto</label>
            <input type="text" name="nombre_pro" class="form-control" required>
        </div>

        <!-- Imágenes del proyecto -->
        <div class="mb-4">
            <label class="form-label fw-bold">Imágenes del proyecto</label>
            <input type="file" name="imagenes_pro[]" class="form-control" accept="image/*" multiple required>
        </div>

        <!-- Imagen de portada -->
        <div class="mb-4">
            <label class="form-label fw-bold">Imagen de portada</label>
            <input type="file" name="imagenes_header" class="form-control" accept="image/*" required>
            <small class="form-text text-muted">Esta imagen se mostrará como portada principal del proyecto en la página de inicio.</small>
        </div>

        <!-- Videos del proyecto -->
        <div class="mb-4">
            <label class="form-label fw-bold">Videos del proyecto</label>
            <input type="file" name="videos_pro[]" class="form-control" accept="video/*" multiple>
            <small class="form-text text-muted">Formatos soportados: MP4, AVI, MOV. Opcional.</small>
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label class="form-label fw-bold">Descripción</label>
            <textarea name="descripcion[]" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Zonas Sociales -->
        <div class="mb-4 border-top pt-3">
            <h6 class="text-uppercase fw-bold text-muted">Zonas Sociales</h6>
            <p class="text-muted">Selecciona las zonas sociales disponibles para este proyecto</p>
            <div class="row g-3">
                @foreach ($zonas as $zona)
                    <div class="col-md-4 col-sm-6">
                        <label for="zona_{{ $zona->id }}" class="card h-100 p-3 border shadow-sm rounded-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($zona->icono)
                                    <img src="{{ asset($zona->icono) }}" alt="{{ $zona->titulo }}" width="24" height="24">
                                @else
                                    <i class="bi bi-building text-secondary fs-5"></i>
                                @endif
                                <span class="fw-semibold text-dark">{{ $zona->titulo }}</span>
                                <input type="checkbox" class="form-check-input ms-auto" name="zonas[]" value="{{ $zona->id }}" id="zona_{{ $zona->id }}">
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Precio -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Precio apto 1</label>
                <input type="number" name="precio_min" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio  apto 2</label>
                <input type="number" name="precio_max" step="0.01" class="form-control">
            </div>
        </div>

        <!-- Área -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Área apto 1 (m²)</label>
                <input type="number" name="area_min" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Área apto 2 (m²)</label>
                <input type="number" name="area_max" step="0.01" class="form-control">
            </div>
        </div>

        <!-- Parqueadero -->
        <div class="mb-4">
            <label class="form-label fw-bold">Precio del parqueadero (SMMLV)</label>
            <input type="number" name="parqueadero" step="0.01" class="form-control" placeholder="Ej: 3.5">
            <small class="form-text text-muted">Precio en Salarios Mínimos Mensuales Legales Vigentes. Dejar vacío si no aplica.</small>
        </div>

        <!-- Tipos de apartamento -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipos de apartamento</label>
            <input type="file" name="apartamentos[]" class="form-control" accept="image/*" multiple>
        </div>

        <!-- Ubicación -->
        <div class="mb-4">
            <label class="form-label fw-bold">Ubicación</label>
            <input type="text" name="ubicacion_pro" class="form-control" placeholder="Ej: Calle 123 #45-67">
            <div class="mt-3">
                <iframe src="https://www.google.com/maps?q=Bogotá&output=embed" width="100%" height="300" style="border:0;"></iframe>
            </div>
        </div>

        <!-- Tipo de vivienda (VIS, No VIS, VIP) -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Vivienda</label>
            <select name="tipo_pro" class="form-select" required>
                <option value="">Seleccione una opción</option>
                <option value="VIS">Vivienda de Interés Social (VIS)</option>
                <option value="No VIS">No VIS</option>
                <option value="VIP">Vivienda de Interés Prioritario (VIP)</option>
            </select>
        </div>

        <!-- Estado del proyecto (automáticamente terminado) -->
        <input type="hidden" name="terminado" value="1">

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar Proyecto</button>
        </div>
    </form>
</div>
@endsection