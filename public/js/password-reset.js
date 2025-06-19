$(document).ready(function() {
    $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#resetBtn');
        const originalText = submitBtn.text();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.prop('disabled', true).text('Procesando...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Tu contraseña fue actualizada correctamente.',
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
                let errorMessage = 'Ocurrió un error al restablecer la contraseña.';
                
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
    
    // Lógica del session status
    if (window.sessionStatus === 'Contraseña cambiada con éxito.') {
        Swal.fire({
            title: '¡Éxito!',
            text: 'Tu contraseña fue actualizada correctamente.',
            icon: 'success',
            confirmButtonColor: '#ff6600',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = window.appRoutes.login;
            }
        });
    }
});