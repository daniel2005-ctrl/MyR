<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización de Vivienda y Parqueadero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            color: #777;
        }
        .section-title {
            font-size: 20px;
            color: #333;
            margin-top: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }
        .section-content {
            font-size: 16px;
            color: #555;
            margin-top: 10px;
        }
        .section-content p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .table td {
            background-color: #fafafa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Cotización de Vivienda y Parqueadero</h1>
            <p>Generada el {{ now()->format('d/m/Y') }}</p>
        </div>

        <!-- Información de la Vivienda -->
        <div class="section">
            <div class="section-title">Detalles de la Vivienda</div>
            <div class="section-content">
                <p><strong>Valor Vivienda:</strong> {{ $valorVivienda }}</p>
                <p><strong>Cuota Inicial:</strong> {{ $cuotaInicial }}</p>
                <p><strong>Separación:</strong> {{ $separacion }}</p>
            </div>
        </div>

        <!-- Información del Parqueadero -->
        <div class="section">
            <div class="section-title">Detalles del Parqueadero</div>
            <div class="section-content">
                <p><strong>Valor Parqueadero:</strong> {{ $valorParqueadero }}</p>
                <p><strong>Cuota Inicial Parqueadero:</strong> {{ $cuotaInicialParqueadero }}</p>
                <p><strong>Separación Parqueadero:</strong> {{ $separacionParqueadero }}</p>
            </div>
        </div>

        <!-- Tabla de Cuotas y Plazos -->
        <div class="section">
            <div class="section-title">Cuotas y Plazos</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Plazo General</strong></td>
                        <td>{{ $plazoGeneral }} meses</td>
                    </tr>
                    <tr>
                        <td><strong>Cuota Mensual General</strong></td>
                        <td>{{ $cuotaMensualGeneral }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Condiciones Generales -->
        <div class="section">
            <div class="section-title">Condiciones Generales</div>
            <div class="section-content">
                <p>La cotización está sujeta a cambios según las condiciones del mercado y la disponibilidad de las propiedades. Los plazos y cuotas están calculados según los valores iniciales, y pueden variar según el tipo de financiamiento seleccionado.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por confiar en nosotros. Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p>&copy; {{ now()->format('Y') }}. Todos los derechos reservados.</p>
        </div>
    </div>

</body>
</html>
