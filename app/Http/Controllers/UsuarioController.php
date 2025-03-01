<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurante;
use App\Models\Rating;

class UsuarioController extends Controller
{
    // Mostrar lista de usuarios
    public function index(Request $request)
    {
        $query = Usuario::with('rol');

        if ($request->has('username') && $request->username != '') {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('rol_id') && $request->rol_id != '') {
            $query->where('rol_id', $request->rol_id);
        }

        $usuarios = $query->paginate(10);
        $roles = Rol::all();

        if ($request->ajax()) {
            return view('admin.usuarios._usuarios_list', compact('usuarios'));
        }

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
            $request->validate([
                'username' => 'required|string|max:30|unique:usuarios',
                'email' => 'required|email|max:120|unique:usuarios',
                'password' => 'required|string|min:8|confirmed',
                'rol_id' => 'required|exists:roles,id',
            ]);

            $usuario = Usuario::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => $request->rol_id,
            ]);

            // Cargar la relación del rol para la respuesta
            $usuario->load('rol');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado con éxito',
                'data' => $usuario
            ]);
    }

    // Mostrar formulario de edición
    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, Usuario $usuario)
    {
            $request->validate([
                'username' => 'required|string|max:30|unique:usuarios,username,' . $usuario->id,
                'email' => 'required|email|max:120|unique:usuarios,email,' . $usuario->id,
                'password' => 'nullable|string|min:8|confirmed',
                'rol_id' => 'required|exists:roles,id',
            ]);

            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'rol_id' => $request->rol_id,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $usuario->update($data);
            
            // Cargar la relación del rol para la respuesta
            $usuario->load('rol');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado con éxito',
                'data' => $usuario
            ]);
    }

    // Eliminar usuario
    public function destroy(Usuario $usuario)
    {
        DB::beginTransaction();
        try {
            // Eliminar valoraciones y comentarios del usuario
            Rating::where('user_id', $usuario->id)->delete();

            // Actualizar los restaurantes que tienen este usuario como manager
            Restaurante::where('manager_id', $usuario->id)
                ->update(['manager_id' => null]);

            // Eliminar el usuario
            $usuario->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
