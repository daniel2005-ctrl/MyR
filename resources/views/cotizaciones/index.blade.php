@extends('layouts.app')

@section('title', 'Calcula tu cuota inicial')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cotizacion.css') }}">
@endpush

@section('content')
    @if(session('warning'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Advertencia!',
                    text: {!! json_encode(session('warning')) !!},
                    confirmButtonText: 'Aceptar'
                }).then(function () {
                    window.location.href = '{{ route('home') }}';
                });
            });
        </script>
    @endif

    <!-- Agregar esta nueva advertencia al cargar la página -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'warning',
                title: '⚠️ Información Importante',
                html: `
                    <p><strong>Antes de continuar, ten en cuenta:</strong></p>
                    <ul style="text-align: left; margin: 10px 0;">
                        <li>Los precios están sujetos a cambios</li>
                        <li>La cotización es solo una estimación</li>
                        <li>Para información oficial, contacta a nuestros asesores</li>
                    </ul>
                `,
                confirmButtonText: 'He leído y entiendo',
                confirmButtonColor: '#ff8000',
                allowOutsideClick: false,
                allowEscapeKey: false,
                width: '500px'
            });
        });
    </script>

    <div id="contenido">
        <div class="container mt-4">
            <h1>Calcula tu cuota inicial</h1>
            <p class="subtext">
                Porcentaje de la cuota:<br>
                30% sobre el valor del apartamento
            </p>

            <!-- Subsidio o crédito -->
            @if(empty($pdf) || !$pdf)
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="subsidio">
                    <label class="form-check-label" for="subsidio">¿Vas a utilizar subsidio o crédito?</label>
                </div>
                @if($subsidios->count())
                    <div id="infoSubsidio" class="alert alert-info mt-3 d-none">
                        Estás interesado en subsidio...
                        <br>
                        @foreach($subsidios as $subsidio)
                            <a href="{{ $subsidio->url }}" target="_blank" class="btn btn-primary btn-sm mt-2 me-2">
                                {{ $subsidio->nombre }}
                            </a>
                        @endforeach
                    </div>
                @endif
                @if($creditos->count())
                    <div id="infoCredito" class="alert alert-warning mt-2 d-none">
                        Estás interesado en crédito...
                        <br>
                        @foreach($creditos as $credito)
                            <a href="{{ $credito->url }}" target="_blank" class="btn btn-warning btn-sm mt-2 me-2">
                                {{ $credito->nombre }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif

            <!-- Formulario vivienda -->
            <div class="mb-3 mt-4">
                <label for="vivienda" class="form-label">Selecciona tu vivienda:</label>
                <select id="vivienda" class="form-control">
                    <option value="" disabled selected>Selecciona una opción</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_pro }}" 
                                data-precio-min="{{ $proyecto->precio['min'] ?? 0 }}" 
                                data-precio-max="{{ $proyecto->precio['max'] ?? 0 }}"
                                data-parqueadero="{{ is_array($proyecto->parqueadero) ? ($proyecto->parqueadero['min'] ?? $proyecto->parqueadero['max'] ?? 0) : ($proyecto->parqueadero ?? 0) }}"
                                data-nombre="{{ $proyecto->nombre_pro }}">
                            {{ $proyecto->nombre_pro }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de apartamento - Solo se muestra condicionalmente -->
            <div class="mb-3" id="tipoApartamentoContainer" style="display: none;">
                <label for="tipoApartamento" class="form-label">Tipo de apartamento:</label>
                <select id="tipoApartamento" class="form-control">
                    <option value="" disabled selected>Selecciona el tipo</option>
                    <option value="min">Apartamento tipo 1</option>
                    <option value="max">Apartamento tipo 2</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor total de la vivienda:</label>
                <input type="text" id="valor" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="cuota-inicial" class="form-label">Cuota inicial (30%):</label>
                <input type="text" id="cuota-inicial" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="separacion" class="form-label">Separación (10% de cuota inicial):</label>
                <input type="text" id="separacion" class="form-control" readonly>
            </div>

            <!-- Parqueadero -->
            <!-- Parqueadero - Solo se muestra si hay precio de parqueadero -->
            <div class="form-check form-switch" id="parqueaderoContainer" style="display: none;">
                <input class="form-check-input" type="checkbox" id="switchParqueadero">
                <label class="form-check-label" for="switchParqueadero">Adquirir Parqueadero</label>
            </div>

            <div id="parqueaderoCard" class="alert alert-success mt-3" style="display: none;">
                <h4>Detalles del Parqueadero</h4>

                <div class="mb-3">
                    <label for="valorParqueadero" class="form-label">Valor total:</label>
                    <input type="text" id="valorParqueadero" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="cuotaInicialParqueadero" class="form-label">Cuota inicial (30%):</label>
                    <input type="text" id="cuotaInicialParqueadero" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="separacionParqueadero" class="form-label">Separación (10%):</label>
                    <input type="text" id="separacionParqueadero" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="plazoMeses" class="form-label">Plazo:</label>
                    <select id="plazoMeses" class="form-control">
                        <option value="12">12 meses</option>
                        <option value="24">24 meses</option>
                        <option value="36" selected>36 meses</option>
                        <option value="48">48 meses</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cuotaMensualParqueadero" class="form-label">Cuota mensual:</label>
                    <input type="text" id="cuotaMensualParqueadero" class="form-control" readonly>
                </div>
            </div>

            <!-- Ahorros -->
            <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" id="tiene-ahorros">
                <label class="form-check-label" for="tiene-ahorros">¿Tienes ahorros?</label>
            </div>
            
            <div id="campo-ahorros" class="mb-3 mt-3" style="display: none;">
                <label for="total-ahorros" class="form-label">Total ahorros:</label>
                <input type="number" id="total-ahorros" class="form-control" value="0">
            </div>
            
            <!-- Plazo cuota mensual -->
            <div class="mb-3">
                <label for="meses-modificable" class="form-label">Modificar plazo:</label>
                <select id="meses-modificable" class="form-control">
                    <option value="12">12 meses</option>
                    <option value="24">24 meses</option>
                    <option value="36">36 meses</option>
                    <option value="48">48 meses</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cuota-mensual" class="form-label">Valor cuota mensual:</label>
                <input type="text" id="cuota-mensual" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- Pass salary minimum to JavaScript -->
    <script>
        window.salarioMinimo = {{ $salarioMinimo }};
    </script>

    <a href="{{ route('home') }}" class="btn-home">
        🏠 Inicio
    </a>

    <a href="javascript:void(0);" id="btnImprimirPDF" class="btn-imprimir-pdf">
        🖨 Imprimir PDF
    </a>
    
    <form id="formPDF" method="POST" action="{{ route('generar.pdf') }}" target="_blank" style="display: none;">
        @csrf
        <input type="hidden" name="nombre_proyecto" id="nombre_proyecto">
        <input type="hidden" name="valor_vivienda" id="valor_vivienda">
        <input type="hidden" name="cuota_inicial" id="cuota_inicial">
        <input type="hidden" name="separacion" id="separacion">
        
        <input type="hidden" name="valor_parqueadero" id="valor_parqueadero">
        <input type="hidden" name="cuota_inicial_parq" id="cuota_inicial_parq">
        <input type="hidden" name="separacion_parq" id="separacion_parq">
        
        <input type="hidden" name="plazo_general" id="plazo_general">
        <input type="hidden" name="cuota_mensual_general" id="cuota_mensual_general">
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/cotizacion.js') }}"></script>
@endpush