@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Panel de Administrador</h1>
    
    <!-- Código de Seguridad y Timer -->
    <div class="alert alert-warning text-center" role="alert">
        <h4>Código de Seguridad para Registro de Administradores</h4>
        <div class="security-info mt-3">
            <div class="code-display">
                <label class="fw-bold">Código Actual:</label>
                <div id="security-code" class="security-code-display fs-3 text-primary fw-bold">------</div>
            </div>
            <div class="timer-display mt-2">
                <label class="fw-bold">Tiempo restante:</label>
                <div id="timer" class="timer-display fs-4 text-danger fw-bold">10:00</div>
            </div>
        </div>
        <small class="d-block mt-2">Comparte este código con usuarios que necesiten registrarse como administradores. El código se regenera automáticamente cada 10 minutos.</small>
    </div>

    <!-- Proyecto más visitado (Tamaño reducido) --> 
    @if(isset($proyectoMasVisto) && $proyectoMasVisto)
    <div class="card mt-3 project-card" style="max-width: 400px; margin: 0 auto;">
        <div class="card-body p-3">
            <h5 class="card-title text-center mb-2">Proyecto Más Visitado</h5>
            <div class="project-details text-center">
                <!-- Imagen del proyecto (tamaño reducido) -->
                <img src="{{ asset($proyectoMasVisto->imagenes_pro[0] ?? 'imagenes/default-image.jpg') }}"
                     alt="Proyecto Más Visitado"
                     class="img-fluid project-image"
                     style="max-height: 120px; width: auto; object-fit: cover;">

                <!-- Nombre del proyecto -->
                <h6 class="project-name mt-2 mb-1">{{ $proyectoMasVisto->nombre_pro }}</h6>

                <!-- Visitas -->
                <p class="project-visitas mb-2 small">Visitas: <span class="visitas-count fw-bold">{{ $proyectoMasVisto->visitas }}</span></p>

                <!-- Enlace al proyecto -->
                <a href="{{ route('proyectos.show', $proyectoMasVisto->slug) }}" class="btn btn-primary btn-sm">Ver Proyecto</a>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info mt-3">No hay proyectos registrados aún.</div>
    @endif
</div>

<!-- Gráfico de estadísticas (tamaño reducido) -->
<div class="card mt-3" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-3">
        <h5 class="card-title text-center mb-2">Visitas por Proyecto</h5>
        <canvas id="visitasChart" height="80" style="max-height: 200px;"></canvas>
    </div>
</div>

<!-- Nueva sección: Clientes del Formulario de Contacto -->
<div class="card mt-4 no-zoom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📧 Formularios de Contacto Recibidos</h5>
        <span class="badge bg-primary">Total: {{ $totalClientes }}</span>
    </div>
    <div class="card-body">
        @if($clientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Proyecto de Interés</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $cliente->created_at->format('d/m/Y') }}<br>
                                    {{ $cliente->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td class="fw-bold">{{ $cliente->nombre }}</td>
                            <td>
                                <a href="tel:{{ $cliente->telefono }}" class="text-decoration-none">
                                    📞 {{ $cliente->telefono }}
                                </a>
                            </td>
                            
                            <td>
                                <a href="mailto:{{ $cliente->email }}" class="text-decoration-none">
                                    ✉️ {{ $cliente->email }}
                                </a>
                            </td>
                            <td>
                                @if($cliente->proyecto)
                                    <span class="badge bg-info">{{ $cliente->proyecto->nombre_pro }}</span>
                                @else
                                    <span class="badge bg-secondary">Sin proyecto</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="mailto:{{ $cliente->email }}?subject=Seguimiento - MyR Constructora&body=Estimado/a {{ $cliente->nombre }},%0A%0AGracias por contactarnos y mostrar interes en nuestros proyectos.%0A%0AEn MyR Constructora nos complace poder brindarle informacion detallada sobre nuestras opciones de vivienda. Nuestro equipo esta disponible para resolver todas sus dudas y acompanarle en el proceso de encontrar su hogar ideal.%0A%0ASaludos cordiales,%0AEquipo MyR Constructora" class="btn btn-outline-primary btn-sm" title="Enviar Email">
                                        ✉️
                                    </a>
                                    <a href="tel:{{ $cliente->telefono }}" class="btn btn-outline-success btn-sm" title="Llamar">
                                        📞
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($totalClientes > 10)
                <div class="text-center mt-3">
                    <small class="text-muted">Mostrando los 10 más recientes de {{ $totalClientes }} total</small>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No hay formularios de contacto recibidos aún.</p>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const proyectos = @json($proyectos);
    const ctx = document.getElementById('visitasChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: proyectos.map(p => p.nombre_pro),
            datasets: [{
                label: 'Visitas',
                data: proyectos.map(p => p.visitas),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
