<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function create(\App\Models\BemLocavel $bem, \Illuminate\Http\Request $request)
    {
        $data_inicio = $request->input('data_inicio', date('Y-m-d'));
        $data_fim = $request->input('data_fim', date('Y-m-d', strtotime('+1 day')));

        // Calculo de dias se data_fim >= data_inicio, senão dias = 1
        $dias = 1;
        if (strtotime($data_fim) >= strtotime($data_inicio)) {
            $dias = \Carbon\Carbon::parse($data_inicio)->diffInDays(\Carbon\Carbon::parse($data_fim)) + 1;
        }

        $dias = max(1, $dias); // nunca menos que 1 dia
        $preco_diario = $bem->preco_diario;
        $subtotal = $dias * $preco_diario;
        $taxa_servico = 25.00;
        $total = $subtotal + $taxa_servico;

        return view('Reserva.create', compact(
            'bem', 'data_inicio', 'data_fim', 'dias', 'subtotal', 'taxa_servico', 'total'
        ));
    }
}
