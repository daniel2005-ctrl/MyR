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

  // CSRF
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
      credentials: "include" // 🔥 NECESARIO para mantener sesión
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {

        console.log("gdcgfdgfgfg", data);
        // Si es admin, redirigimos al dashboard de admin
        if (data.es_admin) {
          window.location.href = "/admin";


          return;
        }

        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: `Hola ${data.usuario}, has iniciado sesión correctamente.`,
          confirmButtonText: "Continuar"
        }).then(() => {
          localStorage.setItem("usuario", data.usuario);
          cerrarModal();
          mostrarMenuUsuario(data.usuario);
        });
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

  // ---- REGISTER ----
  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = registerForm.querySelector("button[type='submit']");
    btn.disabled = true;

    // Creamos FormData e incluimos el checkbox es_admin
    const formData = new FormData(registerForm);
    const esAdmin = registerForm.querySelector("input[name='es_admin']")?.checked ? 1 : 0;
    formData.append('es_admin', esAdmin);

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
          text: "Ahora puedes iniciar sesión.",
          confirmButtonText: "Continuar"
        }).then(() => {
          cerrarModal();
          registerForm.reset();
          showSection(loginFormContainer);
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error al registrar",
          text: data.message,
          confirmButtonText: "#ff6600"
        });
      }
    })
    .catch(err => {
      console.error("Error en registro:", err);
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
        text: "No se pudo conectar con el servidor.",
        confirmButtonText: "Intentar de nuevo"
      });
    })
    .finally(() => {
      btn.disabled = false;
    });
  });

  // ---- RESET PASSWORD ----
  resetForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = resetForm.querySelector("button[type='submit']");
    btn.disabled = true;

    const email = resetForm.querySelector("input[name='email']").value;

    fetch("/password/email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        "Accept": "application/json"
      },
      body: JSON.stringify({ email }),
      credentials: "include"
    })
    .then(async res => {
      const data = await res.json();
      if (res.ok) {
        Swal.fire({
          icon: "success",
          title: "¡Listo!",
          text: data.status || "Revisa tu correo para el enlace de restablecimiento.",
          confirmButtonText: "Continuar"
        }).then(() => {
          mostrarMenuUsuario(localStorage.getItem("usuario") || "");
          showSection(loginFormContainer);
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.message || data.errors.email[0],
          confirmButtonColor: "#ff6600"
        });
      }
    })
    .catch(err => {
      console.error("Error en reset:", err);
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
        text: "No se pudo conectar con el servidor.",
        confirmButtonText: "Intentar de nuevo"
      });
    })
    .finally(() => {
      btn.disabled = false;
    });
  });

  // Cierra modal
  function cerrarModal() {
    loginModal.style.display   = "none";
    modalOverlay.style.display = "none";
  }

  // Menú de usuario
  function mostrarMenuUsuario(usuario) {
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
      fetch("/logout", {
        method: "POST",
        headers: {
          ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
          "Accept": "application/json"
        },
        credentials: "include"
      }).then(() => {
        localStorage.removeItem("usuario");
        window.location.reload();
      });
    });
  }
});
