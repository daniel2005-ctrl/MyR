@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1>Editar Footer</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>¡Oops! Hay algunos errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('footer.update') }}" method="POST" class="w-100" style="max-width: 800px;" id="footerForm">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $footer->direccion) }}" required>
        </div>

        <h4 class="mb-3">Redes Sociales</h4>

        {{-- Gmail Section --}}
        <div class="card mb-3" id="gmail-section">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-envelope"></i> Gmail</h6>
                @if($footer->gmail_url || $footer->gmail_icon)
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearGmailFields()">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gmail_url" class="form-label">Gmail URL</label>
                        <input type="url" name="gmail_url" id="gmail_url" class="form-control" value="{{ old('gmail_url', $footer->gmail_url) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gmail_icon" class="form-label">Icono Gmail (clase Bootstrap Icons)</label>
                        <input type="text" name="gmail_icon" id="gmail_icon" class="form-control" placeholder="Ejemplo: bi-envelope" value="{{ old('gmail_icon', $footer->gmail_icon) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- WhatsApp Section --}}
        <div class="card mb-3" id="whatsapp-section">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-whatsapp"></i> WhatsApp</h6>
                @if($footer->whatsapp_url || $footer->whatsapp_icon)
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearWhatsappFields()">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_url" class="form-label">WhatsApp URL</label>
                        <input type="url" name="whatsapp_url" id="whatsapp_url" class="form-control" value="{{ old('whatsapp_url', $footer->whatsapp_url) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_icon" class="form-label">Icono WhatsApp (clase Bootstrap Icons)</label>
                        <input type="text" name="whatsapp_icon" id="whatsapp_icon" class="form-control" placeholder="Ejemplo: bi-whatsapp" value="{{ old('whatsapp_icon', $footer->whatsapp_icon) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Facebook Section --}}
        <div class="card mb-3" id="facebook-section">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-facebook"></i> Facebook</h6>
                @if($footer->facebook_url || $footer->facebook_icon)
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFacebookFields()">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="facebook_url" class="form-label">Facebook URL</label>
                        <input type="url" name="facebook_url" id="facebook_url" class="form-control" value="{{ old('facebook_url', $footer->facebook_url) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="facebook_icon" class="form-label">Icono Facebook (clase Bootstrap Icons)</label>
                        <input type="text" name="facebook_icon" id="facebook_icon" class="form-control" placeholder="Ejemplo: bi-facebook" value="{{ old('facebook_icon', $footer->facebook_icon) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenedor para redes sociales adicionales --}}
        <div id="additional-socials"></div>

        {{-- Botón para agregar más redes sociales --}}
        <div class="mb-4">
            <button type="button" class="btn btn-outline-primary" onclick="addSocialNetwork()">
                <i class="bi bi-plus-circle"></i> Agregar Red Social
            </button>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="saveBtn">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

