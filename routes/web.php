<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // Redirige al nombre de la ruta 'login'
});

Route::middleware(['auth', 'welcome'])->group(function () {
    // Rutas para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/home', [App\Http\Controllers\Parte1Controller::class, 'index'])->name('home'); // Panel principal de Administradores
        Route::get('/NewUser', [App\Http\Controllers\Parte1Controller::class, 'create'])->name('CreateUser');
        Route::post('/CreateUser', [App\Http\Controllers\Parte1Controller::class, 'store'])->name('NewUser');
        Route::get('/users/{id}/edit', [App\Http\Controllers\Parte1Controller::class, 'edit'])->name('editUser');
        Route::delete('/users/{id}', [App\Http\Controllers\Parte1Controller::class, 'destroy'])->name('deleteUser');
        Route::put('/users/{id}', [App\Http\Controllers\Parte1Controller::class, 'update'])->name('updateUser');

        // Rutas de Empleados Inactivos
        Route::get('/users/inactive', [App\Http\Controllers\Parte1Controller::class, 'inactive'])->name('inactiveUsers');
        Route::post('/users/{id}/reactivate', [App\Http\Controllers\Parte1Controller::class, 'reactivate'])->name('reactivateUser');

        Route::post('/validate-user', [App\Http\Controllers\Parte1Controller::class, 'validateUser'])->name('validateUser');

    });

    // Vista para empleados
    Route::middleware(['role:empleado,ejecutivo,admin'])->group(function () {
        Route::get('/welcome', [App\Http\Controllers\Parte3Controller::class, 'index'])->name('welcome');
    });

    Route::get('/Inactive', [App\Http\Controllers\Parte3Controller::class, 'inactive'])->name('inactive');

});

// Ruta para login (accesible solo para invitados)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

// Ruta para logout
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
