<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showWelcomePage'])->name('home');

// Rutas de acceso abierto -----------------------------------------------------------------------------------------------
Route::get('/home', function() {return view('home');})->name('home');

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
});
// ------------------------------------------------------------------------------------------------------------------------

Route::get('/restaurante/{id}', [AuthController::class, 'showRestaurantePage'])->name('restaurante');

Route::get('/perfil', [AuthController::class, 'showPerfilPage'])->name('perfil');

// Ruta para salir de sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::match(['post', 'put'], '/restaurante/{id}/rate', [AuthController::class, 'rateRestaurante'])->name('restaurante.rate');

Route::delete('/rating/{id}', [AuthController::class, 'deleteRating'])->name('rating.delete');
