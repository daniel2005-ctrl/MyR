<?php

namespace App\Http\Controllers;

use App\Models\RedSocial;
use Illuminate\Http\Request;

class RedSocialController extends Controller
{
    public function index() {
        $redes = RedSocial::all();
        return view('admin.redes.index', compact('redes'));
    }

    public function create() {
        return view('admin.redes.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required',
            'url' => 'required|url',
            'icono' => 'required',
        ]);

        RedSocial::create($request->all());
        return redirect()->route('admin.redes.index')->with('success', 'Red social agregada.');
    }

    public function edit(RedSocial $red) {
        return view('admin.redes.edit', compact('red'));
    }

    public function update(Request $request, RedSocial $red) {
        $request->validate([
            'nombre' => 'required',
            'url' => 'required|url',
            'icono' => 'required',
        ]);

        $red->update($request->all());
        return redirect()->route('admin.redes.index')->with('success', 'Red social actualizada.');
    }

    public function destroy(RedSocial $red) {
        $red->delete();
        return back()->with('success', 'Red social eliminada.');
    }
}
