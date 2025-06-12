<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Localizacao;

class AreaCliente extends Controller
{
    public function clientArea()
    {
        $user = Auth::user();
        $reservas = $user->reservas()->with(['bemLocavel.marca', 'bemLocavel.localizacoes', 'pagamentos'])->get();
        $reservasAtivas = $user->reservas()->whereIn('status', ['confirmada', 'ativa'])->count();
        $totalReservas = $user->reservas()->count();
        $totalGasto = $user->reservas()->sum('preco_total');
        $avaliacaoMedia = 4.8; // Calcular baseado nas avaliações reais
        // Corrigir erro SQL: buscar cidade e filial separadas, ordenar, e montar array único
        $locais = Localizacao::select('cidade', 'filial')
            ->orderBy('cidade')
            ->orderBy('filial')
            ->get();
        $filiais = $locais->map(function($l) { return $l->cidade . ' - ' . $l->filial; })->unique()->values();
        return view('cliente.area', compact('reservas', 'reservasAtivas', 'totalReservas', 'totalGasto', 'avaliacaoMedia', 'filiais'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'morada' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'nif' => 'required|string|max:20|unique:users,nif,' . $user->id,
        ]);
        $user->update($validated);
        return redirect()->back()->with('success', 'Dados pessoais atualizados com sucesso!');
    }

    public function cancelarReserva(Request $request, $id)
    {
        $reserva = \App\Models\Reserva::findOrFail($id);
        // Só permite cancelar se for do próprio utilizador e status válido
        if ($reserva->user_id !== Auth::id() || !in_array($reserva->status, ['pendente', 'confirmada', 'ativa'])) {
            return redirect()->back()->with('error', 'Não é possível cancelar esta reserva.');
        }
        // Calcular se tem direito a devolução
        $hoje = now();
        $inicio = $reserva->data_inicio instanceof \Carbon\Carbon ? $reserva->data_inicio : \Carbon\Carbon::parse($reserva->data_inicio);
        $diffHoras = $hoje->diffInHours($inicio, false); // positivo se falta, negativo se já passou
        $valorDevolucao = null;
        if ($diffHoras >= 48) {
            $valorDevolucao = $reserva->preco_total;
            $mensagem = 'Reserva cancelada com sucesso! O valor será devolvido.';
        } else {
            $valorDevolucao = 0;
            $mensagem = 'Nenhum valor a ser devolvido (<48h)';
        }
        $reserva->status = 'cancelada';
        $reserva->valor_devolucao = $valorDevolucao;
        $reserva->save();
        
        // Atualizar os totais para a view (AJAX ou redirect com recálculo)
        if ($request->ajax()) {
            $user = Auth::user();
            $reservasAtivas = $user->reservas()->whereIn('status', ['confirmada', 'ativa'])->count();
            $totalReservas = $user->reservas()->count();
            $totalGasto = $user->reservas()->sum('preco_total');
            return response()->json([
                'success' => true,
                'reservasAtivas' => $reservasAtivas,
                'totalReservas' => $totalReservas,
                'totalGasto' => $totalGasto,
                'mensagem' => $mensagem,
            ]);
        }
        return redirect()->back()->with('success', $mensagem);
    }
}
