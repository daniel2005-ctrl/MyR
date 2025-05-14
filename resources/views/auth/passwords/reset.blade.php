@extends('layouts.app')

@section('title', 'Restablecer contraseña')


@section('content')

<style>
    .fondo-contenedor {
        position: relative;
        background-size: cover;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0px 0;
    }

    .fondo-imagen {
        max-width: 100%;
        height: auto;
        border-radius: 0px;
    }

    .reset-card {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 700px;
        padding: 40px;
        z-index: 10;
    }
</style>

  <div class="fondo-contenedor">
    <img src="/imagenes/myr72/Fondo72.png" class="fondo-imagen" alt="Fondo">
    <div class="reset-card">
    <h2 class="mb-4 text-center">Restablecer contraseña</h2>

   

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
             <input id="email" type="email" class="form-control form-control-lg"
                   name="email" value="{{ old('email', $email ?? '') }}" required autofocus>
        </div>

        <div class="form-group mb-3">
            <label for="password">Nueva contraseña</label>
            <input id="password-confirm" type="password" class="form-control form-control-lg"
                   name="password" required>
        </div>

        <div class="form-group mb-3">
            <label for="password-confirm">Confirmar nueva contraseña</label>
            <input id="password-confirm" type="password" class="form-control"
                   name="password_confirmation" required>
        </div>

        <div class="d-grid gap-2">
             <button type="submit" class="btn btn-warning btn-lg">Restablecer contraseña</button>
        </div>
    </form>
</div>
                </div>
            </div>
        </div>
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
                window.location.href = "{{ route('in') }}"; // Redirige al login
            });
        </script>
    @endif
@endsection
