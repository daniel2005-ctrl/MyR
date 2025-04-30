@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Enlace de Reseteo</button>
        </form>
    </div>
@endsection
