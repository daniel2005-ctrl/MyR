<div class="card">
    <div class="card-body">
        <h6 class="card-title">Subir Imagen Personalizada</h6>
        <form action="{{ route('admin.cambiar-fondo-personalizado') }}" method="POST" enctype="multipart/form-data" id="form-subir-personalizada">
            @csrf
            <div class="mb-2">
                <input type="file" name="imagen_fondo" accept="image/*" required class="form-control" id="imagen-input-personalizada" onchange="previewImagePersonalizada(this)">
            </div>
            <div id="preview-personalizada" class="mb-2"></div>
            <div id="imagen-warning-personalizada" class="text-warning small mb-2"></div>
            <button type="submit" class="btn btn-primary btn-sm">Subir y usar como fondo</button>
        </form>
    </div>
</div>

<script>
// Función mejorada que simula el comportamiento de las tarjetas de proyectos
function previewImagePersonalizada(input) {
    const file = input.files[0];
    const previewDiv = document.getElementById('preview-personalizada');
    const warningElement = document.getElementById('imagen-warning-personalizada');
    
    // Limpiar contenido previo
    if (previewDiv) previewDiv.innerHTML = '';
    if (warningElement) warningElement.innerHTML = '';
    
    if (!file) {
        return;
    }
    
    // Verificar que el archivo sea una imagen
    if (!file.type.startsWith('image/')) {
        if (warningElement) {
            warningElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Por favor selecciona un archivo de imagen válido.';
        }
        return;
    }
    
    // Crear URL temporal para la imagen (similar a como se manejan las URLs en las tarjetas)
    const imageUrl = URL.createObjectURL(file);
    
    // Crear una imagen temporal para verificar dimensiones
    const img = new Image();
    
    img.onload = function() {
        const width = this.width;
        const height = this.height;
        
        // Verificar si la imagen es HD (al menos 1280x720)
        if (width < 1280 || height < 720) {
            if (warningElement) {
                warningElement.innerHTML = 
                    `<i class="fas fa-exclamation-triangle"></i> Advertencia: La imagen no es HD (${width}x${height}). Se recomienda una resolución mínima de 1280x720 para mejor calidad.`;
            }
            
            // Modificar el formulario para mostrar confirmación antes de enviar
            const form = document.getElementById('form-subir-personalizada');
            if (form) {
                form.onsubmit = function(event) {
                    event.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Continuar con imagen de baja resolución?',
                            text: `La imagen seleccionada tiene una resolución de ${width}x${height}, que es menor a la recomendada (1280x720). ¿Desea continuar de todas formas?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Limpiar el evento antes de enviar
                                form.onsubmit = null;
                                form.submit();
                            }
                        });
                    } else {
                        // Fallback si SweetAlert no está disponible
                        if (confirm(`La imagen tiene baja resolución (${width}x${height}). ¿Continuar?`)) {
                            form.onsubmit = null;
                            form.submit();
                        }
                    }
                };
            }
        } else {
            if (warningElement) {
                warningElement.innerHTML = 
                    `<i class="fas fa-check-circle text-success"></i> La imagen tiene buena resolución (${width}x${height}).`;
            }
            const form = document.getElementById('form-subir-personalizada');
            if (form) {
                form.onsubmit = null;
            }
        }
        
        // Mostrar la vista previa usando el mismo estilo que las tarjetas de proyectos
        if (previewDiv) {
            previewDiv.innerHTML = 
                `<div class="text-center">
                    <div class="card" style="max-width: 300px; margin: 0 auto;">
                        <img src="${imageUrl}" class="card-img-top" alt="Vista previa" style="height: 120px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">Vista previa de la imagen seleccionada</small>
                            <br><small class="text-info">Resolución: ${width}x${height}</small>
                        </div>
                    </div>
                </div>`;
        }
        
        // Limpiar la URL temporal después de un tiempo para liberar memoria
        setTimeout(() => {
            URL.revokeObjectURL(imageUrl);
        }, 60000); // 1 minuto
    };
    
    img.onerror = function() {
        if (warningElement) {
            warningElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error al procesar la imagen.';
        }
        // Limpiar la URL en caso de error
        URL.revokeObjectURL(imageUrl);
    };
    
    // Usar la URL temporal para cargar la imagen
    img.src = imageUrl;
}

// Asegurar que la función esté disponible globalmente
if (typeof window !== 'undefined') {
    window.previewImagePersonalizada = previewImagePersonalizada;
}
</script>