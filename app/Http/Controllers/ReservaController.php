<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;


class ReservaController extends Controller
{
    public function create(Request $request, $bem = null)
    {
        // Se vier do formulário da Home OU da escolha do carro, guardar dados na sessão
        if ($request->has(['local_levantamento', 'local_devolucao', 'data_hora_levantamento', 'data_hora_devolucao'])) {
            session([
                'local_levantamento' => $request->input('local_levantamento'),
                'local_devolucao' => $request->input('local_devolucao'),
                'data_hora_levantamento' => $request->input('data_hora_levantamento'),
                'data_hora_devolucao' => $request->input('data_hora_devolucao'),
            ]);
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
            // Aqui, supõe-se que a reserva ainda não foi criada na base de dados, mas temos o bem_id
            // e outros dados necessários na sessão. Quando a reserva for criada, salve o ID dela na sessão.
            session(['reservation_in_progress' => true]);
            // Se já existir um reserva_id (ex: após criar a reserva mas antes do pagamento), mantenha-o
            // Caso contrário, ele será definido após a criação da reserva.
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
        // Validação dos dados do formulário (ajusta conforme necessário)
        $validated = $request->validate([
            'bem_id' => 'required|exists:bens_locaveis,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

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

    public function gerarPdf($reservaId)
    {
        $reserva = \App\Models\Reserva::with(['user', 'bemLocavel.marca'])->findOrFail($reservaId);
        $dados = \App\Models\PDF::dadosReserva($reserva);
        $pdf = DomPdf::loadView('Reserva.pdf', compact('dados'));
        return $pdf->download('reserva_' . $reserva->id . '.pdf');
    }
}
