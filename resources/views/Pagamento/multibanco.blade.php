@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow p-4" style="max-width: 420px; width: 100%;">
        <h2 class="text-center mb-4" style="color: #567a3a; font-weight: bold;">Pagamento Multibanco</h2>
        <div class="mb-4">
            <p><strong>Entidade:</strong> {{ $reserva->entidade_multibanco }}</p>
            <p><strong>Referência:</strong> {{ $reserva->referencia_multibanco }}</p>
            <p><strong>Valor:</strong> {{ number_format($reserva->preco_total, 2, ',', '.') }} €</p>
        </div>
        @if(session('success'))
            <div class="alert alert-success text-center" style="color:#547326">{{ session('success') }}
            <a href="{{ route('reservas.pdf', ['reserva' => $reserva->id]) }}" class="btn btn-primary w-100 mt-2" target="_blank">
                Gerar PDF para ver detalhes da reserva
            </a>
            <a href="{{ route('cliente.area') }}" style="color:#547326" class="btn btn-info w-100 mt-2">
                Ir para a área do cliente
            </a>
            </div>
        @else
            <form method="POST" action="{{ route('pagamentos.process', ['reserva' => $reserva->id]) }}">
                @csrf
                <button type="submit"  class="btn btn-success w-100 mb-2">Pagamento</button>
            </form>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100 mt-2">Voltar ao início</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
