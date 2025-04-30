@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2 class="text-center mb-4">Restablecer contraseña</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group mb-3">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" class="form-control"
                   name="email" value="{{ old('email', $email ?? '') }}" required autofocus>
        </div>

        <div class="form-group mb-3">
            <label for="password">Nueva contraseña</label>
            <input id="password" type="password" class="form-control"
                   name="password" required>
        </div>

        <div class="form-group mb-3">
            <label for="password-confirm">Confirmar nueva contraseña</label>
            <input id="password-confirm" type="password" class="form-control"
                   name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-warning w-100">Restablecer</button>
    </form>
</div>
@endsection

@section('scripts')
    @if (session('status') === 'Contraseña cambiada con éxito.')
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'Tu contraseña fue actualizada correctamente.',
                icon: 'success',
                confirmButtonColor: '#ff6600',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('login') }}"; // Redirige al login
            });
        </script>
    @endif
@endsection
