document.addEventListener("DOMContentLoaded", function () { 
    // Espera a que el DOM esté completamente cargado antes de ejecutar el script.

    const loginButton = document.getElementById("loginButton"); // Botón que abre el modal de inicio de sesión
    const loginModal = document.getElementById("loginModal"); // Modal de inicio de sesión
    const closeModal = document.getElementById("closeModal"); // Botón de cierre del modal
    const loginForm = document.getElementById("loginForm"); // Formulario de inicio de sesión

    // Verifica si hay un usuario guardado en localStorage y muestra su sesión automáticamente
    if (localStorage.getItem("usuario")) {
        mostrarMenuUsuario(localStorage.getItem("usuario"));
    }

    // Verifica que los elementos existan antes de continuar
    if (!loginButton || !loginModal || !closeModal || !loginForm) {
        console.error("Error: Elementos del login no encontrados.");
        return;
    }

    // Evento para mostrar el modal cuando se hace clic en el botón de login
    loginButton.addEventListener("click", function (event) {
        event.stopPropagation();
        loginModal.style.display = "block";
    });

    // Función para cerrar el modal
    function cerrarModal() {
        loginModal.style.display = "none";
        let modalOverlay = document.getElementById("modalOverlay");
        if (modalOverlay) modalOverlay.style.display = "none";
    }

    // Evento para cerrar el modal al hacer clic en el botón de cerrar ("×")
    closeModal.addEventListener("click", cerrarModal);

    // Evento para cerrar el modal si se hace clic fuera de él
    document.addEventListener("click", function (event) {
        if (!loginModal.contains(event.target) && event.target !== loginButton) {
            cerrarModal();
        }
    });

    // Previene que el clic dentro del modal se propague y lo cierre accidentalmente
    loginModal.addEventListener("click", function (event) {
        event.stopPropagation();
    });

    // Manejo del envío del formulario de login
    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(loginForm);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Guardar el usuario en localStorage
                localStorage.setItem("usuario", data.usuario);

                // Ocultar el modal de login
                cerrarModal();

                // Mostrar el menú del usuario
                mostrarMenuUsuario(data.usuario);
            } else {
                let errorMensaje = document.getElementById("errorMensaje");
                if (!errorMensaje) {
                    errorMensaje = document.createElement("p");
                    errorMensaje.id = "errorMensaje";
                    errorMensaje.style.color = "red";
                    errorMensaje.style.fontWeight = "bold";
                    loginForm.appendChild(errorMensaje);
                }
                errorMensaje.innerText = "Usuario o contraseña incorrectos. Intenta de nuevo.";
            }
        })
        .catch(error => console.error("Error:", error));
    });

    // Función para mostrar el menú del usuario si ya está autenticado
    function mostrarMenuUsuario(usuario) {
        loginButton.outerHTML = `
        <div id="userMenu" class="user-container">
            <div id="userDropdown" class="user-dropdown">
                <span id="userIcon">👤 ${usuario}</span>
                <div id="dropdownContent" class="dropdown-content">
                    <a href="perfil.php">Editar Perfil</a>
                    <a href="#" id="logoutButton">Cerrar Sesión</a>
                </div>
            </div>
        </div>`;

        // Agregar eventos al nuevo menú de usuario
        document.getElementById("userIcon").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdownContent");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        document.getElementById("logoutButton").addEventListener("click", function () {
            localStorage.removeItem("usuario"); // Eliminar usuario de localStorage
            window.location.reload(); // Recargar la página para volver al estado original
        });

        // Cierra el menú si se hace clic fuera de él
        document.addEventListener("click", function (event) {
            const userDropdown = document.getElementById("userDropdown");
            if (!userDropdown.contains(event.target)) {
                document.getElementById("dropdownContent").style.display = "none";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const registerLink = document.querySelector("#loginModal a[href='#']"); // Enlace "Regístrate aquí"
    const registerModal = document.getElementById("registerModal"); // Modal de registro
    const closeRegisterModal = document.getElementById("closeRegisterModal"); // Botón de cierre del modal de registro
    
    if (registerLink && registerModal) {
        registerLink.addEventListener("click", function (event) {
            event.preventDefault();
            cerrarModal(); // Cierra el modal de inicio de sesión
            registerModal.style.display = "block"; // Muestra el modal de registro
        });
    }
    
    if (closeRegisterModal) {
        closeRegisterModal.addEventListener("click", function () {
            registerModal.style.display = "none";
        });
    }

    // Cerrar el modal de registro si se hace clic fuera de él
    document.addEventListener("click", function (event) {
        if (!registerModal.contains(event.target) && event.target !== registerLink) {
            registerModal.style.display = "none";
        }
    });

    // Previene que el clic dentro del modal de registro lo cierre accidentalmente
    registerModal.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});


document.addEventListener("DOMContentLoaded", function () {     
    const passwordInput = document.getElementById("password");

    if (passwordInput) {
        const passwordContainer = document.createElement("div");
        passwordContainer.style.position = "relative";
        passwordContainer.style.display = "inline-block";
        passwordContainer.style.width = "100%";

        passwordInput.parentNode.insertBefore(passwordContainer, passwordInput);
        passwordContainer.appendChild(passwordInput);

        const togglePassword = document.createElement("span");
        togglePassword.innerHTML = "👁️‍🗨️";
        togglePassword.style.position = "absolute";
        togglePassword.style.right = "15px";
        togglePassword.style.top = "50%";
        togglePassword.style.transform = "translateY(-50%)";
        togglePassword.style.cursor = "pointer";
        togglePassword.style.fontSize = "18px";

        passwordContainer.appendChild(togglePassword);

        togglePassword.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.innerHTML = "🔒";
            } else {
                passwordInput.type = "password";
                togglePassword.innerHTML = "👁️‍🗨️";
            }
        });
    }

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(loginForm);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.setItem("usuario", data.usuario);
                cerrarModal();
                mostrarMenuUsuario(data.usuario);
            } else {
                let errorMensaje = document.getElementById("errorMensaje");
                if (!errorMensaje) {
                    errorMensaje = document.createElement("p");
                    errorMensaje.id = "errorMensaje";
                    errorMensaje.style.color = "red";
                    errorMensaje.style.fontWeight = "bold";
                    loginForm.appendChild(errorMensaje);
                }
                errorMensaje.innerText = "Usuario o contraseña incorrectos. Intenta de nuevo.";
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
