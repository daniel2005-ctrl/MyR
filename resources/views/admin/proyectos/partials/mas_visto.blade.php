@php
    $proyecto = session('proyecto_mas_visitado');
@endphp

@if(!$proyecto)
    <div class="alert alert-warning">No hay proyecto más visitado disponible.</div>
@else
    <div class="card" style="max-width: 250px;">
        @php
            // Obtener la primera imagen del array imagenes_header
            $imagenUrl = '';
            if (is_string($proyecto->imagenes_header) && trim($proyecto->imagenes_header) !== '') {
                $imagenUrl = $proyecto->imagenes_header;
            } elseif (is_array($proyecto->imagenes_header)) {
                $imagenUrl = $proyecto->imagenes_header[0] ?? '';
            }
            
            // Verificar si es URL de Cloudinary o ruta local
            $imagenSrc = str_contains($imagenUrl, 'cloudinary.com') ? $imagenUrl : '/imagenes/' . $imagenUrl;
        @endphp
        <img src="{{ $imagenSrc }}" class="card-img-top" alt="{{ $proyecto->nombre_pro }}" style="height: 150px; object-fit: cover;">
        <div class="card-body p-2">
            <h6 class="card-title">{{ $proyecto->nombre_pro }}</h6>
            <small class="text-muted">{{ $proyecto->visitas }} visitas</small>
            <br>
            <form action="{{ route('admin.usar-proyecto-mas-visitado') }}" method="POST" id="form-usar-fondo">
                @csrf
                <button type="button" class="btn btn-success btn-sm mt-2" id="btn-usar-fondo" onclick="cerrarDropdownAlEnviar()">Usar como fondo</button>
            </form>
        </div>
    </div>
@endif

<script>
function cerrarDropdownAlEnviar() {
    event.preventDefault();
    
    // Hacer petición AJAX
    fetch('{{ route('admin.usar-proyecto-mas-visitado') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Fondo aplicado correctamente',
            timer: 2000
        });
        
        // Cerrar dropdown y limpiar contenido
        const imagenDestacada = document.getElementById('imagen-destacada');
        if (imagenDestacada) {
            imagenDestacada.innerHTML = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>