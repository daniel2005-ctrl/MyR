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
        if ($request->has('social_names') && $request->has('social_urls') && $request->has('social_icons')) {
            $names = $request->social_names;
            $urls = $request->social_urls;
            $icons = $request->social_icons;
            
            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i]) && !empty($urls[$i]) && !empty($icons[$i])) {
                    $additionalSocials[] = [
                        'name' => $names[$i],
                        'url' => $urls[$i],
                        'icon' => $icons[$i]
                    ];
                }
            }
        }

        $footer->update([
            'direccion' => $request->direccion,
            'gmail_url' => $request->gmail_url,
            'whatsapp_url' => $request->whatsapp_url,
            'facebook_url' => $request->facebook_url,
            'gmail_icon' => $request->gmail_icon,
            'whatsapp_icon' => $request->whatsapp_icon,
            'facebook_icon' => $request->facebook_icon,
            'additional_socials' => $additionalSocials, // Remove json_encode(), let Laravel handle it
        ]);

        return redirect()->route('footer.editar')->with('success', 'Footer actualizado correctamente.');
    }
}
