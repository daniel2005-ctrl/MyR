@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nosotros_admin.css') }}">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Nosotros</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <form method="POST" action="{{ route('admin.nosotros.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Sección Imagen de Fondo --}}
        <div class="section-box">
            <div class="section-title">Imagen de Fondo</div>
            <label for="imagen_fondo" class="form-label">Sube una imagen (se subirá a Cloudinary)</label>
            <input type="file" name="imagen_fondo" class="form-control" onchange="vistaPrevia(this, 'preview_fondo')">
            
            @if($nosotros?->imagen_fondo)
                <div class="imagen-actual">
                    <img src="{{ $nosotros->imagen_fondo }}" width="200" alt="Actual fondo" class="preview-img">
                    <div class="cloudinary-badge">Cloudinary</div>
                    <button type="button" class="btn-eliminar" onclick="eliminarImagen('imagen_fondo')" title="Eliminar imagen">×</button>
                </div>
                <input type="hidden" name="eliminar_imagen_fondo" id="eliminar_imagen_fondo" value="0">
            @endif
            
            <img id="preview_fondo" width="100" style="display: none;" class="preview-img" alt="Vista previa fondo">
            
            {{-- Imágenes de muestra de Cloudinary --}}
            <div class="mt-3">
                <small class="text-muted">Imágenes de muestra disponibles en Cloudinary (click para usar):</small>
                <div class="imagenes-muestra">
                    @if(isset($imagenesMuestra))
                        @foreach($imagenesMuestra as $imagen)
                            <div class="imagen-muestra" onclick="usarImagenCloudinary('{{ $imagen['ruta'] }}', 'preview_fondo', 'imagen_fondo')" title="{{ $imagen['nombre'] }}">
                                <img src="{{ $imagen['ruta'] }}" alt="{{ $imagen['nombre'] }}">
                                <div class="cloudinary-badge">Cloud</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Sección Imagen Visión --}}
        <div class="section-box">
            <div class="section-title">Imagen Visión</div>
            <label for="imagen_vision" class="form-label">Sube una imagen (se subirá a Cloudinary)</label>
            <input type="file" name="imagen_vision" class="form-control" onchange="vistaPrevia(this, 'preview_vision')">
            
            @if($nosotros?->imagen_vision)
                <div class="imagen-actual">
                    <img src="{{ $nosotros->imagen_vision }}" width="200" alt="Actual visión" class="preview-img">
                    <div class="cloudinary-badge">Cloudinary</div>
                    <button type="button" class="btn-eliminar" onclick="eliminarImagen('imagen_vision')" title="Eliminar imagen">×</button>
                </div>
                <input type="hidden" name="eliminar_imagen_vision" id="eliminar_imagen_vision" value="0">
            @endif
            
            <img id="preview_vision" width="100" style="display: none;" class="preview-img" alt="Vista previa visión">
            
            {{-- Imágenes de muestra de Cloudinary --}}
            <div class="mt-3">
                <small class="text-muted">Imágenes de muestra disponibles en Cloudinary (click para usar):</small>
                <div class="imagenes-muestra">
                    @if(isset($imagenesMuestra))
                        @foreach($imagenesMuestra as $imagen)
                            <div class="imagen-muestra" onclick="usarImagenCloudinary('{{ $imagen['ruta'] }}', 'preview_vision', 'imagen_vision')" title="{{ $imagen['nombre'] }}">
                                <img src="{{ $imagen['ruta'] }}" alt="{{ $imagen['nombre'] }}">
                                <div class="cloudinary-badge">Cloud</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Sección Imagen Misión --}}
        <div class="section-box">
            <div class="section-title">Imagen Misión</div>
            <label for="imagen_mision" class="form-label">Sube una imagen (se subirá a Cloudinary)</label>
            <input type="file" name="imagen_mision" class="form-control" onchange="vistaPrevia(this, 'preview_mision')">
            
            @if($nosotros?->imagen_mision)
                <div class="imagen-actual">
                    <img src="{{ $nosotros->imagen_mision }}" width="200" alt="Actual misión" class="preview-img">
                    <div class="cloudinary-badge">Cloudinary</div>
                    <button type="button" class="btn-eliminar" onclick="eliminarImagen('imagen_mision')" title="Eliminar imagen">×</button>
                </div>
                <input type="hidden" name="eliminar_imagen_mision" id="eliminar_imagen_mision" value="0">
            @endif
            
            <img id="preview_mision" width="100" style="display: none;" class="preview-img" alt="Vista previa misión">
            
            {{-- Imágenes de muestra de Cloudinary --}}
            <div class="mt-3">
                <small class="text-muted">Imágenes de muestra disponibles en Cloudinary (click para usar):</small>
                <div class="imagenes-muestra">
                    @if(isset($imagenesMuestra))
                        @foreach($imagenesMuestra as $imagen)
                            <div class="imagen-muestra" onclick="usarImagenCloudinary('{{ $imagen['ruta'] }}', 'preview_mision', 'imagen_mision')" title="{{ $imagen['nombre'] }}">
                                <img src="{{ $imagen['ruta'] }}" alt="{{ $imagen['nombre'] }}">
                                <div class="cloudinary-badge">Cloud</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Sección: Valores Institucionales --}}
        <div class="section-box">
            <div class="section-title">Valores Institucionales</div>
            
            {{-- Subida individual de imágenes para cada valor --}}
            @php
                $valoresNombres = ['Colaboración', 'Respeto', 'Transparencia', 'Responsabilidad', 'Solidaridad'];
                $valoresImagenes = is_array($nosotros?->valores) ? $nosotros->valores : (is_string($nosotros?->valores) ? explode(',', $nosotros->valores) : []);
            @endphp
            
            @foreach($valoresNombres as $index => $valorNombre)
                <div class="valor-individual mb-4">
                    <h6>{{ $valorNombre }}</h6>
                    <label for="valor_{{ $index }}" class="form-label">Imagen para {{ $valorNombre }}</label>
                    <input type="file" name="valor_{{ $index }}" class="form-control" onchange="vistaPrevia(this, 'preview_valor_{{ $index }}')">
                    
                    @if(isset($valoresImagenes[$index]) && !empty(trim($valoresImagenes[$index])))
                        <div class="imagen-actual">
                            <img src="{{ trim($valoresImagenes[$index]) }}" width="150" alt="{{ $valorNombre }}" class="preview-img">
                            <div class="cloudinary-badge">Cloudinary</div>
                            <button type="button" class="btn-eliminar" onclick="eliminarImagenValor({{ $index }})" title="Eliminar imagen">×</button>
                        </div>
                        <input type="hidden" name="eliminar_valor_{{ $index }}" id="eliminar_valor_{{ $index }}" value="0">
                    @endif
                    
                    <img id="preview_valor_{{ $index }}" width="100" style="display: none;" class="preview-img" alt="Vista previa {{ $valorNombre }}">
                    
                    {{-- Imágenes de muestra de Cloudinary --}}
                    <div class="mt-2">
                        <small class="text-muted">Imágenes de muestra disponibles (click para usar):</small>
                        <div class="imagenes-muestra">
                            @if(isset($imagenesMuestra))
                                @foreach($imagenesMuestra as $imagen)
                                    <div class="imagen-muestra" onclick="usarImagenCloudinaryValor('{{ $imagen['ruta'] }}', {{ $index }})" title="{{ $imagen['nombre'] }}">
                                        <img src="{{ $imagen['ruta'] }}" alt="{{ $imagen['nombre'] }}">
                                        <div class="cloudinary-badge">Cloud</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            
            {{-- Campo oculto para mantener compatibilidad --}}
            <input type="hidden" name="valores" id="valores_hidden" value="{{ is_array($nosotros?->valores) ? implode(', ', $nosotros->valores) : $nosotros?->valores }}">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script>
