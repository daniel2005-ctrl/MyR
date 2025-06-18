// Objeto principal para manejar la funcionalidad de proyectos
const ProyectosAdmin = {
    // Inicialización
    init() {
        this.setupDeleteConfirmation();
        this.showWelcomeMessage();
    },

    // Configurar confirmación de eliminación
    setupDeleteConfirmation() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar proyecto?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    },

    // Mostrar mensaje de bienvenida
    showWelcomeMessage() {
        const mensaje = localStorage.getItem("loginSuccess");
        const usuario = localStorage.getItem("usuario");

        if (mensaje) {
            Swal.fire({
                icon: "success",
                title: "¡Bienvenido!",
                text: `${mensaje} Hola, ${usuario}`,
                confirmButtonColor: "#ff6600"
            });

            localStorage.removeItem("loginSuccess");
        }
    },

    // Mostrar proyecto más visitado (CORREGIDO)
    mostrarMasVisto() {
        console.log('Datos disponibles:', window.proyectosData); // Debug
        
        if (!window.proyectosData || !window.proyectosData.proyectoMasVisto) {
            document.getElementById('imagen-destacada').innerHTML = 
                '<div class="alert alert-warning">No hay proyecto más visitado disponible.</div>';
            return;
        }
        
        const proyecto = window.proyectosData.proyectoMasVisto;
        console.log('Proyecto más visitado:', proyecto); // Debug
        
        // Obtener la primera imagen del array imagenes_header
        let imagenUrl = '';
        if (typeof proyecto.imagenes_header === 'string' && 
            proyecto.imagenes_header.trim() !== '') {
            // Es un string simple, usar directamente
            imagenUrl = proyecto.imagenes_header;
        } else if (Array.isArray(proyecto.imagenes_header)) {
            imagenUrl = proyecto.imagenes_header[0] || '';
        }
        
        const html = `
            <div class="card" style="max-width: 250px;">
                <img src="${imagenUrl.includes('cloudinary.com') ? imagenUrl : '/imagenes/' + imagenUrl}" class="card-img-top" alt="${proyecto.nombre_pro}" style="height: 150px; object-fit: cover;">
                <div class="card-body p-2">
                    <h6 class="card-title">${proyecto.nombre_pro}</h6>
                    <small class="text-muted">${proyecto.visitas} visitas</small>
                    <br>
                    <button class="btn btn-success btn-sm mt-2" onclick="ProyectosAdmin.aplicarFondoMasVisto()" id="btn-mas-visitado">Usar como fondo</button>
                </div>
            </div>
        `;
        
        document.getElementById('imagen-destacada').innerHTML = html;
    },

  aplicarFondoMasVisto() {
        console.log('Iniciando aplicarFondoMasVisto');
        console.log('URL:', window.proyectosData.routes.usarProyectoMasVisitado);
        
        fetch(window.proyectosData.routes.usarProyectoMasVisitado, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Éxito, recargando página...');
                window.location.reload(true);
            } else {
                console.log('Error en respuesta:', data.message);
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al aplicar el fondo.',
                icon: 'error'
            });
        });
    },

    // Mostrar selector de proyecto (CORREGIDO para usar terminado = 1)
    mostrarSelectorProyecto() {
        console.log('Proyectos activos disponibles:', window.proyectosData?.proyectosActivos); // Debug
        
        if (!window.proyectosData || !window.proyectosData.proyectosActivos || window.proyectosData.proyectosActivos.length === 0) {
            document.getElementById('imagen-destacada').innerHTML = 
                '<div class="alert alert-warning">No hay proyectos activos con imágenes disponibles.<br><small>Verifica que los proyectos tengan terminado=1 e imagenes_header.</small></div>';
            return;
        }
        
        let selectHtml = `
            <div class="card" style="max-width: 300px;">
                <div class="card-body">
                    <h6 class="card-title">Seleccionar Proyecto Activo</h6>
                    <select id="select-proyecto" class="form-select mb-2" onchange="ProyectosAdmin.previsualizarProyecto()">
                        <option value="">-- Selecciona un proyecto --</option>
        `;
        
        window.proyectosData.proyectosActivos.forEach(proyecto => {
            // Procesar imagenes_header
            let imagenUrl = '';
            if (typeof proyecto.imagenes_header === 'string' && proyecto.imagenes_header.trim() !== '') {
                imagenUrl = proyecto.imagenes_header;
            } else if (Array.isArray(proyecto.imagenes_header)) {
                imagenUrl = proyecto.imagenes_header[0] || '';
            }
            
            selectHtml += `<option value="${proyecto.slug}" data-imagen="${imagenUrl}" data-nombre="${proyecto.nombre_pro}">${proyecto.nombre_pro} (${proyecto.visitas} visitas)</option>`;
        });
        
        selectHtml += `
                    </select>
                    <button class="btn btn-success btn-sm" onclick="ProyectosAdmin.aplicarFondoProyecto()" id="btn-aplicar" style="display:none;">Usar como fondo</button>
                    <div id="preview-imagen" class="mt-2"></div>
                </div>
            </div>
        `;
        
        document.getElementById('imagen-destacada').innerHTML = selectHtml;
    },

    // Previsualizar imagen del proyecto seleccionado
    previsualizarProyecto() {
        const select = document.getElementById('select-proyecto');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption.value) {
            document.getElementById('preview-imagen').innerHTML = '';
            document.getElementById('btn-aplicar').style.display = 'none';
            return;
        }
        
        const imagen = selectedOption.dataset.imagen;
        const nombre = selectedOption.dataset.nombre;
        
        const html = `
            <img src="/imagenes/${imagen}" alt="${nombre}" style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">
            <div class="mt-1"><small><strong>${nombre}</strong></small></div>
        `;
        
        document.getElementById('preview-imagen').innerHTML = html;
        document.getElementById('btn-aplicar').style.display = 'inline-block';
    },

    // Aplicar fondo del proyecto seleccionado
    aplicarFondoProyecto() {
        const select = document.getElementById('select-proyecto');
        const slug = select.value;
        
        if (!slug) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor selecciona un proyecto'
            });
            return;
        }
        
        const btn = document.getElementById('btn-aplicar');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Aplicando...';
        }
        
        const url = window.proyectosData.routes.cambiarFondoProyecto.replace('PLACEHOLDER', slug);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    confirmButtonColor: '#28a745',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        // Forzar recarga completa
                        window.location.href = window.location.href;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al cambiar el fondo'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Usar como fondo';
            }
        });
    },

    // Mostrar formulario para subir imagen personalizada
    mostrarSubirImagen() {
        const html = `
            <div class="card" style="max-width: 300px;">
                <div class="card-body">
                    <h6 class="card-title">Subir Imagen Personalizada</h6>
                    <form id="form-subir-personalizada" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <div class="mb-2">
                            <input type="file" name="imagen_personalizada" accept="image/*" required class="form-control" id="imagen-input">
                        </div>
                        <div id="preview-personalizada" class="mb-2"></div>
                        <button type="submit" class="btn btn-primary btn-sm">Subir y usar como fondo</button>
                    </form>
                </div>
            </div>
        `;
        
        document.getElementById('imagen-destacada').innerHTML = html;
        
        // Agregar preview de imagen
        document.getElementById('imagen-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-personalizada').innerHTML = 
                        `<img src="${e.target.result}" style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Manejar envío del formulario
        document.getElementById('form-subir-personalizada').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Subiendo...';
            
            fetch(window.proyectosData.routes.subirImagenPersonalizada, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al subir la imagen'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Subir y usar como fondo';
            });
        });
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    ProyectosAdmin.init();
});

// Hacer las funciones globales para que funcionen con onclick
window.mostrarMasVisto = () => ProyectosAdmin.mostrarMasVisto();
window.mostrarSelectorProyecto = () => ProyectosAdmin.mostrarSelectorProyecto();
window.mostrarSubirImagen = () => ProyectosAdmin.mostrarSubirImagen();