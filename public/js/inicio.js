document.addEventListener("DOMContentLoaded", function() {
    const loginButton      = document.getElementById("loginButton");
    const loginModal       = document.getElementById("loginModal");
    const modalOverlay     = document.getElementById("modalOverlay");
    const closeModal       = document.getElementById("closeModal");

    const loginFormContainer    = document.getElementById("loginFormContainer");
    const registerFormContainer = document.getElementById("registerFormContainer");
    const resetFormContainer    = document.getElementById("resetFormContainer");

    const switchToRegister      = document.getElementById("switchToRegister");
    const switchToLogin         = document.getElementById("switchToLogin");
    const switchToReset         = document.getElementById("switchToReset");
    const backToLoginFromReset  = document.getElementById("backToLoginFromReset");

    const loginForm      = document.getElementById("loginForm");
    const registerForm   = document.getElementById("registerForm");
    const resetForm      = document.getElementById("resetForm");
    const passwordInput  = document.getElementById("password");

    // CSRF Token
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

    // Función helper para mostrar solo una sección
    function showSection(sec) {
        [loginFormContainer, registerFormContainer, resetFormContainer].forEach(c => {
            if (c) c.style.display = "none";
        });
        sec.style.display = "block";
    }

    // Mostrar menú si ya está logueado
    if (localStorage.getItem("usuario")) {
        mostrarMenuUsuario(localStorage.getItem("usuario"));
    }

    // Verificación rápida de elementos
    if (!loginButton || !loginModal || !modalOverlay || !closeModal) {
        console.error("Faltan elementos del modal en el DOM.");
        return;
    }

    // Abrir modal
    loginButton.addEventListener("click", () => {
        loginModal.style.display   = "flex";
        modalOverlay.style.display = "block";
        showSection(loginFormContainer);
    });

    // Cerrar modal
    closeModal.addEventListener("click", cerrarModal);
    modalOverlay.addEventListener("click", cerrarModal);

    // Switch entre secciones
    if (switchToRegister) {
        switchToRegister.addEventListener("click", e => {
            e.preventDefault();
            showSection(registerFormContainer);
            initializeRegisterForm();
        });
    }
    if (switchToLogin) {
        switchToLogin.addEventListener("click", e => {
            e.preventDefault();
            showSection(loginFormContainer);
        });
    }
    if (switchToReset) {
        switchToReset.addEventListener("click", e => {
            e.preventDefault();
            showSection(resetFormContainer);
        });
    }
    if (backToLoginFromReset) {
        backToLoginFromReset.addEventListener("click", e => {
            e.preventDefault();
            showSection(loginFormContainer);
        });
    }

    // Mostrar/ocultar contraseña
    if (passwordInput) {
        const container = document.createElement("div");
        container.style.position = "relative";
        passwordInput.parentNode.insertBefore(container, passwordInput);
        container.appendChild(passwordInput);

        const toggle = document.createElement("span");
        toggle.textContent = "👁️‍🗨️";
        Object.assign(toggle.style, {
            position: "absolute",
            right: "15px",
            top: "50%",
            transform: "translateY(-50%)",
            cursor: "pointer",
            fontSize: "18px"
        });
        container.appendChild(toggle);

        toggle.addEventListener("click", () => {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggle.textContent = "🔒";
            } else {
                passwordInput.type = "password";
                toggle.textContent = "👁️‍🗨️";
            }
        });
    }

    // ---- LOGIN ----
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(loginForm);

        fetch("/login", {
            method: "POST",
            headers: {
                ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
                "Accept": "application/json"
            },
            body: formData,
            credentials: "include"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Guardamos el usuario y un mensaje en localStorage
                localStorage.setItem("usuario", data.usuario);
                localStorage.setItem("loginSuccess", "¡Has iniciado sesión correctamente!");

                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    cerrarModal();
                    mostrarMenuUsuario(data.usuario);
                }
            } else {
                // Diferentes tipos de error
                if (data.type === 'not_registered') {
                    Swal.fire({
                        icon: "warning",
                        title: "Usuario no registrado",
                        text: data.message,
                        confirmButtonColor: "#ff6600",
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Registrarse"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Cambiar al formulario de registro
                            // Aquí puedes agregar la lógica para mostrar el formulario de registro
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error al iniciar sesión",
                        text: data.message,
                        confirmButtonColor: "#ff6600"
                    });
                }
            }
        })
        .catch(err => {
            console.error("Error en login:", err);
            Swal.fire({
                icon: "error",
                title: "Error inesperado",
                text: "No se pudo conectar con el servidor.",
                confirmButtonColor: "#ff6600"
            });
        });
    });




    // ---- REGISTRO ---- (SINGLE EVENT LISTENER)
    registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(registerForm);
    
        // Si es rol de administrador, validar el código de seguridad
        if (formData.get('tipo_permiso_id') === '1') {
            const securityCodeInput = document.getElementById('admin-security-code') || 
                                      document.getElementById('security-code-input') ||
                                      document.querySelector('input[name="security_code"]');
            if (!securityCodeInput) {
                Swal.fire({
                    icon: "error",
                    title: "Error del sistema",
                    text: "Campo de código de seguridad no encontrado.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
            const inputCode = securityCodeInput.value.trim();
            if (!inputCode) {
                Swal.fire({
                    icon: "error",
                    title: "Código requerido",
                    text: "Por favor ingrese el código de seguridad.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
            // Aquí se compara el código ingresado con el de la base de datos
            try {
                const isValid = await validateSecurityCode(inputCode); // <-- función de codigo_seguridad.js
                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Código de seguridad inválido",
                        text: "El código ingresado no es correcto o ha expirado. Verifique con el administrador.",
                        confirmButtonColor: "#ff6600"
                    });
                    return;
                }
            } catch (error) {
                console.error('Error validando código:', error);
                Swal.fire({
                    icon: "error",
                    title: "Error de validación",
                    text: "No se pudo validar el código. Intente nuevamente.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
        }

        // Alerta de confirmación antes de continuar
    const { isConfirmed } = await Swal.fire({
        title: '¿Deseas crear tu cuenta?',
        html: `
              <p>Debes aceptar los <a href="${rutaTerminos}" target="_blank">Términos y Condiciones</a> antes de continuar.</p>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="aceptoTerminos">
                    <label class="form-check-label" for="aceptoTerminos">Acepto los términos y condiciones</label>
                </div>
            `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const acepto = document.getElementById('aceptoTerminos').checked;
            if (!acepto) {
                Swal.showValidationMessage('Debes aceptar los Términos y Condiciones para continuar.');
            }
            return acepto;
        }
    });
    
        // Continuar con el registro...
        fetch("/register", {
            method: "POST",
            headers: {
                ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
                "Accept": "application/json"
            },
            body: formData,
            credentials: "include"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "¡Registro exitoso!",
                    text: "Tu cuenta ha sido creada correctamente.",
                    confirmButtonText: "Iniciar sesión"
                }).then(() => {
                    showSection(loginFormContainer);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error al registrar",
                    text: data.message,
                    confirmButtonColor: "#ff6600"
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor.",
                confirmButtonColor: "#ff6600"
            });
        });
    });

    // ---- RECUPERACIÓN DE CONTRASEÑA ----
    resetForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(resetForm);

        fetch("/password/email", {
            method: "POST",
            headers: {
                ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
                "Accept": "application/json"
            },
            body: formData,
            credentials: "include"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "¡Recuperación de contraseña!",
                    text: "Hemos enviado un enlace de recuperación a tu correo.",
                    confirmButtonText: "Aceptar"
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message,
                    confirmButtonColor: "#ff6600"
                });
            }
        })
        .catch(err => {
            console.error("Error en recuperación:", err);
            Swal.fire({
                icon: "error",
                title: "Error inesperado",
                text: "No se pudo conectar con el servidor.",
                confirmButtonColor: "#ff6600"
            });
        });
    });

    // Función para inicializar el formulario de registro
    function initializeRegisterForm() {
        const tipoPermisoSelect = document.getElementById('tipo_permiso_id');
        const securityCodeContainer = document.getElementById('security-code-container');
        
        if (tipoPermisoSelect && securityCodeContainer) {
            // Remover listeners previos
            tipoPermisoSelect.removeEventListener('change', handleTipoPermisoChange);
            // Agregar nuevo listener
            tipoPermisoSelect.addEventListener('change', handleTipoPermisoChange);
            
            // Ejecutar inmediatamente para el valor actual
            handleTipoPermisoChange.call(tipoPermisoSelect);
        }
    }
    
    function handleTipoPermisoChange() {
        const securityCodeContainer = document.getElementById('security-code-container');
        if (securityCodeContainer) {
            if (this.value === '1') { // Administrador
                securityCodeContainer.style.display = 'block';
                console.log('Campo de código de seguridad mostrado');
            } else {
                securityCodeContainer.style.display = 'none';
                console.log('Campo de código de seguridad ocultado');
            }
        }
    }

    // Cierra modal
    function cerrarModal() {
        loginModal.style.display   = "none";
        modalOverlay.style.display = "none";
    }

    function mostrarMenuUsuario(usuario) {
        const estaEnAdmin = document.getElementById('admin-page') !== null;

        loginButton.outerHTML = `
            <div id="userMenu" class="user-container">
                <div id="userDropdown" class="user-dropdown">
                    <span id="userIcon">👤 ${usuario}</span>
                    <div id="dropdownContent" class="dropdown-content">
                        <a href="/perfil">Editar Perfil</a>
                        <a href="#" id="logoutButton">Cerrar Sesión</a>
                    </div>
                </div>
            </div>`;

        document.getElementById("userIcon").addEventListener("click", () => {
            const dd = document.getElementById("dropdownContent");
            dd.style.display = dd.style.display === "block" ? "none" : "block";
        });

        document.getElementById("logoutButton").addEventListener("click", () => {
            fetch("/custom-logout", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    localStorage.removeItem("usuario");
                    window.location.href = data.redirect;
                } else {
                    console.error("Error al cerrar sesión");
                }
            })
            .catch(err => {
                console.error("Error en logout:", err);
            });
        });
    }
});


    // Event listener para el tipo de permiso
    document.addEventListener('DOMContentLoaded', function() {
        const tipoPermisoSelect = document.getElementById('tipo_permiso_id');
        const securityCodeContainer = document.getElementById('security-code-container');
        
        if (tipoPermisoSelect && securityCodeContainer) {
            tipoPermisoSelect.addEventListener('change', function() {
                if (this.value === '1') { // Administrador
                    securityCodeContainer.style.display = 'block';
                } else {
                    securityCodeContainer.style.display = 'none';
                }
            });
        }
    });
    // Función para inicializar el formulario de registro
    function initializeRegisterForm() {
        if (!tipoPermisoSelect || !securityCodeContainer) {
            console.error('Elementos del formulario de registro no encontrados');
            return;
        }
    
        // Mostrar/ocultar el campo de código de seguridad según el tipo de cuenta
        tipoPermisoSelect.addEventListener('change', function() {
            console.log('Tipo de permiso seleccionado:', this.value);
            if (this.value === '1') { // Administrador
                securityCodeContainer.style.display = 'block';
                console.log('Campo de código de seguridad mostrado');
            } else {
                securityCodeContainer.style.display = 'none';
                console.log('Campo de código de seguridad ocultado');
            }
        });
    }
    
    // Inicializar cuando se muestra el formulario de registro
    if (switchToRegister) {
        switchToRegister.addEventListener('click', function() {
            setTimeout(initializeRegisterForm, 100); // Pequeño delay para asegurar que el DOM esté listo
        });
    }
    
    // Modificar el evento submit del formulario de registro
    registerForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(registerForm);
    
        // Si es rol de administrador, validar el código de seguridad
        if (formData.get('tipo_permiso_id') === '1') {
            const securityCodeInput = document.getElementById('admin-security-code');
            
            if (!securityCodeInput) {
                Swal.fire({
                    icon: "error",
                    title: "Error del sistema",
                    text: "Campo de código de seguridad no encontrado.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
            
            const inputCode = securityCodeInput.value.trim();
            
            if (!inputCode) {
                Swal.fire({
                    icon: "error",
                    title: "Código requerido",
                    text: "Por favor ingrese el código de seguridad.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
            
            // Mostrar loading mientras valida
            Swal.fire({
                title: 'Validando código...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Validación asíncrona del código
            const isValid = await validateSecurityCode(inputCode);
            Swal.close();
            
            if (!isValid) {
                Swal.fire({
                    icon: "error",
                    title: "Código de seguridad inválido",
                    text: "El código ingresado no es correcto o ha expirado. Verifique con el administrador.",
                    confirmButtonColor: "#ff6600"
                });
                return;
            }
        }
    
        // Continuar con el resto del proceso de registro...
        fetch("/register", {
            method: "POST",
            headers: {
                ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
                "Accept": "application/json"
            },
            body: formData,
            credentials: "include"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "¡Registro exitoso!",
                    text: "Tu cuenta ha sido creada correctamente.",
                    confirmButtonText: "Iniciar sesión"
                }).then(() => {
                    showSection(loginFormContainer);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error al registrar",
                    text: data.message,
                    confirmButtonColor: "#ff6600"
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor.",
                confirmButtonColor: "#ff6600"
            });
        });
    });

    // También inicializar si el formulario de registro está visible
    if (registerFormContainer && registerFormContainer.style.display !== "none") {
        initializeRegisterForm();
    }
    function cerrarModal() {
        loginModal.style.display   = "none";
        modalOverlay.style.display = "none";
    }

 function mostrarMenuUsuario(usuario) {
    const estaEnAdmin = document.getElementById('admin-page') !== null;

    loginButton.outerHTML = `
        <div id="userMenu" class="user-container">
            <div id="userDropdown" class="user-dropdown">
                <span id="userIcon">👤 ${usuario}</span>
                <div id="dropdownContent" class="dropdown-content">
                    <a href="/perfil">Editar Perfil</a>
                    <a href="#" id="logoutButton">Cerrar Sesión</a>
                </div>
            </div>
        </div>`;

    document.getElementById("userIcon").addEventListener("click", () => {
        const dd = document.getElementById("dropdownContent");
        dd.style.display = dd.style.display === "block" ? "none" : "block";
    });

    


    // ✅ Solo este logout se necesita
    document.getElementById("logoutButton").addEventListener("click", () => {
        fetch("/custom-logout", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            credentials: "include"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                localStorage.removeItem("usuario");
                window.location.href = data.redirect; // Redirección manual
            } else {
                console.error("Error al cerrar sesión");
            }
        })
        .catch(err => {
            console.error("Error en logout:", err);
        });
    });
}

    // En el evento de submit del formulario de registro
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const tipoPermiso = formData.get('tipo_permiso_id');
        
        // Validar código de seguridad solo para administradores
        if (tipoPermiso === '1') {
            const securityCode = formData.get('security_code');
            if (!securityCode) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el código de seguridad para registrarse como administrador.'
                });
                return;
            }
            
            if (!validateSecurityCode(securityCode)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Código Incorrecto',
                    text: 'El código de seguridad ingresado no es válido. Solicite el código actual al administrador.'
                });
                return;
            }
        }
        
        // Continuar con el registro...
        // ... resto del código de registro ...
});
