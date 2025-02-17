<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use App\Models\TipoCocina;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RestauranteController extends Controller
{
    // Mostrar lista de restaurantes
    public function index(Request $request)
    {
        $query = Restaurante::query();

        // Filtro por nombre
        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombre_r', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por tipo de comida
        if ($request->has('tipo_comida') && $request->tipo_comida != '') {
            $query->where('tipo_comida', 'like', '%' . $request->tipo_comida . '%');
        }

        // Filtro por precio
        if ($request->has('precio') && $request->precio != '') {
            $query->where('precio_promedio', '<=', $request->precio);
        }

        // Filtro por municipio
        if ($request->has('municipio') && $request->municipio != '') {
            $query->where('municipio', $request->municipio);
        }

        $restaurantes = $query->paginate(10);
        $municipios = Restaurante::distinct()->pluck('municipio');
        $tiposCocina = TipoCocina::all();

        return view('admin.restaurantes.index', compact('restaurantes', 'municipios', 'tiposCocina'));
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
        DB::beginTransaction();
        try {
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

            DB::commit();
            return redirect()->route('restaurantes.index')->with('success', 'Restaurante creado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Error al crear el restaurante. Por favor, inténtelo más tarde.');
        }
    }

    // Mostrar formulario de edición
    public function edit(Restaurante $restaurante)
    {
        $tiposCocina = TipoCocina::all();
        return view('admin.restaurantes.edit', compact('restaurante', 'tiposCocina'));
    }

    // Actualizar restaurante
    public function update(Request $request, Restaurante $restaurante)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'nombre_r' => 'required|string|max:75|unique:restaurantes,nombre_r,' . $restaurante->id,
                'descripcion' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'precio_promedio' => 'required|numeric',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'municipio' => 'nullable|string|max:255',
                'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
            ]);

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/restaurantes'), $nombreImagen);
                $restaurante->imagen = $nombreImagen;
            }

            $restaurante->update($request->except('imagen'));

            DB::commit();
            return redirect()->route('restaurantes.index')->with('success', 'Restaurante actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Error al actualizar el restaurante. Por favor, inténtelo más tarde.');
        }
    }

    // Eliminar restaurante
    public function destroy(Restaurante $restaurante)
    {
        DB::beginTransaction();
        try {
            $restaurante->delete();
            DB::commit();
            return redirect()->route('restaurantes.index')->with('success', 'Restaurante eliminado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Error al eliminar el restaurante. Por favor, inténtelo más tarde.');
        }
    }
}
