<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - MYR Proyectos</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            {{-- Imagen de fondo desde Cloudinary --}}
            background: linear-gradient(135deg, rgba(255, 102, 0, 0.1), rgba(255, 106, 0, 0.1)), 
                        url('https://res.cloudinary.com/dtwtippni/image/upload/v1750113028/cotizaciones_r1ityx.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #ff6600, #ff6a00);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://res.cloudinary.com/dtwtippni/image/upload/v1750112030/proyectos/Logo.png') no-repeat center center;
            background-size: contain;
            opacity: 0.1;
        }
        
        .content {
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #ff6600;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            text-align: center;
            color: #555;
        }
        
        .highlight {
            background: linear-gradient(135deg, #ff6600, #ff6a00);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6600, #ff6a00);
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        }
        
        .button:hover {
            background: linear-gradient(135deg, #e55a00, #e55d00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 102, 0, 0.4);
        }
        
        .footer {
            background: linear-gradient(135deg, #333, #555);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://res.cloudinary.com/dtwtippni/image/upload/v1750113028/cotizaciones_r1ityx.png') no-repeat center center;
            background-size: cover;
            opacity: 0.1;
        }
        
        .social-links {
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }
        
        .social-links a {
            color: #ff6600;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: #ff6a00;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .project-showcase {
            background: rgba(255, 102, 0, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .footer-text {
            margin: 10px 0;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .footer-logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6a00;
            margin-bottom: 10px;
        }
        
        .footer-text {
            font-size: 14px;
            color: #6c757d;
            margin: 5px 0;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ff6a00;
            text-decoration: none;
            font-size: 14px;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .project-showcase {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .accent-border {
            border-left: 4px solid #ff6a00;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🏠 MYR PROYECTOS</h1>
            <p>Construyendo un futuro firme</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">¡Hola {{ $notifiable->nombre ?? 'Usuario' }}!</div>
            
            <div class="message accent-border">
                Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta en <strong>MYR Proyectos</strong>.
            </div>
            
            <div class="highlight">
                🏗️ <strong>Construyendo un futuro firme</strong><br>
                Honestos y confiables desde el inicio
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    🔑 Restablecer Contraseña
                </a>
            </div>
            
            <div class="warning">
                ⏰ <strong>Importante:</strong> Este enlace de restablecimiento expirará en <strong>60 minutos</strong>.
            </div>
            
            <div class="message">
                Si no solicitaste restablecer tu contraseña, no es necesario realizar ninguna acción. Tu cuenta permanece segura.
            </div>
            
            <div class="project-showcase">
                <h3>🏘️ Descubre Nuestros Proyectos</h3>
                <p>Tu hogar ideal te está esperando</p>
                <a href="{{ url('/') }}" style="color: white; text-decoration: underline;">
                    Ver Proyectos Disponibles
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">MYR PROYECTOS</div>
            <div class="footer-text">📧 Contacto: myrproyectosyconstrucciones@gmail.com</div>
            <div class="footer-text">🌐 {{ url('/') }}</div>
            <div class="footer-text">📱 Síguenos en nuestras redes sociales</div>
            
            <div class="social-links">
                {{-- URLs dinámicas desde la base de datos --}}
                @if($footer && $footer->facebook_url)
                    <a href="{{ $footer->facebook_url }}" target="_blank">📘 Facebook</a>
                @endif
                
                @if($footer && $footer->whatsapp_url)
                    <a href="{{ $footer->whatsapp_url }}" target="_blank">📱 WhatsApp</a>
                @endif
                
                @if($footer && $footer->gmail_url)
                    <a href="{{ $footer->gmail_url }}" target="_blank">📧 Gmail</a>
                @endif
                
                {{-- Redes sociales adicionales del campo additional_socials --}}
                @if($footer && $footer->additional_socials)
                    @foreach($footer->additional_socials as $social)
                        @if(isset($social['url']) && isset($social['nombre']))
                            <a href="{{ $social['url'] }}" target="_blank">
                                @if(isset($social['icono']))
                                    {!! $social['icono'] !!}
                                @endif
                                {{ $social['nombre'] }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
            
            <div style="margin-top: 20px; font-size: 12px; color: #999;">
                © {{ date('Y') }} MYR Proyectos. Todos los derechos reservados.
            </div>
        </div>
    </div>
</body>
</html>