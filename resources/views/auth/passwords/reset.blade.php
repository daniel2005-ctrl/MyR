@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/password-forms.css') }}">
@endsection

@section('content')
<div class="password-reset-container">
    <div class="reset-form-card">
        <div class="brand-header">
            <div class="brand-logo">🏠 MYR PROYECTOS</div>
            <div class="brand-subtitle">Construyendo un futuro firme</div>
        </div>
        
        <h2 class="form-title">Restablecer Contraseña</h2>
        <p class="form-description">
            Ingresa tu nueva contraseña para completar el restablecimiento.
        </p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email" class="form-label">📧 Correo Electrónico</label>
                <input id="email" 
                       type="email" 
                       class="form-control"
                       name="email" 
                       value="{{ old('email', $email ?? '') }}" 
                       required 
                       autofocus
                       readonly>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">🔒 Nueva Contraseña</label>
                <input id="password" 
                       type="password" 
                       class="form-control"
                       name="password" 
                       placeholder="Ingresa tu nueva contraseña"
                       required>
                <div class="password-requirements">
                    <strong>Requisitos de la contraseña:</strong>
                    <ul>
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula</li>
                        <li>Al menos una letra minúscula</li>
                        <li>Al menos un número</li>
                    </ul>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password-confirm" class="form-label">🔒 Confirmar Nueva Contraseña</label>
                <input id="password-confirm" 
                       type="password" 
                       class="form-control"
                       name="password_confirmation" 
                       placeholder="Confirma tu nueva contraseña"
                       required>
            </div>
            
            <button type="submit" class="btn-reset" id="resetBtn">
                ✅ Restablecer Contraseña
            </button>
        </form>
        
        <div class="back-link">
            <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.appRoutes = {
        proyectosIndex: '{{ route("proyectos.index") }}',
        login: '{{ route("login") }}'
    };
    
    @if (session('status') === 'Contraseña cambiada con éxito.')
        window.sessionStatus = 'Contraseña cambiada con éxito.';
    @endif
</script>
<script src="{{ asset('js/password-reset.js') }}"></script>
@endsection