<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use App\Models\Reserva;

class PagamentoController extends Controller
{

    /**
     * Mostra a página de pagamento Multibanco simulada para uma reserva.
     */
    public function show($reservaId)
    {
        $reserva = Reserva::findOrFail($reservaId);
        // Se ainda não tiver referência/entidade, gerar e guardar
        if (!$reserva->referencia_multibanco || !$reserva->entidade_multibanco) {
            $reserva->entidade_multibanco = '12345'; // Simulado
            $reserva->referencia_multibanco = str_pad($reserva->id, 9, '0', STR_PAD_LEFT);
            $reserva->save();
        }
        return view('pagamento.multibanco', [
            'reserva' => $reserva
        ]);
    }

    /**
     * Processa o pagamento Multibanco (simulado).
     */
    public function process(Request $request, $reservaId)
    {
        $reserva = Reserva::findOrFail($reservaId);
        // Atualiza o status da reserva para 'confirmada' após pagamento simulado
        if ($reserva->status === 'pendente') {
            $reserva->status = 'confirmada';
            $reserva->save();
        }
        // Atualiza o status do pagamento associado para 'pago'
        $pagamento = $reserva->pagamentos()->latest()->first();
        if ($pagamento && $pagamento->status !== 'pago') {
            $pagamento->status = 'pago';
            $pagamento->data_pagamento = now();
            $pagamento->save();
        }
        // Apenas simula o pagamento e mostra o alerta de sucesso
        return redirect()->route('pagamentos.show', ['reserva' => $reserva->id])
            ->with('success', 'Pagamento Multibanco efetuado com sucesso!');
    }
}