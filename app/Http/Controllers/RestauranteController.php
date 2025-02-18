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

            // Guardar datos antiguos para comparar cambios
            $oldData = $restaurante->toArray();
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

            // Detectar cambios
            $cambios = array_diff_assoc($restaurante->toArray(), $oldData);
            
            // Enviar correo al manager si hay cambios
            if (!empty($cambios) && $restaurante->manager) {
                Mail::to($restaurante->manager->email)
                    ->send(new RestauranteModificado($restaurante, $cambios));
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Restaurante actualizado con éxito',
                'data' => $restaurante
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar restaurante: ' . $e->getMessage());
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
            // Eliminar valoraciones y comentarios del restaurante
            Rating::where('restaurante_id', $restaurante->id)->delete();

            // Liberar al gerente antes de eliminar el restaurante
            if ($restaurante->manager_id) {
                $restaurante->manager_id = null;
                $restaurante->save();
            }

            // Eliminar imagen
            if (file_exists(public_path('images/restaurantes/' . $restaurante->imagen))) {
                unlink(public_path('images/restaurantes/' . $restaurante->imagen));
            }

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
        $query = Restaurante::with(['tipoCocina', 'ratings']);

        // Aplicar filtros existentes
        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombre_r', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('tipo_comida') && $request->tipo_comida != '') {
            $query->whereHas('tipoCocina', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->tipo_comida . '%');
            });
        }

        if ($request->has('precio_min') && $request->precio_min != '') {
            $query->where('precio_promedio', '>=', $request->precio_min);
        }

        if ($request->has('precio_max') && $request->precio_max != '') {
            $query->where('precio_promedio', '<=', $request->precio_max);
        }

        if ($request->has('municipio') && $request->municipio != '') {
            $query->where('municipio', $request->municipio);
        }

        if ($request->has('valoracion_min') && $request->valoracion_min != '') {
            $query->whereHas('ratings', function($q) use ($request) {
                $q->select('restaurante_id')
                  ->groupBy('restaurante_id')
                  ->havingRaw('AVG(rating) >= ?', [$request->valoracion_min]);
            });
        }

        // Aplicar ordenación
        if ($request->has('sort') && $request->has('order')) {
            if ($request->sort === 'rating') {
                $query->withAvg('ratings', 'rating')
                      ->orderBy('ratings_avg_rating', $request->order);
            } else {
                $query->orderBy($request->sort, $request->order);
            }
        }

        $restaurantes = $query->paginate(10);

        return view('partials.restaurant_list', [
            'restaurantes' => $restaurantes->appends($request->all())
        ])->render();
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
