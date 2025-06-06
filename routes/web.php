<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,HomeController,BemLocavelController,
    UserController,ReservaController,PagamentoController};




// Página inicial: Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Página Home (escolher localizaçao e datas)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Página Bem Locavel
Route::get('/bens_locaveis', [BemLocavelController::class, 'index'])->name('bens_locaveis.index');
Route::get('/bens_locaveis/search', [BemLocavelController::class, 'search'])->name('bens_locaveis.search');
Route::get('/bens-locaveis/{id}', [BemLocavelController::class, 'show'])->name('bens.locaveis.show');
Route::get('/bens-locaveis/{id}/disponibilidade', [BemLocavelController::class, 'verificarDisponibilidade'])->name('bens.locaveis.disponibilidade');
Route::get('/bens-locaveis/localizacao/{cidade}/{filial?}', [BemLocavelController::class, 'porLocalizacao'])->name('bens.locaveis.localizacao');
Route::get('/bens-locaveis/estatisticas', [BemLocavelController::class, 'estatisticas'])->name('bens.locaveis.estatisticas');
Route::post('/bens-locaveis/processar-filtros', [BemLocavelController::class, 'processarFiltros'])->name('bens.locaveis.processar_filtros');
Route::get('/bens-locaveis/detalhes/{id}', [BemLocavelController::class, 'detalhes'])->name('bens.locaveis.detalhes');


// Página principal CarrosEscolha da grid de veículos
Route::get('/carros-escolha', [BemLocavelController::class, 'carrosEscolha'])->name('carrosEscolha.index');



//Route::get('/carros', [BemLocavelController::class, 'index'])->name('bens.index');
// Página de detalhes de um veículo
Route::get('/carros/{id}', [BemLocavelController::class, 'show'])->name('carros.show');
// Buscar veículos com filtros
Route::get('/carros/search', [BemLocavelController::class, 'search'])->name('carros.search');

//Route::get('/reservas/{bem}', [ReservaController::class, 'create'])->name('reservas.create');
Route::get('/reservas/{bem}', [ReservaController::class, 'create'])->name('reservas.create');

//Rota PDF para imprimir reservas
Route::get('/reservas/{reserva}/pdf', [\App\Http\Controllers\ReservaController::class, 'gerarPdf'])->name('reservas.pdf');



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

// Área do cliente
Route::get('/cliente/area', function () { return view('cliente.area');})->name('cliente.area');
