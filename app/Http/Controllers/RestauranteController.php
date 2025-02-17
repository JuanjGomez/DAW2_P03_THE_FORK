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

class RestauranteController extends Controller
{
    // Mostrar lista de restaurantes
    public function index(Request $request)
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

        if ($request->has('tipo_cocina') && $request->tipo_cocina != '') {
            $query->where('tipo_cocina_id', $request->tipo_cocina);
        }

        $restaurantes = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.restaurantes.partials.restaurant-grid', compact('restaurantes'))->render();
        }

        $municipios = Restaurante::distinct()->pluck('municipio');
        $tiposCocina = TipoCocina::all();
        
        // Managers para el modal de creación (solo los que no tienen restaurante)
        $managersDisponibles = Usuario::where('rol_id', 2)
            ->doesntHave('restaurante')
            ->get();
        
        // Managers para los modales de edición (incluye tanto los disponibles como los ya asignados)
        $managersEdicion = Usuario::where('rol_id', 2)
            ->where(function($query) {
                $query->doesntHave('restaurante')
                      ->orWhereHas('restaurante');
            })
            ->get();

        return view('admin.restaurantes.index', compact('restaurantes', 'municipios', 'tiposCocina', 'managersDisponibles', 'managersEdicion'));
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
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            DB::commit();
            session()->flash('success', 'Restaurante actualizado con éxito');
            return redirect()->route('restaurantes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el restaurante'
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
            // Dentro del método update, modifica la parte de los cambios
            $cambios = [];
            $camposARevisar = ['nombre_r', 'descripcion', 'direccion', 'precio_promedio', 'municipio', 'tipo_cocina_id', 'manager_id'];
            
            foreach ($camposARevisar as $campo) {
                if ($request->has($campo) && $request->$campo != $restaurante->$campo) {
                    if ($campo === 'tipo_cocina_id') {
                        $tipoCocinaAnterior = TipoCocina::find($restaurante->tipo_cocina_id);
                        $tipoCocinaNuevo = TipoCocina::find($request->tipo_cocina_id);
                        $cambios['tipo de cocina'] = [
                            'anterior' => $tipoCocinaAnterior ? $tipoCocinaAnterior->nombre : 'No definido',
                            'nuevo' => $tipoCocinaNuevo ? $tipoCocinaNuevo->nombre : 'No definido'
                        ];
                    } else {
                        $cambios[$campo] = [
                            'anterior' => $restaurante->$campo,
                            'nuevo' => $request->$campo
                        ];
                    }
                }
            }

            // Validación existente...
            $request->validate([
                'nombre_r' => 'required|string|max:75|unique:restaurantes,nombre_r,' . $restaurante->id,
                'descripcion' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'precio_promedio' => 'required|numeric',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'municipio' => 'nullable|string|max:255',
                'tipo_cocina_id' => 'required|exists:tipo_cocina,id',
                'manager_id' => 'nullable|exists:usuarios,id'
            ]);

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/restaurantes'), $nombreImagen);
                $restaurante->imagen = $nombreImagen;
                $cambios['imagen'] = [
                    'anterior' => 'imagen anterior',
                    'nuevo' => 'nueva imagen'
                ];
            }

            $restaurante->update($request->except('imagen'));

            // Justo antes del commit, añade un log para debug
            if (!empty($cambios)) {
                Log::info('Cambios detectados:', $cambios);
                if ($restaurante->manager) {
                    try {
                        Mail::to($restaurante->manager->email)
                            ->send(new RestauranteModificado($restaurante, $cambios));
                        Log::info('Email enviado a: ' . $restaurante->manager->email);
                    } catch (\Exception $e) {
                        Log::error('Error enviando email: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();
            session()->flash('success', 'Restaurante actualizado con éxito');
            return redirect()->route('restaurantes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el restaurante'
            ], 500);
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
}
