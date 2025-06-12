<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,HomeController,BemLocavelController,
    UserController,ReservaController,PagamentoController,AreaCliente};

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




// // Página de detalhes de um veículo
// Route::get('/carros/{id}', [BemLocavelController::class, 'show'])->name('carros.show');
// Buscar veículos com filtros
Route::get('/carros/search', [BemLocavelController::class, 'search'])->name('carros.search');

Route::get('/reservas/{bem}', [ReservaController::class, 'create'])->name('reservas.create');

//Rota PDF para imprimir reservas
Route::get('/reservas/{reserva}/pdf', [\App\Http\Controllers\ReservaController::class, 'gerarPdf'])->name('reservas.pdf');

// Rotas de autenticação e perfil
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () 
{
Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');

 // Reservas
Route::prefix('reservas')->name('reservas.')->group(function () 
    {
    Route::get('/', [ReservaController::class, 'index'])->name('index');
    Route::post('/store', [ReservaController::class, 'store'])->name('store');
    Route::get('/{reserva}', [ReservaController::class, 'show'])->name('show');
    Route::patch('/{reserva}/cancelar', [ReservaController::class, 'cancel'])->name('cancel');
// Editar reserva
    Route::get('/reservas/{reserva}/editar', [\App\Http\Controllers\ReservaController::class, 'edit'])->name('reservas.edit');
// Atualizar reserva (PATCH)
    Route::patch('/reservas/{reserva}/atualizar', [\App\Http\Controllers\ReservaController::class, 'update'])->name('reservas.update');
// Permitir AJAX via POST + _method=PATCH
    Route::post('/reservas/{reserva}/atualizar', [\App\Http\Controllers\ReservaController::class, 'update']);
        
    });

// Pagamentos
    Route::prefix('pagamentos')->name('pagamentos.')->group(function () 
    {
        Route::get('/{reserva}', [PagamentoController::class, 'show'])->name('show');
        Route::post('/{reserva}/processar', [PagamentoController::class, 'process'])->name('process');
        Route::get('/{reserva}/callback', [PagamentoController::class, 'callback'])->name('callback');
    });
});

// Área do cliente
Route::get('/cliente/area', [AreaCliente::class, 'clientArea'])->name('cliente.area');
Route::patch('/cliente/update-profile', [AreaCliente::class, 'updateProfile'])->name('cliente.updateProfile');
// Cancelar reserva
Route::patch('/reservas/{id}/cancelar', [\App\Http\Controllers\AreaCliente::class, 'cancelarReserva'])->name('reservas.cancel');