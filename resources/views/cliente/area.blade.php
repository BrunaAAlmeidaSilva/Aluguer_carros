@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4" style="color: #567a3a; font-weight: bold;">Área do Cliente</h2>
        <p>Bem-vindo à sua área de cliente! Aqui poderá consultar as suas reservas, dados pessoais e histórico de pagamentos.</p>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar ao início</a>
        </div>
    </div>
</div>
@endsection


'id' => $reserva->id,
            'nome_cliente' => $user ? $user->name : '-',
            'email_cliente' => $user ? $user->email : '-',
            'veiculo' => $bem ? ($bem->marca->nome . ' ' . $bem->modelo) : '-',
            'matricula' => $bem ? $bem->registo_unico_publico : '-',
            'cor' => $bem ? $bem->cor : '-',
            'combustivel' => $bem ? $bem->combustivel : '-',
            'transmissao' => $bem ? $bem->transmissao : '-',
            'ano' => $bem ? $bem->ano : '-',
            'data_inicio' => $reserva->data_inicio ? $reserva->data_inicio->format('d/m/Y') : '-',
            'data_fim' => $reserva->data_fim ? $reserva->data_fim->format('d/m/Y') : '-',
            'preco_total' => $reserva->preco_total,
            'entidade_multibanco' => $reserva->entidade_multibanco,
            'referencia_multibanco' => $reserva->referencia_multibanco,
            'status' => $reserva->status,
            'observacoes' => $reserva->observacoes,