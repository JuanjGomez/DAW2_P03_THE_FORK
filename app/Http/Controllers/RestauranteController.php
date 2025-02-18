<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use App\Models\TipoCocina;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestauranteModificado;
use Illuminate\Support\Facades\Log;
use App\Models\Rating;

class RestauranteController extends Controller
{
    // Mostrar lista de restaurantes
    public function index(Request $request)
    {
        $restaurantes = $this->applyFilters($request);
        
        if ($request->ajax()) {
            return view('admin.restaurantes._restaurantes_list', compact('restaurantes'));
        }
        
        return view('admin.restaurantes.index', [
            'restaurantes' => $restaurantes,
            'municipios' => $this->getMunicipios(),
            'tiposCocina' => $this->getTiposCocina(),
            'managersDisponibles' => $this->getManagersDisponibles(),
            'managersEdicion' => $this->getManagersEdicion()
        ]);
    }

    // Mostrar formulario de creación
    public function create()
    {
        $tiposCocina = TipoCocina::all();
        $managers = Usuario::where('rol_id', 2)->doesntHave('restaurante')->get();
        return view('admin.restaurantes.createRestaurante', compact('tiposCocina', 'managers'));
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
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'municipio' => 'nullable|string|max:255',
                'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
                'manager_id' => 'nullable|exists:usuarios,id'
            ]);

            // Manejar la subida de imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/restaurantes'), $nombreImagen);
            }

            // Crear el restaurante
            $restaurante = Restaurante::create([
                'nombre_r' => $request->nombre_r,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'precio_promedio' => $request->precio_promedio,
                'imagen' => $nombreImagen ?? null,
                'municipio' => $request->municipio,
                'tipo_cocina_id' => $request->tipo_cocina_id,
                'manager_id' => $request->manager_id
            ]);

            // Cargar las relaciones necesarias
            $restaurante->load(['tipoCocina', 'manager']);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Restaurante creado con éxito',
                'data' => $restaurante
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el restaurante: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mostrar formulario de edición
    public function edit(Restaurante $restaurante)
    {
        $tiposCocina = TipoCocina::all();
        $managers = Usuario::where('rol_id', 2)
            ->where(function($query) use ($restaurante) {
                $query->doesntHave('restaurante')
                      ->orWhere('id', $restaurante->manager_id);
            })
            ->get();
        return view('admin.restaurantes.edit', compact('restaurante', 'tiposCocina', 'managers'));
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
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'municipio' => 'nullable|string|max:255',
                'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
                'manager_id' => 'nullable|exists:usuarios,id'
            ]);

            $data = $request->except('imagen');

            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior
                if ($restaurante->imagen && file_exists(public_path('images/restaurantes/' . $restaurante->imagen))) {
                    unlink(public_path('images/restaurantes/' . $restaurante->imagen));
                }
                
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/restaurantes'), $nombreImagen);
                $data['imagen'] = $nombreImagen;
            }

            $restaurante->update($data);
            $restaurante->load(['tipoCocina', 'manager']);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Restaurante actualizado con éxito',
                'data' => $restaurante
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el restaurante: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar restaurante
    public function destroy(Restaurante $restaurante)
    {
        DB::beginTransaction();
        try {
            // Liberar al gerente antes de eliminar el restaurante
            if ($restaurante->manager_id) {
                $restaurante->manager_id = null;
                $restaurante->save();
            }

            // Eliminar imagen
            if (file_exists(public_path('images/restaurantes/' . $restaurante->imagen))) {
                unlink(public_path('images/restaurantes/' . $restaurante->imagen));
            }

            // Eliminar valoraciones asociadas
            Rating::where('restaurante_id', $restaurante->id)->delete();
            
            // Eliminar restaurante
            $restaurante->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Restaurante eliminado con éxito',
                'data' => ['id' => $restaurante->id]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el restaurante: ' . $e->getMessage()
            ], 500);
        }
    }

    public function filterRestaurants(Request $request)
    {
        $query = Restaurante::query();

        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombre_r', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('tipo_comida') && $request->tipo_comida != '') {
            $query->whereHas('tipoCocina', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->tipo_comida . '%');
            });
        }

        if ($request->has('precio') && $request->precio != '') {
            $query->where('precio_promedio', '<=', $request->precio);
        }

        if ($request->has('municipio') && $request->municipio != '') {
            $query->where('municipio', $request->municipio);
        }

        $restaurantes = $query->paginate(10);

        return view('admin.restaurantes.partials.restaurant-grid', compact('restaurantes'))->render();
    }

    protected function applyFilters(Request $request)
    {
        $query = Restaurante::query();

        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombre_r', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('tipo_comida') && $request->tipo_comida != '') {
            $query->whereHas('tipoCocina', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->tipo_comida . '%');
            });
        }

        if ($request->has('precio') && $request->precio != '') {
            $query->where('precio_promedio', '<=', $request->precio);
        }

        if ($request->has('municipio') && $request->municipio != '') {
            $query->where('municipio', $request->municipio);
        }

        return $query->paginate(10);
    }

    protected function getMunicipios()
    {
        return Restaurante::distinct()->pluck('municipio');
    }

    protected function getTiposCocina()
    {
        return TipoCocina::all();
    }

    protected function getManagersDisponibles()
    {
        return Usuario::where('rol_id', 2)->doesntHave('restaurante')->get();
    }

    protected function getManagersEdicion()
    {
        return Usuario::where('rol_id', 2)->get();
    }
}
