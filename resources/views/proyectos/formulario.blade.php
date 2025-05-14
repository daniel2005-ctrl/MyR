

<!DOCTYPE html>
<html lang="es"> 
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Formulario en Tarjeta</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }
        .contenedor-formulario {
            position: fixed;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            width: 30rem;
            max-height: 90vh;
            overflow-y: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border: 5px solid #ffc107;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="contenedor-formulario">
    <h2 class="text-center mb-4">Formulario de Contacto</h2> 

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('formulario.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label> 
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label> 
            <input type="tel" id="telefono" name="telefono" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label> 
            <input type="email" id="correo" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
           
        </div>

        <div class="mb-3">
            <label for="proyecto" class="form-label">Selecciona un proyecto:</label>
            <select id="proyecto" name="proyecto" class="form-select @error('proyecto') is-invalid @enderror" required>
                <option value="" {{ old('proyecto') ? '' : 'selected' }} disabled>Elige un proyecto</option>
                <option value="1" {{ old('proyecto') == '1' ? 'selected' : '' }}>MYR72</option>
                <option value="2" {{ old('proyecto') == '2' ? 'selected' : '' }}>Altos De Rincon De Varsovia</option>
                <option value="3" {{ old('proyecto') == '3' ? 'selected' : '' }}>Prados De Varsovia</option>
                <option value="4" {{ old('proyecto') == '4' ? 'selected' : '' }}>Rincon De Varsovia</option>
            </select>

        </div>

        <button type="submit">Enviar</button> 
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
