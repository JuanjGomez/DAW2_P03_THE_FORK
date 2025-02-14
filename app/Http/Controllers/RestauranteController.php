<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use App\Models\TipoCocina;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{
    // Mostrar lista de restaurantes
    public function index(){
        $restaurantes = Restaurante::all();
        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $tiposCocina = TipoCocina::all();
        return view('admin.restaurantes.createRestaurante', compact('tiposCocina'));
    }

    // Guardar nuevo restaurante
    public function store(Request $request)
    {
        $request->validate([
            'nombre_r' => 'required|string|max:75|unique:restaurantes',
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'precio_promedio' => 'required|numeric',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'municipio' => 'nullable|string|max:255',
            'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
        ]);

        // Manejar la subida de imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/restaurantes'), $nombreImagen);
        }

        // Crear el restaurante
        Restaurante::create([
            'nombre_r' => $request->nombre_r,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'precio_promedio' => $request->precio_promedio,
            'imagen' => $nombreImagen ?? null,
            'municipio' => $request->municipio,
            'tipo_cocina_id' => $request->tipo_cocina_id,
        ]);

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
