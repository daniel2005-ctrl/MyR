$(document).ready(function() {
    $('#emailResetForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#emailBtn');
        const originalText = submitBtn.text();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true).text('Enviando...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                Swal.fire({
                    title: '¡Enlace enviado!',
                    text: 'Hemos enviado un enlace de recuperación a tu correo electrónico. Revisa tu bandeja de entrada.',
                    icon: 'success',
                    confirmButtonColor: '#ff6600',
                    confirmButtonText: 'Ir al inicio'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = window.appRoutes.proyectosIndex;
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Ocurrió un error al enviar el enlace.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join('\n');
                }
                
                Swal.fire({
                    title: 'Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#ff6600',
                    confirmButtonText: 'Intentar de nuevo'
                });
                
                // Rehabilitar botón
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});