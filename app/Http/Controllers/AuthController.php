<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    // Muestra el login
    public function showLoginForm(){
        return view('auth.login');
    }

    // Maneja el proceso de login
    public function login(Request $request){

        // 1. Validar los datos del formulario:
        // el email debe ser válido y la contraseña no puede estar vacía
        $request->validate([
            'email' => 'required|email|max:120',
            'password' => 'required|max:255',
        ]);

        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (Auth::attempt($request->only('email', 'password'))) {
            // Si las credenciales son válidas, regenera la sesión para protegerla contra ataques de fijación de sesión
            $request->session()->regenerate();

            // Obtener datos del usuario
            $user = Auth::user();
            $username = $user->username;
            $rol_id = $user->rol_id;

            // Almacenar un mensaje flash en la sesión para mostrarlo en la vista de redirección
            session()->flash('success', "Bienvenido $username!");
            
            // Redirige segun el rol del usuario
            return ($rol_id == 1) ? redirect()->route('admin') : redirect()->route('principal');
        }

        // Si la autenticación falla, redirige de vuelta con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
            // Mensaje de error genérico
        ]);
    }

    // Muesta el formulario de registro
    public function showRegisterForm(){
        return view('auth.register');
    }

    // Maneja el proceso de registro
    public function register(Request $request){
        // Validar los datos del formulario
        $request->validate([
            'username' => 'required|string|max:30',
            'email' => 'required|string|email|max:120|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario
        $usuario = Usuario::create([
            'username' => $request->username,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => 3, // Asignacion del rol de standard
        ]);

        $username = $usuario->username;

        // Autenticar al usuario y redirigir
        Auth::login($usuario);
        // Anadir mensaje de extio en la sesion
        session()->flash('success', "Bienvenido $username!");
        return redirect()->route('principal');
    }

    public function Logout(Request $request){
        // Cerrar sesión
        Auth::logout();
        //Invalidar la sesion para evitar que se reutilice
        $request->session()->invalidate();
        // Eliminar la cookie de sesión para que expire
        $request->session()->regenerateToken();
        // Redirigir al usuario al login
        return redirect('home');
    }

    public function showWelcomePage()
    {
        $restaurantes = \App\Models\Restaurante::where('municipio', 'Barcelona')
            ->orderBy('precio_promedio', 'desc')
            ->take(5)
            ->get();

        return view('welcome', ['restaurantes' => $restaurantes]);
    }
}
