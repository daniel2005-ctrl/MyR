document.addEventListener("DOMContentLoaded", function () {
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
      Swal.fire({
        icon: "error",
        title: "Error al iniciar sesión",
        text: data.message,
        confirmButtonColor: "#ff6600"
      });
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




  // ---- REGISTRO ----
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(registerForm);

    fetch("/register", {
      method: "POST",
      headers: {
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        "Accept": "application/json"
      },
      body: formData,
      credentials: "include" // 🔥 NECESARIO para mantener sesión
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
          showSection(loginFormContainer);  // Mostrar el formulario de login
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
    .catch(err => {
      console.error("Error en registro:", err);
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
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
          ${estaEnAdmin ? '<a href="/admin">Panel de Administrador</a>' : ''}
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
});