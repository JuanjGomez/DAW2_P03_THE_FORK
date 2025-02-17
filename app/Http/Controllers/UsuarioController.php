<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $request->validate([
                'username' => 'required|string|max:30|unique:usuarios',
                'email' => 'required|email|max:120|unique:usuarios',
                'password' => 'required|string|min:8|confirmed',
                'rol_id' => 'required|exists:roles,id',
            ]);

            Usuario::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => $request->rol_id,
            ]);

            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Hubo un error al crear el usuario.');
        }
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
        DB::beginTransaction();
        try {
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

            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Hubo un error al actualizar el usuario.');
        }
    }

    // Eliminar usuario
    public function destroy(Usuario $usuario)
    {
        DB::beginTransaction();
        try {
            $usuario->delete();
            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return redirect()->back()->with('error', 'Hubo un error al eliminar el usuario.');
        }
    }
}
