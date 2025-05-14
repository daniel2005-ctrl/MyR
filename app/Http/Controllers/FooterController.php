<?php

namespace App\Http\Controllers;
use App\Models\FooterInfo;
use App\Models\FooterSocial;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function update(Request $request)
    {
        // Actualizar dirección
        $info = FooterInfo::firstOrCreate([]);
        $info->direccion = $request->input('direccion');
        $info->save();

        // Limpiar y volver a guardar redes sociales
        FooterSocial::truncate();
        foreach ($request->input('socials', []) as $social) {
            FooterSocial::create($social);
        }

        return redirect()->back()->with('success', 'Footer actualizado correctamente.');
    }
}