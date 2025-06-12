<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;


class ReservaController extends Controller
{
    public function create(Request $request, $bem = null)
    {
        // Atualiza cada campo da sessão se vier no request
        foreach (['local_levantamento', 'local_devolucao', 'data_hora_levantamento', 'data_hora_devolucao'] as $campo) {
            if ($request->has($campo)) {
                session([$campo => $request->input($campo)]);
            }
        }
        // Se o parâmetro $bem vier da rota, guardar na sessão
        if ($bem) {
            session(['bem_id' => $bem]);
        } elseif ($request->has('bem')) {
            session(['bem_id' => $request->input('bem')]);
        } elseif ($request->has('bem_id')) {
            session(['bem_id' => $request->input('bem_id')]);
        }

        // Quando o utilizador não está autenticado e tenta reservar, guardar intenção de reserva
        if (!auth()->check()) {
            session(['reservation_in_progress' => true]);
        }

        // Ler dados da sessão
        $local_levantamento = session('local_levantamento');
        $local_devolucao = session('local_devolucao');
        $data_hora_levantamento = session('data_hora_levantamento');
        $data_hora_devolucao = session('data_hora_devolucao');
        $bem_id = session('bem_id');

        // Extrai apenas a data (YYYY-MM-DD)
        $data_inicio = $data_hora_levantamento ? substr($data_hora_levantamento, 0, 10) : null;
        $data_fim = $data_hora_devolucao ? substr($data_hora_devolucao, 0, 10) : null;

        // Calculo de dias (mínimo 1)
        $dias = 1;
        if ($data_inicio && $data_fim && strtotime($data_fim) >= strtotime($data_inicio)) {
            $dias = \Carbon\Carbon::parse($data_inicio)->diffInDays(\Carbon\Carbon::parse($data_fim)) + 1;
        }
        $dias = max(1, $dias);

        $bem = null;
        $subtotal = null;
        $taxa_servico = 25.00;
        $total = null;

        if ($bem_id) {
            $bem = \App\Models\BemLocavel::with(['marca', 'caracteristicas'])->find($bem_id);
            if ($bem) {
                $subtotal = $dias * $bem->preco_diario;
                $total = $subtotal + $taxa_servico;
            }
        }

        return view('Reserva.create', compact(
            'bem', 'local_levantamento', 'local_devolucao', 'data_inicio', 'data_fim', 'dias', 'subtotal', 'taxa_servico', 'total'
        ));
    }

