<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('imagenes/Logos.png') }}" alt="Logo MYR" style="height: 85px; margin-right: 5px;">
            MYR Proyectos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                {{-- Proyectos --}}
                @if (!request()->fullUrlIs('*#proyectos'))
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#proyectos">Proyectos</a></li>
                @else
                    <li class="nav-item"><span class="nav-link active text-muted">Proyectos</span></li>
                @endif

                {{-- Nosotros --}}
                @if (!request()->is('nosotros'))
                    <li class="nav-item"><a class="nav-link" href="{{ url('nosotros') }}">Nosotros</a></li>
                @else
                    <li class="nav-item"><span class="nav-link active text-muted">Nosotros</span></li>
                @endif

                {{-- Contáctanos --}}
                @if (!request()->fullUrlIs('*#contacto'))
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#contacto">Contáctanos</a></li>
                @else
                    <li class="nav-item"><span class="nav-link active text-muted">Contáctanos</span></li>
                @endif

                {{-- Cotización --}}
                @if (!request()->is('cotizacion'))
                    <li class="nav-item"><a class="nav-link" href="{{ url('cotizacion') }}">Cotizaciones</a></li>
                @else
                    <li class="nav-item"><span class="nav-link active text-muted">Cotizaciones</span></li>
                @endif

                {{-- Login --}}
                <li class="nav-item">
                    <button id="loginButton" class="loginButton bi bi-person">Iniciar Sesión</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="separator"></div>

<!-- Modal -->
<div id="modalOverlay" class="modal-overlay" style="display: none;"></div>

<div id="loginModal" class="modal-container" style="display: none;">
    <div class="modal-content">
        <span class="close-button" id="closeModal">&times;</span>

        <!-- Login -->
        <div id="loginFormContainer">
            <h2>Iniciar Sesión</h2>
            <form id="loginForm">
                <label for="email">Correo:</label>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <br>
                <button type="submit">Iniciar sesión</button>
            </form>

            <div class="mt-2">
                <a href="#" id="switchToReset" class="small">¿Olvidaste tu contraseña?</a>
            </div>
            <p class="mt-2">
                ¿No tienes cuenta? 
                <a href="#" id="switchToRegister">Regístrate aquí</a>
            </p>
        </div>

        <!-- Registro -->
        <div id="registerFormContainer" style="display: none;">
            <h2>Registro</h2>
            <form id="registerForm">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <br>
                <label for="email">Correo:</label>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" placeholder="Contraseña" required>
                <br>
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" required>
                <br>
                <label for="tipo_permiso_id">Tipo de cuenta:</label>
                <select name="tipo_permiso_id" required>
                    <option value="2" selected>Usuario</option>
                    <option value="1">Administrador</option>
                </select>
                  

                <button type="submit">Registrarse</button>
                <p id="mensajeRegistro"></p>
            </form>
            <p>
                ¿Ya tienes cuenta? 
                <a href="#" id="switchToLogin">Inicia sesión aquí</a>
            </p>
        </div>

        <!-- Reset Password -->
        <div id="resetFormContainer" style="display: none;">
            <h2>Recuperar Contraseña</h2>
            <form id="resetForm">
                @csrf
                <div class="mb-2">
                    <label for="reset-email">Correo electrónico:</label>
                    <input id="reset-email" type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
            </form>
            <p class="mt-2">
                <a href="#" id="backToLoginFromReset">Volver al login</a>
            </p>
        </div>

    </div>
</div>

