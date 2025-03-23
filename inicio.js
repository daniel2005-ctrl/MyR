document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.getElementById("loginButton");
    const loginModal = document.getElementById("loginModal");
    const modalOverlay = document.getElementById("modalOverlay");
    const closeModal = document.getElementById("closeModal");

    const loginFormContainer = document.getElementById("loginFormContainer");
    const registerFormContainer = document.getElementById("registerFormContainer");
    const switchToRegister = document.getElementById("switchToRegister");
    const switchToLogin = document.getElementById("switchToLogin");
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    if (localStorage.getItem("usuario")) {
        mostrarMenuUsuario(localStorage.getItem("usuario"));
    }

    if (!loginButton || !loginModal || !modalOverlay || !closeModal || !switchToRegister || !switchToLogin || !loginForm || !registerForm) {
        console.error("Error: Elementos no encontrados.");
        return;
    }

    loginButton.addEventListener("click", function () {
        loginModal.style.display = "flex";
        modalOverlay.style.display = "block";
    });

    closeModal.addEventListener("click", cerrarModal);
    modalOverlay.addEventListener("click", cerrarModal);

    switchToRegister.addEventListener("click", function (event) {
        event.preventDefault();
        loginFormContainer.style.display = "none";
        registerFormContainer.style.display = "block";
    });

    switchToLogin.addEventListener("click", function (event) {
        event.preventDefault();
        registerFormContainer.style.display = "none";
        loginFormContainer.style.display = "block";
    });

    
    function mostrarError(message, type) {
        let errorMensaje = document.getElementById(`errorMensaje-${type}`);
        if (!errorMensaje) {
            errorMensaje = document.createElement("p");
            errorMensaje.id = `errorMensaje-${type}`;
            errorMensaje.style.color = "red";
            errorMensaje.style.fontWeight = "bold";
            if (type === "login") {
                loginForm.appendChild(errorMensaje);
            } else {
                registerForm.appendChild(errorMensaje);
            }
        }
        errorMensaje.innerText = message;
    }
});

    function cerrarModal() {
        loginModal.style.display = "none";
        modalOverlay.style.display = "none";
    }

    function mostrarError(message) {
        let errorMensaje = document.getElementById("errorMensaje");
        if (!errorMensaje) {
            errorMensaje = document.createElement("p");
            errorMensaje.id = "errorMensaje";
            errorMensaje.style.color = "red";
            errorMensaje.style.fontWeight = "bold";
            loginForm.appendChild(errorMensaje);
        }
        errorMensaje.innerText = message;
    }

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

        document.getElementById("userIcon").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdownContent");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        document.getElementById("logoutButton").addEventListener("click", function () {
            localStorage.removeItem("usuario");
            window.location.reload();
        });

        document.addEventListener("click", function (event) {
            const userDropdown = document.getElementById("userDropdown");
            if (!userDropdown.contains(event.target)) {
                document.getElementById("dropdownContent").style.display = "none";
            }
        });
    }

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

    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            let formData = new FormData(loginForm);
            console.log("Enviando datos de login:", Object.fromEntries(formData));

            fetch("login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del login:", data);
                if (data.success) {
                    localStorage.setItem("usuario", data.usuario);
                    cerrarModal();
                    mostrarMenuUsuario(data.usuario);
                } else {
                    mostrarError(data.message, "login");
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
        });
    }

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();
            let formData = new FormData(registerForm);
            console.log("Enviando datos de registro:", Object.fromEntries(formData));

            fetch("registro.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())  // 🔹 Primero obtenemos la respuesta como texto
            .then(text => {
                console.log("Respuesta del servidor (raw):", text);  // 🔹 Ver en consola el texto recibido
                return JSON.parse(text);  // 🔹 Convertimos manualmente a JSON
            })
            .then(data => {
                console.log("Respuesta del registro (JSON):", data);
                if (data.success) {
                    alert("Registro exitoso");
                    registerForm.reset();
                    document.getElementById("switchToLogin").click();
                } else {
                    mostrarError(data.message, "registro");
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
            
        });
    }

    
    

    function cerrarModal() {
        loginModal.style.display = "none";
        modalOverlay.style.display = "none";
    }

    function mostrarError(message, type) {
        let errorMensaje = document.getElementById(`errorMensaje-${type}`);
        if (!errorMensaje) {
            errorMensaje = document.createElement("p");
            errorMensaje.id = `errorMensaje-${type}`;
            errorMensaje.style.color = "red";
            errorMensaje.style.fontWeight = "bold";
            if (type === "login") {
                loginForm.appendChild(errorMensaje);
            } else {
                registerForm.appendChild(errorMensaje);
            }
        }
        errorMensaje.innerText = message;
    }


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

        document.getElementById("userIcon").addEventListener("click", function () {
            const dropdown = document.getElementById("dropdownContent");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        document.getElementById("logoutButton").addEventListener("click", function () {
            localStorage.removeItem("usuario");
            window.location.reload();
        });

        document.addEventListener("click", function (event) {
            const userDropdown = document.getElementById("userDropdown");
            if (!userDropdown.contains(event.target)) {
                document.getElementById("dropdownContent").style.display = "none";
            }
        });
    }
