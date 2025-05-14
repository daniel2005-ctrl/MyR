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

        <!-- Estado del proyecto -->
        <div class="mb-4">
            <label for="estado_proyecto" class="form-label fw-bold">Estado del proyecto</label>
            <select name="estado_id" id="estado_proyecto" class="form-select" required>
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
                <label class="form-label">Precio mínimo</label>
                <input type="number" name="precio_min" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio máximo</label>
                <input type="number" name="precio_max" step="0.01" class="form-control">
            </div>
        </div>

        <!-- Área -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Área mínima (m²)</label>
                <input type="number" name="area_min" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Área máxima (m²)</label>
                <input type="number" name="area_max" step="0.01" class="form-control">
            </div>
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

        <!-- Enviar -->
      <!-- Estado del proyecto (automáticamente terminado) -->
<input type="hidden" name="terminado" value="1">

<div class="text-center mt-4">
    <button type="submit" class="btn btn-primary btn-lg">Guardar Proyecto</button>
</div>
    </form>
</div>
@endsection
