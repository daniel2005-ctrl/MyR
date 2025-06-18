<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        @php
        $homeUrl = '/';
        if (Auth::check() && Auth::user()->tipo_permiso_id == 1) {
            $homeUrl = '/admin';
        }
        // Obtener la URL del logo desde configuraciones
        $logoUrl = \App\Models\Configuracion::obtener('logo_navbar', 'imagenes/Logos.png');
        @endphp
        <a class="navbar-brand" href="{{ url($homeUrl) }}">
        <img src="{{ 
            (str_contains($logoUrl, 'cloudinary.com') || str_contains($logoUrl, 'res.cloudinary.com')) 
                ? $logoUrl 
                : asset($logoUrl) 
        }}" alt="Logo MYR" style="height: 85px; margin-right: 5px;">
        MYR Proyectos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                {{-- Proyectos --}}
                @auth
                @if (Auth::user()->tipo_permiso_id == 1)
                {{-- Enlace a Proyectos para Administrador --}}
                @php
                $adminProyectosUrl = url('admin') . '#proyectos';
                @endphp
                
                    @if (!request()->fullUrlIs($adminProyectosUrl))
                        <li class="nav-item"><a class="nav-link" href="{{ $adminProyectosUrl }}">Proyectos (Admin)</a></li>
                    @else
                        <li class="nav-item"><span class="nav-link active text-muted">Proyectos (Admin)</span></li>
                    @endif
                @else
                    {{-- Enlace a Proyectos para Usuario normal --}}
                    @php
                        $userProyectosUrl = url('/') . '#proyectos';
                    @endphp
                
                    @if (!request()->fullUrlIs($userProyectosUrl))
                        <li class="nav-item"><a class="nav-link" href="{{ $userProyectosUrl }}">Proyectos</a></li>
                    @else
                        <li class="nav-item"><span class="nav-link active text-muted">Proyectos</span></li>
                    @endif
                @endif
                
                @else
                {{-- Enlace a Proyectos para visitantes no autenticados --}}
                @php
                $guestProyectosUrl = url('/') . '#proyectos';
                @endphp
                
                @if (!request()->fullUrlIs($guestProyectosUrl))
                    <li class="nav-item"><a class="nav-link" href="{{ $guestProyectosUrl }}">Proyectos</a></li>
                @else
                    <li class="nav-item"><span class="nav-link active text-muted">Proyectos</span></li>
                @endif
                @endauth

                {{-- Servicios --}}

               {{-- Nosotros --}}
@auth
@if (Auth::user()->tipo_permiso_id == 1)
    {{-- Enlace para administrador --}}
    @if (!request()->is('admin/nosotros'))
        <li class="nav-item"><a class="nav-link" href="{{ url('admin/nosotros') }}">Nosotros (Admin)</a></li>
    @else
        <li class="nav-item"><span class="nav-link active text-muted">Nosotros (Admin)</span></li>
    @endif
@else
    {{-- Enlace para usuario normal --}}
    @if (!request()->is('nosotros'))
        <li class="nav-item"><a class="nav-link" href="{{ url('nosotros') }}">Nosotros</a></li>
    @else
        <li class="nav-item"><span class="nav-link active text-muted">Nosotros</span></li>
    @endif
@endif
@else
{{-- Visitante sin sesión --}}
@if (!request()->is('nosotros'))
    <li class="nav-item"><a class="nav-link" href="{{ url('nosotros') }}">Nosotros</a></li>
@else
    <li class="nav-item"><span class="nav-link active text-muted">Nosotros</span></li>
@endif
@endauth

                {{-- Contáctanos --}}
                @auth
                    @if (Auth::user()->tipo_permiso_id != 1)
                        @if (!request()->fullUrlIs('*#contacto'))
                            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#contacto">Contáctanos</a></li>
                        @else
                            <li class="nav-item"><span class="nav-link active text-muted">Contáctanos</span></li>
                        @endif
                    @endif
                @else
                    {{-- Mostrar para visitantes no autenticados --}}
                    @if (!request()->fullUrlIs('*#contacto'))
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#contacto">Contáctanos</a></li>
                    @else
                        <li class="nav-item"><span class="nav-link active text-muted">Contáctanos</span></li>
                    @endif
                @endauth

                {{-- Cotización --}}
                @auth
                    @if (Auth::user()->tipo_permiso_id == 1)
                        {{-- Enlace para administrador - Gestión de Subsidios y Créditos --}}
                        @if (!request()->is('subsidios_creditos*'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('subsidios_creditos.index') }}">Gestión Subsidios/Créditos</a></li>
                        @else
                            <li class="nav-item"><span class="nav-link active text-muted">Gestión Subsidios/Créditos</span></li>
                        @endif
                    @else
                        {{-- Enlace para usuario normal - Cotización normal --}}
                        @if (!request()->is('cotizacion'))
                            <li class="nav-item"><a class="nav-link" href="{{ url('cotizacion') }}">Cotizaciones</a></li>
                        @else
                            <li class="nav-item"><span class="nav-link active text-muted">Cotizaciones</span></li>
                        @endif
                    @endif
                @else
                    {{-- Enlace para visitantes no autenticados - Cotización normal --}}
                    @if (!request()->is('cotizacion'))
                        <li class="nav-item"><a class="nav-link" href="{{ url('cotizacion') }}">Cotizaciones</a></li>
                    @else
                        <li class="nav-item"><span class="nav-link active text-muted">Cotizaciones</span></li>
                    @endif
                @endauth

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
                <select name="tipo_permiso_id" id="tipo_permiso_id" class="form-select transition" required onchange="highlightAccountType(this)">
                    <option value="2" selected>Usuario</option>
                    <option value="1">Administrador</option>
                </select>

                <div id="security-code-container" style="display: none;">
                    <label for="admin-security-code">Código de seguridad:</label>
                    <input type="text" id="admin-security-code" name="security_code" placeholder="Ingrese el código de seguridad">
                    <small class="text-muted d-block mt-1">Solicite el código al administrador actual</small>
                </div>

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

  