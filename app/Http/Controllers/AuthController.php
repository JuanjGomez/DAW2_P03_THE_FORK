<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\Restaurante;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Muestra el login
    public function showLoginForm(){
        return view('auth.login');
    }

    // Maneja el proceso de login
    public function login(Request $request){
        // 1. Validar los datos del formulario:
        // 1. Validar los datos del formulario:
        // el email debe ser válido y la contraseña no puede estar vacía
        $request->validate([
            'email' => 'required|email|max:120',
            'password' => 'required|max:255',
        ]);
        // Intentar autenticar al usuario con las credenciales proporcionadas
        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (Auth::attempt($request->only('email', 'password'))) {
            // Si las credenciales son válidas, regenera la sesión para protegerla contra ataques de fijación de sesión
            $request->session()->regenerate();
            // Obtener datos del usuario
            // Obtener datos del usuario
            $user = Auth::user();
            $username = $user->username;
            $rol_id = $user->rol_id;
            // Almacenar un mensaje flash en la sesión para mostrarlo en la vista de redirección
            // Almacenar un mensaje flash en la sesión para mostrarlo en la vista de redirección
            session()->flash('success', "Bienvenido $username!");
            // Redirige segun el rol del usuario

            // Redirige segun el rol del usuario
            return ($rol_id == 1) ? redirect()->route('restaurantes.index') : redirect()->route('principal');
        }
        // Si la autenticación falla, redirige de vuelta con un mensaje de error
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
        DB::beginTransaction();
        try {
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
            DB::commit();
            return redirect()->route('principal');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return back()->withErrors([
                'email' => 'Hubo un error al procesar el registro. Por favor, inténtelo más tarde.',
            ]);
        }
    }


    public function Logout(Request $request){
        // Cerrar sesión
        Auth::logout();
        //Invalidar la sesion para evitar que se reutilice
        $request->session()->invalidate();
        // Eliminar la cookie de sesión para que expire
        $request->session()->regenerateToken();
        // Redirigir al usuario al home
        return redirect('/');
    }

    public function showWelcomePage()
    {
        $restaurantes = \App\Models\Restaurante::where('municipio', 'Barcelona')
            ->orderBy('precio_promedio', 'desc')
            ->take(5)
            ->get();

        return view('welcome', ['restaurantes' => $restaurantes]);
    }

    public function showPrincipalPage(Request $request)
    {
        $query = \App\Models\Restaurante::with(['tipoCocina', 'ratings']);

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
                $q->select('restaurante_id');
                $q->groupBy('restaurante_id');
                $q->havingRaw('AVG(rating) >= ?', [$request->valoracion_min]);
            });
        }

        $restaurantes = $query->orderBy('precio_promedio', 'desc')->paginate(10);
        $municipios = \App\Models\Restaurante::distinct()->pluck('municipio');

        return view('principal', [
            'restaurantes' => $restaurantes,
            'municipios' => $municipios
        ]);
    }

    public function showRestaurantePage($id)
    {
        $restaurante = \App\Models\Restaurante::with(['tipoCocina', 'ratings'])->findOrFail($id);
        $userRating = Auth::check() ? $restaurante->ratings()->where('user_id', Auth::id())->first() : null;

        return view('restaurante', [
            'restaurante' => $restaurante,
            'userRating' => $userRating,
        ]);
    }

    public function showPerfilPage()
    {
        return view('perfil');
    }

    public function rateRestaurante(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:500',
        ]);

        $restaurante = Restaurante::findOrFail($id);
        $user = Auth::user();

        $rating = $restaurante->ratings()->where('user_id', $user->id)->first();

        if ($rating) {
            $rating->update([
                'rating' => $request->rating,
                'comentario' => $request->comentario ?? $rating->comentario,
            ]);
        } else {
            $restaurante->ratings()->create([
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comentario' => $request->comentario ?? '',
            ]);
        }

        return redirect()->back()->with('success', 'Gracias por tu valoración!');
    }

    public function updatePerfil(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'username' => 'required|string|max:30',
                'email' => 'required|string|email|max:120|unique:usuarios,email,' . Auth::id(),
            ]);

            $user = Auth::user();
            Usuario::where('id', $user->id)->update([
                'username' => $request->username,
                'email' => $request->email
            ]);

            DB::commit();
            return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo del error
            return back()->withErrors([
                'email' => 'Hubo un error al actualizar el perfil. Por favor, inténtelo más tarde.',
            ]);
        }
    }

    public function filterRestaurants(Request $request)
    {
        $query = \App\Models\Restaurante::with(['tipoCocina', 'ratings']);

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

        // Ordenación
        if ($request->has('sort') && $request->has('order')) {
            if ($request->sort === 'rating') {
                $query->withAvg('ratings', 'rating')
                      ->orderBy('ratings_avg_rating', $request->order);
            } else {
                $query->orderBy($request->sort, $request->order);
            }
        }

        $restaurantes = $query->paginate(10);

        return view('partials.restaurant_list', ['restaurantes' => $restaurantes])->render();
    }
}
