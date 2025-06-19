@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endpush

@section('content')
<br>

<div class="edit-wrapper"> {{-- NUEVO WRAPPER PARA AISLAR ESTILOS --}}
    <div class="profile-container"> {{-- CAMBIAMOS .container por una clase única --}}
        <div class="w-100">
            <header class="text-center py-3 bg-white rounded shadow-sm">
                <h1>Editar Perfil</h1>
            </header>

            {{-- Mensajes de éxito --}}
            @if (session('mensaje'))
                <div class="alert alert-success text-center mt-3">
                    {{ session('mensaje') }}
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row justify-content-center mt-4">
                <div class="col-md-8 border p-4 rounded bg-white shadow-sm">
                    <form method="POST" action="{{ route('perfil.update') }}">
                         @csrf
                         @method('PUT')  {{-- Esto es lo que faltaba --}}

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_actual" class="form-label fw-bold">Contraseña Actual</label>
                            <input type="password" name="password_actual" id="password_actual" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_nueva" class="form-label fw-bold">Nueva Contraseña</label>
                            <input type="password" name="password_nueva" id="password_nueva" class="form-control">
                            <small class="form-text text-muted">Opcional. Debe tener al menos 8 caracteres.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_nueva_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_nueva_confirmation" id="password_nueva_confirmation" class="form-control">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning fw-bold">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones flotantes --}}
    <div style="position: fixed; top: 20%; left: 10px; transform: translateY(-50%); z-index: 999;">
        <a href="{{ url('/') }}" class="btn btn-light shadow-sm d-flex align-items-center mb-2" style="border-radius: 10px; color: orange; font-weight: bold;">
            <img src="https://res.cloudinary.com/dtwtippni/image/upload/v1750112199/proyectos/otros/home.png" alt="Inicio" style="height: 20px; margin-right: 8px;">
            Inicio
        </a>
    </div>

@endsection
