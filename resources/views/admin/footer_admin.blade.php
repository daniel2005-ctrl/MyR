@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Redes Sociales</h2>
    <a href="{{ route('admin.redes.create') }}" class="bg-green-500 px-4 py-2 rounded text-white">+ Nueva red</a>

    <table class="mt-4 w-full border">
        <thead><tr><th>Nombre</th><th>URL</th><th>Icono</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($redes as $red)
            <tr>
                <td>{{ $red->nombre }}</td>
                <td><a href="{{ $red->url }}" target="_blank">{{ $red->url }}</a></td>
                <td><i class="{{ $red->icono }}"></i></td>
                <td>
                    <a href="{{ route('admin.redes.edit', $red) }}" class="text-blue-600">Editar</a>
                    <form action="{{ route('admin.redes.destroy', $red) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