function vistaPrevia(input, idVista) {
    const file = input.files[0];
    const preview = document.getElementById(idVista);
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}

function eliminarImagen(campo) {
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen de Cloudinary?')) {
        document.getElementById('eliminar_' + campo).value = '1';
        // Ocultar la imagen actual
        const imagenActual = event.target.closest('.imagen-actual');
        imagenActual.style.display = 'none';
    }
}

function usarImagenCloudinary(urlCloudinary, previewId, campoNombre) {
    const preview = document.getElementById(previewId);
    preview.src = urlCloudinary;
    preview.style.display = 'block';
    
    // Crear un input hidden para enviar la URL de Cloudinary seleccionada
    let hiddenInput = document.getElementById('cloudinary_' + campoNombre);
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'cloudinary_' + campoNombre;
        hiddenInput.id = 'cloudinary_' + campoNombre;
        document.querySelector('form').appendChild(hiddenInput);
    }
    hiddenInput.value = urlCloudinary;
}

// Función para eliminar imagen de valor específico
function eliminarImagenValor(index) {
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
        document.getElementById('eliminar_valor_' + index).value = '1';
        // Ocultar la imagen actual
        const imagenActual = event.target.closest('.imagen-actual');
        if (imagenActual) {
            imagenActual.style.display = 'none';
        }
    }
}

// Función para usar imagen de Cloudinary para valores
function usarImagenCloudinaryValor(url, index) {
    // Mostrar en vista previa
    const preview = document.getElementById('preview_valor_' + index);
    preview.src = url;
    preview.style.display = 'block';
    
    // Actualizar el campo oculto valores
    actualizarCampoValores();
}

// Función para actualizar el campo valores con las URLs actuales
function actualizarCampoValores() {
    const valores = [];
    for (let i = 0; i < 5; i++) {
        const preview = document.getElementById('preview_valor_' + i);
        if (preview && preview.src && preview.style.display !== 'none') {
            valores[i] = preview.src;
        } else {
            // Mantener valor existente si no hay cambios
            const valorActual = document.querySelector(`input[name="eliminar_valor_${i}"]`);
            if (valorActual && valorActual.value === '0') {
                const imgActual = document.querySelector(`.imagen-actual img[alt*="${['Colaboración', 'Respeto', 'Transparencia', 'Responsabilidad', 'Solidaridad'][i]}"]`);
                if (imgActual) {
                    valores[i] = imgActual.src;
                }
            }
        }
    }
    document.getElementById('valores_hidden').value = valores.filter(v => v).join(', ');
}
</script>

@endsection