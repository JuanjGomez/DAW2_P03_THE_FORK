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
    Route::get('/admin/restaurantes', [RestauranteController::class, 'index'])->name('admin.restaurantes.index');
    Route::get('/admin/restaurantes/crear', [RestauranteController::class, 'create'])->name('admin.restaurantes.create');
    Route::post('/admin/restaurantes', [RestauranteController::class, 'store'])->name('admin.restaurantes.store');
    Route::get('/admin/restaurantes/{restaurante}/editar', [RestauranteController::class, 'edit'])->name('admin.restaurantes.edit');
    Route::put('/admin/restaurantes/{restaurante}', [RestauranteController::class, 'update'])->name('admin.restaurantes.update');
    Route::delete('/admin/restaurantes/{restaurante}', [RestauranteController::class, 'destroy'])->name('admin.restaurantes.destroy');
});
// ------------------------------------------------------------------------------------------------------------------------

Route::get('/restaurante/{id}', [AuthController::class, 'showRestaurantePage'])->name('restaurante');

Route::get('/perfil', [AuthController::class, 'showPerfilPage'])->name('perfil');

// Ruta para salir de sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