    public function store(Request $request)
    {
        // Validação dos dados do formulário 
        $validated = $request->validate([
            'bem_id' => 'required|exists:bens_locaveis,id',
            'data_inicio' => ['required','date','after_or_equal:today', function($attribute, $value, $fail) {
                if (\Carbon\Carbon::parse($value)->gt(now()->addMonths(7))) {
                    $fail('A data de início não pode ser superior a 7 meses a partir de hoje.');
                }
            }],
            'data_fim' => ['required','date','after_or_equal:data_inicio', function($attribute, $value, $fail) use ($request) {
                if (\Carbon\Carbon::parse($value)->gt(now()->addMonths(7))) {
                    $fail('A data de fim não pode ser superior a 7 meses a partir de hoje.');
                }
            }],
        ]);

        // Buscar locais da sessão
        $local_levantamento = session('local_levantamento');
        $local_devolucao = session('local_devolucao');

        // Criação da reserva
        $bem = \App\Models\BemLocavel::find($validated['bem_id']);
        $dias = \Carbon\Carbon::parse($validated['data_inicio'])->diffInDays(\Carbon\Carbon::parse($validated['data_fim'])) + 1;
        $dias = max(1, $dias);
        $taxa_servico = 25.00;
        $subtotal = $bem ? $dias * $bem->preco_diario : 0;
        $total = $subtotal + $taxa_servico;

        $reserva = \App\Models\Reserva::create([
            'user_id' => auth()->id(),
            'bem_locavel_id' => $validated['bem_id'],
            'data_inicio' => $validated['data_inicio'],
            'data_fim' => $validated['data_fim'],
            'preco_total' => $total,
            'status' => 'pendente',
            'local_levantamento' => $local_levantamento,
            'local_devolucao' => $local_devolucao,
        ]);

        // Guarda na sessão para o fluxo de pagamento
        session(['reservation_in_progress' => true, 'reserva_id' => $reserva->id]);


        // Redireciona para login se não autenticado, ou para pagamento se já autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            return redirect()->route('pagamentos.show', ['reserva' => $reserva->id]);
        }

        
    }
        //Gera PDF da reserva
    public function gerarPdf($reservaId)
    {
        $reserva = \App\Models\Reserva::with(['user', 'bemLocavel.marca'])->findOrFail($reservaId);
        $dados = \App\Models\PDF::dadosReserva($reserva);
        $pdf = DomPdf::loadView('Reserva.pdf', compact('dados'));
        return $pdf->download('reserva_' . $reserva->id . '.pdf');
    }

    public function edit($id)
    {
        $reserva = \App\Models\Reserva::with(['bemLocavel.marca', 'bemLocavel.localizacoes'])->findOrFail($id);
        if (auth()->id() !== $reserva->user_id) {
            abort(403, 'Não autorizado.');
        }
        // Devolve uma view de edição (cria resources/views/reserva/edit.blade.php se necessário)
        return view('reserva.edit', compact('reserva'));
    }

    public function update(Request $request, $id)
    {
        $reserva = \App\Models\Reserva::with(['bemLocavel.localizacoes', 'pagamentos'])->findOrFail($id);
        if (auth()->id() !== $reserva->user_id) {
            abort(403, 'Não autorizado.');
        }
        // Validação com tratamento de exceção para AJAX
        try {
            $validated = $request->validate([
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        }
        $reserva->data_inicio = $validated['data_inicio'];
        $reserva->data_fim = $validated['data_fim'];

        // Recalcular preço total ao editar datas
        $dias = \Carbon\Carbon::parse($validated['data_inicio'])->diffInDays(\Carbon\Carbon::parse($validated['data_fim'])) + 1;
        $dias = max(1, $dias);
        $taxa_servico = 25.00;
        $bem = $reserva->bemLocavel;
        $subtotal = $bem ? $dias * $bem->preco_diario : 0;
        $total = $subtotal + $taxa_servico;
        
        // Calcular diferença de valor (para mostrar ao cliente)
        $diferenca_valor = $total - $reserva->getOriginal('preco_total');
        $reserva->preco_total = $total;
        $reserva->save();

        // Se for AJAX, retorna JSON com dados atualizados
        if ($request->ajax() || $request->wantsJson()) {
            // Recarrega relações
            $reserva->refresh();
            $reserva->load(['bemLocavel.marca', 'bemLocavel.localizacoes', 'pagamentos']);
            $user = $reserva->user;
            $reservasAtivas = $user->reservasAtivas()->count();
            $totalReservas = $user->historicoReservas()->count();
            $totalGasto = $user->reservas()->sum('preco_total');
            // Usar os campos da reserva
            $localizacao = ($reserva->local_levantamento && $reserva->local_devolucao)
                ? $reserva->local_levantamento . ' → ' . $reserva->local_devolucao
                : 'N/A';
            $pagamento_status = $reserva->pagamentos->isNotEmpty() ? $reserva->pagamentos->first()->status : 'pendente';
            return response()->json([
                'success' => true,
                'reserva' => [
                    'id' => $reserva->id,
                    'data_inicio' => $reserva->data_inicio->format('Y-m-d'),
                    'data_fim' => $reserva->data_fim->format('Y-m-d'),
                    'localizacao' => $localizacao,
                    'preco_total' => $reserva->preco_total,
                    'status' => $reserva->status,
                    'pagamento_status' => $pagamento_status,
                    'valor_devolucao' => $reserva->valor_devolucao,
                    'diferenca_valor' => $diferenca_valor,
                ],
                'reservasAtivas' => $reservasAtivas,
                'totalReservas' => $totalReservas,
                'totalGasto' => $totalGasto,
            ]);
        } else {
            \Log::error('Erro update reserva', [
                'input' => $request->all(),
                'user' => auth()->id(),
            ]);
            return response()->json(['success' => false, 'message' => 'Erro inesperado no backend.'], 500);
        }
        // Requisição normal: redirect
        return redirect()->route('cliente.area')->with('success', 'Reserva atualizada com sucesso!');
    }


    
    public function cancel($id)
    {
        // Redireciona para o método de cancelamento do AreaCliente
        return app(\App\Http\Controllers\AreaCliente::class)->cancelarReserva(request(), $id);
    }
}
