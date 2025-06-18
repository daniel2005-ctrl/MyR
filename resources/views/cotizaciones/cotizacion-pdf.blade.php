<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización del Proyecto</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 40px 30px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .header-table .title-cell {
            text-align: left;
            width: 70%;
        }

        .header-table .logo-cell {
            text-align: right;
            width: 30%;
        }

        .header h2 {
            color: #ff6a00;
            font-size: 26px;
            margin: 0;
        }

        .header .logo {
            width: 80px;
            height: auto;
        }

        .header p {
            font-size: 12px;
            color: #777;
        }

        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            color: #ff6a00;
            border-bottom: 2px solid #ff6a00;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        p {
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #ff6a00;
            color: white;
            font-size: 14px;
        }

        .resumen {
            font-weight: bold;
            font-size: 15px;
            margin-top: 10px;
            text-align: right;
        }

        .condiciones {
            margin-top: 35px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="title-cell">
                        <h2>MYR Proyectos y Construcciones S.A.S</h2>
                    </td>
                    <td class="logo-cell">
                        @if(file_exists(public_path('imagenes/Logos.png')))
                            <img src="{{ public_path('imagenes/Logos.png') }}" alt="Logo MYR" class="logo">
                        @else
                            <span style="color: red;">Logo no encontrado</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">Vivienda</div>
            <p><strong>Proyecto:</strong> {{ $nombreProyecto }}</p>
            <p><strong>Valor Vivienda:</strong> {{ $valorVivienda }}</p>
            <p><strong>Cuota Inicial:</strong> {{ $cuotaInicial }}</p>
            <p><strong>Separación:</strong> {{ $separacion }}</p>
        </div>

        {{-- Condicional para mostrar parqueadero --}}
        @php
            $parqueaderoVisible = !empty($valorParqueadero) && $valorParqueadero !== '$ 0' && $valorParqueadero !== 0;
        @endphp

        @if($parqueaderoVisible)
            <div class="section">
                <div class="section-title">Parqueadero</div>
                <p><strong>Valor Parqueadero:</strong> {{ $valorParqueadero }}</p>
                <p><strong>Cuota Inicial Parqueadero:</strong> {{ $cuotaInicialParqueadero }}</p>
                <p><strong>Separación Parqueadero:</strong> {{ $separacionParqueadero }}</p>
            </div>
        @endif

        {{-- Total de Ahorros --}}
        @isset($totalAhorros)
            <div class="section">
                <div class="section-title">Total de Ahorros</div>
                <p><strong>Total Ahorros Aplicados:</strong> {{ $totalAhorros }}</p>
            </div>
        @endisset

        <div class="section">
            <div class="section-title">Cuota y Plazo</div>
            <table>
                <thead>
                    <tr>
                        <th>Plazo (meses)</th>
                        <th>Cuota Mensual</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $plazoGeneral }}</td>
                        <td>{{ $cuotaMensualGeneral }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="condiciones">
            <h3>Condiciones Generales</h3>
            <p>
                La cotización está sujeta a cambios según las condiciones del mercado y la disponibilidad de las propiedades.
                Los plazos y cuotas están calculados según los valores iniciales, y pueden variar según el tipo de cuota y plazo seleccionado.
            </p>
        </div>

        <div class="footer">
            <p>Gracias por confiar en nosotros. Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p>© 2025. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
