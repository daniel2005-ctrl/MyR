@php
    $proyectosActivos = session('proyectos_activos');
@endphp

@if(!$proyectosActivos || $proyectosActivos->isEmpty())
    <div class="alert alert-warning">No hay proyectos activos con imágenes disponibles.<br><small>Verifica que los proyectos tengan terminado=1 e imagenes_header.</small></div>
@else
    <div class="card" style="max-width: 300px;">
        <div class="card-body">
            <h6 class="card-title">Seleccionar Proyecto Activo</h6>
            <form action="{{ route('admin.cambiar-fondo-proyecto') }}" method="POST">
                @csrf
                <select name="proyecto_id" class="form-select mb-2" id="select-proyecto" onchange="previewProyecto(this)">
                    <option value="">-- Selecciona un proyecto --</option>
                    @foreach($proyectosActivos as $proyecto)
                        <option value="{{ $proyecto->id_pro }}" data-imagen="{{ is_array($proyecto->imagenes_header) ? ($proyecto->imagenes_header[0] ?? '') : $proyecto->imagenes_header }}">{{ $proyecto->nombre_pro }} ({{ $proyecto->visitas }} visitas)</option>
                    @endforeach
                </select>
                <div id="preview-imagen" class="mt-2"></div>
                <button type="submit" class="btn btn-success btn-sm mt-2" id="btn-aplicar" style="display:none;">Usar como fondo</button>
            </form>
        </div>
    </div>
    
    <script>
    function previewProyecto(select) {
        const selectedOption = select.options[select.selectedIndex];
        const previewDiv = document.getElementById('preview-imagen');
        const btnAplicar = document.getElementById('btn-aplicar');
        
        if (!selectedOption.value) {
            previewDiv.innerHTML = '';
            btnAplicar.style.display = 'none';
            return;
        }
        
        const imagen = selectedOption.dataset.imagen;
        const nombre = selectedOption.text;
        
        // Verificar si la imagen es una URL de Cloudinary o una ruta local
        const imagenUrl = imagen.includes('cloudinary.com') ? imagen : `/imagenes/${imagen}`;
        
        previewDiv.innerHTML = `
            <img src="${imagenUrl}" alt="${nombre}" style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">
            <div class="mt-1"><small><strong>${nombre}</strong></small></div>
        `;
        btnAplicar.style.display = 'inline-block';
    }
    </script>
@endif