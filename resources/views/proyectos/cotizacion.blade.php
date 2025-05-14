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

    <div id="contenido">
        <div class="container mt-4">
            <h1>Calcula tu cuota inicial</h1>
            <p class="subtext">
                Porcentaje de la cuota:<br>
                30% sobre el valor del apartamento
            </p>

            <!-- Subsidio o crédito -->
            @if(empty($pdf) || !$pdf) <!-- Condición para mostrar solo si no es un PDF -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="subsidio">
                    <label class="form-check-label" for="subsidio">
                        ¿Vas a utilizar subsidio o crédito?
                    </label>
                </div>

                <div id="infoSubsidio" class="alert alert-info mt-3" style="display: none;">
                    Estás interesado en subsidio...
                    <br>
                    <a href="https://www.comfenalco.com.co/subsidio-de-vivienda/" target="_blank" class="btn btn-primary btn-sm mt-2">
                        Subsidio Comfenalco
                    </a>
                    <a href="https://www.comfatolima.com.co/beneficios/subsidios/subsidio-de-vivienda/" target="_blank" class="btn btn-primary btn-sm mt-2 ms-2">
                        Subsidio ConfaTolima
                    </a>
                </div>

                <div id="infoCredito" class="alert alert-warning mt-2" style="display: none;">
                    Estás interesado en crédito...
                    <div class="container mt-3">
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <a href="https://www.bancolombia.com/personas/vivienda" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Bancolombia
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.scotiabankcolpatria.com/personas/hipotecario/compra-de-inmuebles/compra-de-vivienda" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Scotiabank
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.bancocajasocial.com/creditos-de-vivienda/credito-hipotecario/" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Banco Caja Social
                                </a>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <a href="https://www.bancopopular.com.co/wps/portal/bancopopular/inicio/para-ti/financiacion-vivienda" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Banco Popular
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.avvillas.com.co/credito-hipotecario" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Banco AV Villas
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.davivienda.com/wps/portal/personas/nuevo#cc644b6e-41c6-4827-8484-267ad854c4b2" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Davivienda
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="https://viviendadigital.bancodebogota.com/" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Banco de Bogotá
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.bbva.com.co/personas/productos/prestamos/vivienda.html" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    BBVA
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="https://www.fna.gov.co/vivienda/Paginas/credito-hipotecario.aspx" target="_blank" class="btn btn-warning-cotizacion w-100">
                                    Fondo Nacional del Ahorro
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario vivienda -->
            <div class="mb-3 mt-4">
                <label for="vivienda" class="form-label">Selecciona tu vivienda:</label>
                <select id="vivienda" class="form-control">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="myr72_70m2" data-precio="200000000">MYR72 - 70m²</option>
                    <option value="myr72_54m2" data-precio="150000000">MYR72 - 54m²</option>
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
            <div class="form-check form-switch">
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

    <a href="{{ route('home') }}" class="btn-home">
        🏠 Inicio
    </a>

    <a href="javascript:void(0);" id="btnImprimirPDF" class="btn-imprimir-pdf">
        🖨️ Imprimir PDF
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
