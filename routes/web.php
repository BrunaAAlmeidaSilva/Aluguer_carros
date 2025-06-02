<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    HomeController,
    BemLocavelController,
    UserController,
    ReservaController,
    PagamentoController
};

// Página inicial: Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Página Home (viaturas)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Página Bem Locavel
Route::get('/bens_locaveis', [BemLocavelController::class, 'index'])->name('bens_locaveis.index');
Route::get('/bens_locaveis/search', [BemLocavelController::class, 'search'])->name('bens_locaveis.search');
Route::get('/bens_locaveis/{bem}', [BemLocavelController::class, 'show'])->name('bens_locaveis.show');

// Rotas de autenticação e perfil
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');

    // Dashboard autenticado (opcional, se quiseres dashboard diferente para users autenticados)
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservas
    Route::prefix('reservas')->name('reservas.')->group(function () {
        Route::get('/', [ReservaController::class, 'index'])->name('index');
        Route::get('/criar/{bem}', [ReservaController::class, 'create'])->name('create');
        Route::post('/store', [ReservaController::class, 'store'])->name('store');
        Route::get('/{reserva}', [ReservaController::class, 'show'])->name('show');
        Route::patch('/{reserva}/cancelar', [ReservaController::class, 'cancel'])->name('cancel');
    });

    // Pagamentos
    Route::prefix('pagamentos')->name('pagamentos.')->group(function () {
        Route::get('/{reserva}', [PagamentoController::class, 'show'])->name('show');
        Route::post('/{reserva}/processar', [PagamentoController::class, 'process'])->name('process');
        Route::get('/{reserva}/callback', [PagamentoController::class, 'callback'])->name('callback');
    });
});
