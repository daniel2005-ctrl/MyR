@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card no-zoom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Gestión de Subsidios y Créditos</h4>
                    <a href="{{ route('subsidios_creditos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Agregar Nuevo
                    </a>
                </div>
                <div class="card-body">
                    @if($subsidiosCreditos->count() > 0)
                        {{-- Sección de Subsidios --}}
                        @php
                            $subsidios = $subsidiosCreditos->where('tipo', 'subsidio')->sortBy('orden');
                        @endphp
                        @if($subsidios->count() > 0)
                            <div class="mb-5">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-house-check"></i> Subsidios
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Orden</th>
                                                <th>Nombre</th>
                                                <th>URL</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subsidios as $item)
                                            <tr>
                                                <td>{{ $item->orden }}</td>
                                                <td>{{ $item->nombre }}</td>
                                                <td>
                                                    <a href="{{ $item->url }}" target="_blank" class="text-decoration-none">
                                                        {{ Str::limit($item->url, 50) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $item->activo ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $item->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('subsidios_creditos.edit', $item->id) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('subsidios_creditos.destroy', $item->id) }}" 
                                                              method="POST" class="d-inline delete-form" 
                                                              data-nombre="{{ $item->nombre }}" 
                                                              data-tipo="subsidio">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Sección de Créditos --}}
                        @php
                            $creditos = $subsidiosCreditos->where('tipo', 'credito')->sortBy('orden');
                        @endphp
                        @if($creditos->count() > 0)
                            <div class="mb-3">
                                <h5 class="text-warning mb-3">
                                    <i class="bi bi-credit-card"></i> Créditos
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-warning">
                                            <tr>
                                                <th>Orden</th>
                                                <th>Nombre</th>
                                                <th>URL</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($creditos as $item)
                                            <tr>
                                                <td>{{ $item->orden }}</td>
                                                <td>{{ $item->nombre }}</td>
                                                <td>
                                                    <a href="{{ $item->url }}" target="_blank" class="text-decoration-none">
                                                        {{ Str::limit($item->url, 50) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $item->activo ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $item->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('subsidios_creditos.edit', $item->id) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('subsidios_creditos.destroy', $item->id) }}" 
                                                              method="POST" class="d-inline delete-form" 
                                                              data-nombre="{{ $item->nombre }}" 
                                                              data-tipo="crédito">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No hay subsidios o créditos registrados.</p>
                            <a href="{{ route('subsidios_creditos.create') }}" class="btn btn-primary">
                                Agregar el primero
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alertas de éxito --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

{{-- Script para confirmación de eliminación --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar eliminación con SweetAlert2
        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const form = this.closest('.delete-form');
                const nombre = form.getAttribute('data-nombre');
                const tipo = form.getAttribute('data-tipo');
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas eliminar el ${tipo} "${nombre}"? Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Eliminando...',
                            text: 'Por favor espera',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Enviar formulario
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection