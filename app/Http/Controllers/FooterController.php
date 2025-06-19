<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        // Redirigir al método edit en lugar de duplicar código
        return $this->edit();
    }

    public function edit()
    {
        $footer = Footer::firstOrCreate([], [
            'direccion' => '',
            'gmail_url' => '',
            'whatsapp_url' => '',
            'facebook_url' => '',
            'gmail_icon' => '',
            'whatsapp_icon' => '',
            'facebook_icon' => '',
            'additional_socials' => [], // Remove json_encode(), let Laravel handle the casting
        ]);
        return view('admin.footer.editar', compact('footer'));
    }

    public function update(Request $request)
    {
        $footer = Footer::firstOrCreate([], [
            'direccion' => '',
            'gmail_url' => '',
            'whatsapp_url' => '',
            'facebook_url' => '',
            'gmail_icon' => '',
            'whatsapp_icon' => '',
            'facebook_icon' => '',
            'additional_socials' => [], // Remove json_encode()
        ]);

        $request->validate([
            'direccion' => 'required|string|max:255',
            'gmail_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'gmail_icon' => 'nullable|string|max:50',
            'whatsapp_icon' => 'nullable|string|max:50',
            'facebook_icon' => 'nullable|string|max:50',
            'social_names' => 'nullable|array',
            'social_names.*' => 'required_with:social_urls,social_icons|string|max:100',
            'social_urls' => 'nullable|array',
            'social_urls.*' => 'required_with:social_names,social_icons|url|max:255',
            'social_icons' => 'nullable|array',
            'social_icons.*' => 'required_with:social_names,social_urls|string|max:50',
        ]);

        // Procesar redes sociales adicionales
        $additionalSocials = [];
        if ($request->has('social_names') && is_array($request->social_names)) {
            foreach ($request->social_names as $index => $name) {
                if (!empty($name) && !empty($request->social_urls[$index]) && !empty($request->social_icons[$index])) {
                    $additionalSocials[] = [
                        'name' => $name,
                        'url' => $request->social_urls[$index],
                        'icon' => 'bi bi-' . $request->social_icons[$index],
                    ];
                }
            }
        }

        $footer->update([
            'direccion' => $request->direccion,
            'gmail_url' => $request->gmail_url,
            'whatsapp_url' => $request->whatsapp_url,
            'facebook_url' => $request->facebook_url,
            'gmail_icon' => $request->gmail_icon ? 'bi bi-' . $request->gmail_icon : null,
            'whatsapp_icon' => $request->whatsapp_icon ? 'bi bi-' . $request->whatsapp_icon : null,
            'facebook_icon' => $request->facebook_icon ? 'bi bi-' . $request->facebook_icon : null,
            'additional_socials' => $additionalSocials, // Remove json_encode(), let Laravel handle it
        ]);

        return redirect()->route('footer.editar')->with('success', 'Footer actualizado correctamente.');
    }
}
