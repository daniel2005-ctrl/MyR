<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubsidioCredito;

class SubsidioCreditoController extends Controller
{
    public function index() {
        $subsidiosCreditos = SubsidioCredito::orderBy('orden')->get();
        return view('admin.subsidios_creditos.index', compact('subsidiosCreditos'));
    }
    
    public function create() {
        return view('admin.subsidios_creditos.create');
    }
    
    public function store(Request $request) {
        $request->validate([
            'tipo' => 'required|in:subsidio,credito',
            'nombre' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'orden' => 'nullable|integer|min:1',
        ]);
        
        $data = $request->all();
        $data['activo'] = $request->has('activo');
        
        SubsidioCredito::create($data);
        
        return redirect()->route('subsidios_creditos.index')
                        ->with('success', 'Elemento creado exitosamente.');
    }
    
    public function edit($id) {
        $item = SubsidioCredito::findOrFail($id);
        return view('admin.subsidios_creditos.edit', compact('item'));
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'tipo' => 'required|in:subsidio,credito',
            'nombre' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'orden' => 'nullable|integer|min:1',
        ]);
        
        $item = SubsidioCredito::findOrFail($id);
        $data = $request->all();
        $data['activo'] = $request->has('activo');
        
        $item->update($data);
        
        return redirect()->route('subsidios_creditos.index')
                        ->with('success', 'Elemento actualizado exitosamente.');
    }
    
    public function destroy($id) {
        $item = SubsidioCredito::findOrFail($id);
        $item->delete();
        
        return redirect()->route('subsidios_creditos.index')
                        ->with('success', 'Elemento eliminado exitosamente.');
    }
}