@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Agregar Nuevo Subsidio/Crédito</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subsidios_creditos.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="subsidio" {{ old('tipo') == 'subsidio' ? 'selected' : '' }}>Subsidio</option>
                                <option value="credito" {{ old('tipo') == 'credito' ? 'selected' : '' }}>Crédito</option>
                            </select>
                            @error('tipo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label">URL *</label>
                            <input type="url" name="url" id="url" class="form-control" 
                                   value="{{ old('url') }}" required>
                            <small class="form-text text-muted">Incluye http:// o https://</small>
                            @error('url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number" name="orden" id="orden" class="form-control" 
                                   value="{{ old('orden', 1) }}" min="1">
                            <small class="form-text text-muted">Orden de aparición (menor número aparece primero)</small>
                            @error('orden')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="activo" id="activo" class="form-check-input" 
                                       value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                <label for="activo" class="form-check-label">Activo</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subsidios_creditos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection