<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;

class RestauranteController extends Controller
{
    // Mostrar lista de restaurantes
    public function index()
    {
        $restaurantes = Restaurante::all();
        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('admin.restaurantes.create');
    }

    // Guardar nuevo restaurante
    public function store(Request $request)
    {
        $request->validate([
            'nombre_r' => 'required|string|max:75|unique:restaurantes',
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'precio_promedio' => 'required|numeric',
            'imagen' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
        ]);

        Restaurante::create($request->all());

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante creado con éxito.');
    }

    // Mostrar formulario de edición
    public function edit(Restaurante $restaurante)
    {
        return view('admin.restaurantes.edit', compact('restaurante'));
    }

    // Actualizar restaurante
    public function update(Request $request, Restaurante $restaurante)
    {
        $request->validate([
            'nombre_r' => 'required|string|max:75|unique:restaurantes,nombre_r,' . $restaurante->id,
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'precio_promedio' => 'required|numeric',
            'imagen' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
        ]);

        $restaurante->update($request->all());

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante actualizado con éxito.');
    }

    // Eliminar restaurante
    public function destroy(Restaurante $restaurante)
    {
        $restaurante->delete();

        return redirect()->route('restaurantes.index')->with('success', 'Restaurante eliminado con éxito.');
    }
}
