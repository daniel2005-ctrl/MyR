<div class="separator"></div>
<footer class="footer d-flex justify-content-end align-items-center px-4 py-3">
    <div class="text-end">
        <p class="footer-text mb-2">{{ $footer->direccion ?? 'Carrera 20 A Sur Edificio Altos de Rincón De Varsovia' }}</p>
        <div class="footer-icons d-flex justify-content-end gap-3">
            {{-- Gmail --}}
            @if($footer && $footer->gmail_url && $footer->gmail_icon)
                <a href="{{ $footer->gmail_url }}" target="_blank" class="text-decoration-none">
                    <i class="{{ $footer->gmail_icon }}" style="font-size: 1.5rem; color: #ea4335;"></i>
                </a>
            @endif

            {{-- WhatsApp --}}
            @if($footer && $footer->whatsapp_url && $footer->whatsapp_icon)
                <a href="{{ $footer->whatsapp_url }}" target="_blank" class="text-decoration-none">
                    <i class="{{ $footer->whatsapp_icon }}" style="font-size: 1.5rem; color: #25d366;"></i>
                </a>
            @endif

            {{-- Facebook --}}
            @if($footer && $footer->facebook_url && $footer->facebook_icon)
                <a href="{{ $footer->facebook_url }}" target="_blank" class="text-decoration-none">
                    <i class="{{ $footer->facebook_icon }}" style="font-size: 1.5rem; color: #1877f2;"></i>
                </a>
            @endif

            {{-- Redes sociales adicionales --}}
            @if($footer && $footer->additional_socials)
                @php
                    // Asegurar que additional_socials sea un array
                    $additionalSocials = is_array($footer->additional_socials) 
                        ? $footer->additional_socials 
                        : json_decode($footer->additional_socials, true) ?? [];
                @endphp
                
                @foreach($additionalSocials as $social)
                    @if(isset($social['url']) && isset($social['icon']) && isset($social['name']))
                        <a href="{{ $social['url'] }}" target="_blank" class="text-white me-3">
                            <i class="{{ $social['icon'] }}" title="{{ $social['name'] }}"></i>
                        </a>
                    @endif
                @endforeach
            @endif
        </div>

        {{-- Mostrar botón solo si está autenticado y es administrador --}}
        @auth
            @if (Auth::user()->tipo_permiso_id == 1)
                <div class="text-end mt-3">
                    <a href="{{ route('footer.editar') }}" class="btn btn-sm btn-primary shadow-sm" 
                       title="Editar Footer" 
                       style="border-radius: 25px; padding: 8px 16px; transition: all 0.3s ease; background: linear-gradient(135deg, #ff8400 0%, #ff8400 100%); border: none;">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span class="fw-semibold">Editar Footer</span>
                    </a>
                </div>
            @endif
        @endauth
    </div>
</footer>