{{-- Toast Container --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<script>
let socialCounter = 0;

// Función para mostrar toast notifications
function showToast(message, type = 'success', duration = 4000) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const iconClass = {
        'success': 'bi-check-circle-fill text-success',
        'error': 'bi-exclamation-triangle-fill text-danger',
        'warning': 'bi-exclamation-triangle-fill text-warning',
        'info': 'bi-info-circle-fill text-info'
    };
    
    const bgClass = {
        'success': 'bg-success',
        'error': 'bg-danger', 
        'warning': 'bg-warning',
        'info': 'bg-info'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-white ${bgClass[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${iconClass[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: duration
    });
    
    toast.show();
    
    // Remover el toast del DOM después de que se oculte
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Función para mostrar modal de confirmación personalizado
function showConfirmModal(title, message, onConfirm, onCancel = null) {
    const modalId = 'confirmModal-' + Date.now();
    
    const modalHtml = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">
                            <i class="bi bi-question-circle text-warning me-2"></i>
                            ${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">${message}</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn-${modalId}">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmBtn-${modalId}">
                            <i class="bi bi-check-circle me-1"></i>Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    
    document.getElementById(`confirmBtn-${modalId}`).addEventListener('click', function() {
        modal.hide();
        if (onConfirm) onConfirm();
    });
    
    document.getElementById(`cancelBtn-${modalId}`).addEventListener('click', function() {
        modal.hide();
        if (onCancel) onCancel();
    });
    
    // Limpiar el modal del DOM después de ocultarlo
    document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
        document.getElementById(modalId).remove();
    });
    
    modal.show();
}

function clearGmailFields() {
    showConfirmModal(
        'Eliminar información de Gmail',
        '¿Estás seguro de que quieres eliminar toda la información de Gmail? Esta acción no se puede deshacer.',
        function() {
            document.getElementById('gmail_url').value = '';
            document.getElementById('gmail_icon').value = '';
            showToast('Información de Gmail eliminada correctamente', 'success');
        }
    );
}

function clearWhatsappFields() {
    showConfirmModal(
        'Eliminar información de WhatsApp',
        '¿Estás seguro de que quieres eliminar toda la información de WhatsApp? Esta acción no se puede deshacer.',
        function() {
            document.getElementById('whatsapp_url').value = '';
            document.getElementById('whatsapp_icon').value = '';
            showToast('Información de WhatsApp eliminada correctamente', 'success');
        }
    );
}

function clearFacebookFields() {
    showConfirmModal(
        'Eliminar información de Facebook',
        '¿Estás seguro de que quieres eliminar toda la información de Facebook? Esta acción no se puede deshacer.',
        function() {
            document.getElementById('facebook_url').value = '';
            document.getElementById('facebook_icon').value = '';
            showToast('Información de Facebook eliminada correctamente', 'success');
        }
    );
}

function addSocialNetwork() {
    socialCounter++;
    const container = document.getElementById('additional-socials');
    
    const socialCard = document.createElement('div');
    socialCard.className = 'card mb-3';
    socialCard.id = `social-${socialCounter}`;
    
    socialCard.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-globe"></i> 
                <input type="text" name="social_names[]" placeholder="Nombre de la red social" class="form-control d-inline-block" style="width: auto; display: inline-block !important; margin-left: 10px;" required>
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSocialNetwork(${socialCounter})">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">URL de la Red Social</label>
                    <input type="url" name="social_urls[]" class="form-control" placeholder="https://ejemplo.com" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Icono (clase Bootstrap Icons)</label>
                    <input type="text" name="social_icons[]" class="form-control" placeholder="Ejemplo: bi-instagram, bi-twitter, bi-linkedin" required>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <small class="text-muted">
                        <strong>Ejemplos de iconos:</strong> bi-instagram, bi-twitter, bi-linkedin, bi-youtube, bi-tiktok, bi-telegram, bi-discord, bi-github
                    </small>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(socialCard);
    showToast('Nueva red social agregada', 'info', 2000);
}

function removeSocialNetwork(id) {
    showConfirmModal(
        'Eliminar red social',
        '¿Estás seguro de que quieres eliminar esta red social? Esta acción no se puede deshacer.',
        function() {
            const element = document.getElementById(`social-${id}`);
            if (element) {
                element.remove();
                showToast('Red social eliminada correctamente', 'success');
            }
        }
    );
}

// Validación del formulario y confirmación de guardado
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('footerForm');
    const saveBtn = document.getElementById('saveBtn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const socialNames = document.querySelectorAll('input[name="social_names[]"]');
        const socialUrls = document.querySelectorAll('input[name="social_urls[]"]');
        const socialIcons = document.querySelectorAll('input[name="social_icons[]"]');
        
        // Validar que todos los campos de redes sociales adicionales estén completos
        for (let i = 0; i < socialNames.length; i++) {
            if (!socialNames[i].value.trim() || !socialUrls[i].value.trim() || !socialIcons[i].value.trim()) {
                showToast('Por favor, completa todos los campos de las redes sociales adicionales o elimina las secciones vacías.', 'error', 6000);
                return;
            }
        }
        
        // Mostrar confirmación antes de guardar
        showConfirmModal(
            'Guardar cambios',
            '¿Estás seguro de que quieres guardar todos los cambios realizados en el footer?',
            function() {
                // Mostrar loading en el botón
                saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
                saveBtn.disabled = true;
                
                // Enviar el formulario
                form.submit();
            }
        );
    });
});
</script>

<style>
.card-header input.form-control {
    width: 200px !important;
    display: inline-block !important;
    margin-left: 10px;
    font-size: 0.9rem;
    padding: 0.25rem 0.5rem;
}

.card-header h6 {
    display: flex;
    align-items: center;
    margin: 0;
}

#additional-socials .card {
    border-left: 4px solid #6c757d;
}

.text-muted {
    font-size: 0.85rem;
}

.toast {
    min-width: 300px;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px 15px 0 0;
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endsection
