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
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .contenedor-formulario {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 3px solid #ffc107;
            position: relative;
        }
        
        .contenedor-formulario::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, #ffc107, #ff8400, #ffc107);
            border-radius: 18px;
            z-index: -1;
        }
        
        h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 30px;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        
        button {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 8px;
            border: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .contenedor-formulario {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="contenedor-formulario">
    <h2 class="text-center mb-4">Formulario de Contacto</h2> 

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('info'))
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        {{ session('info') }}
    </div>
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

    <form id="formulario-contacto" action="{{ route('formulario.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label> 
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label> 
            <input type="tel" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label> 
            <input type="email" id="correo" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
        </div>

        <div class="mb-3">
    <label for="proyecto" class="form-label">Selecciona un proyecto:</label>
    <select id="proyecto" name="proyecto" class="form-select @error('proyecto') is-invalid @enderror" required>
        <option value="" {{ old('proyecto') ? '' : 'selected' }} disabled>Elige un proyecto</option>
        @foreach($proyectos as $proyecto)
            <option value="{{ $proyecto->id_pro }}" {{ old('proyecto') == $proyecto->id_pro ? 'selected' : '' }}>
                {{ $proyecto->nombre_pro }}
            </option>
        @endforeach
    </select>
    
</div>

        <button type="submit">Enviar</button> 
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para mostrar alerta de confirmación antes de enviar -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const rutaTerminos = @json(route('terminos')); // genera URL segura para JS

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // evita envío inmediato

        Swal.fire({
            title: '¿Deseas enviar el formulario?',
            html: `
                <p>Debes aceptar los <a href="${rutaTerminos}" target="_blank">Términos y Condiciones</a> antes de continuar.</p>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="aceptoTerminos">
                    <label class="form-check-label" for="aceptoTerminos">Acepto los términos y condiciones</label>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const checkbox = document.getElementById('aceptoTerminos');
                if (!checkbox.checked) {
                    Swal.showValidationMessage('Debes aceptar los términos y condiciones.');
                }
                return checkbox.checked;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // ahora sí se envía el formulario
            }
        });
    });
});
</script>


</body>
</html>