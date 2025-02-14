<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestauranteController;

Route::get('/', [AuthController::class, 'showWelcomePage'])->name('home');

// Rutas de acceso abierto -----------------------------------------------------------------------------------------------

//Ruta formulario de sesion
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Ruta formulario de registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Ruta para salir de sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------------------------------------------------------------------------------------------

// Rutas securizadas ------------------------------------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Principal
    Route::get('/principal', [AuthController::class, 'showPrincipalPage'])->name('principal');
    // Rutas de administración de restaurantes (solo para admins)
    Route::get('/restaurantes', [RestauranteController::class, 'index'])->name('restaurantes.index');
    Route::get('/restaurantes/crear', [RestauranteController::class, 'create'])->name('restaurantes.create');
    Route::post('/restaurantes', [RestauranteController::class, 'store'])->name('restaurantes.store');
    Route::get('/restaurantes/{restaurante}/editar', [RestauranteController::class, 'edit'])->name('restaurantes.edit');
    Route::put('/restaurantes/{restaurante}', [RestauranteController::class, 'update'])->name('restaurantes.update');
});
// ------------------------------------------------------------------------------------------------------------------------

Route::get('/restaurante/{id}', [AuthController::class, 'showRestaurantePage'])->name('restaurante');

Route::get('/perfil', [AuthController::class, 'showPerfilPage'])->name('perfil');

// Ruta para salir de sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::match(['post', 'put'], '/restaurante/{id}/rate', [AuthController::class, 'rateRestaurante'])->name('restaurante.rate');

Route::put('/perfil', [AuthController::class, 'updatePerfil'])->name('perfil.update');
