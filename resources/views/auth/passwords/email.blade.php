@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/password-forms.css') }}">
<style>
    .alert {
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
</style>
@endsection

@section('content')
<div class="password-reset-container">
    <div class="reset-form-card">
        <div class="brand-header">
            <div class="brand-logo">🏠 MYR PROYECTOS</div>
            <div class="brand-subtitle">Construyendo un futuro firme</div>
        </div>
        
        <h2 class="form-title">Recuperar Contraseña</h2>
        <p class="form-description">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>
        
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('password.email') }}" method="POST" id="emailResetForm">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">📧 Correo Electrónico</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="{{ old('email') }}"
                       placeholder="tu@email.com"
                       required 
                       autofocus>
            </div>
            
            <button type="submit" class="btn-reset" id="emailBtn">
                🔑 Enviar Enlace de Restablecimiento
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
        proyectosIndex: '{{ route("proyectos.index") }}'
    };
</script>
<script src="{{ asset('js/password-email.js') }}"></script>
@endsection