<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Reserva #{{ $dados['id'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h1 { color: #567a3a; }
        .section { margin-bottom: 18px; }
        .label { font-weight: bold; color: #567a3a; }
        .value { margin-left: 8px; }
        .box { border: 1px solid #567a3a; border-radius: 8px; padding: 12px; margin-bottom: 18px; }
    </style>
</head>
<body>
    <h1>Detalhes da Reserva</h1>
    <div class="box">
        <div class="section"><span class="label">Reserva Nº:</span> <span class="value">{{ $dados['id'] }}</span></div>
        <div class="section"><span class="label">Cliente:</span> <span class="value">{{ $dados['nome_cliente'] }}</span></div>
        <div class="section"><span class="label">Email:</span> <span class="value">{{ $dados['email_cliente'] }}</span></div>
    </div>
    <div class="box">
        <div class="section"><span class="label">Veículo:</span> <span class="value">{{ $dados['veiculo'] }}</span></div>
        <div class="section"><span class="label">Matrícula:</span> <span class="value">{{ $dados['matricula'] }}</span></div>
        <div class="section"><span class="label">Cor:</span> <span class="value">{{ $dados['cor'] }}</span></div>
        <div class="section"><span class="label">Combustível:</span> <span class="value">{{ ucfirst($dados['combustivel']) }}</span></div>
        <div class="section"><span class="label">Transmissão:</span> <span class="value">{{ ucfirst($dados['transmissao']) }}</span></div>
        <div class="section"><span class="label">Ano:</span> <span class="value">{{ $dados['ano'] }}</span></div>
    </div>
    <div class="box">
        <div class="section"><span class="label">Data de Início:</span> <span class="value">{{ $dados['data_inicio'] }}</span></div>
        <div class="section"><span class="label">Data de Fim:</span> <span class="value">{{ $dados['data_fim'] }}</span></div>
        <div class="section"><span class="label">Valor Total:</span> <span class="value">{{ number_format($dados['preco_total'], 2, ',', '.') }} €</span></div>
    </div>
    <div class="box">
        <div class="section"><span class="label">Entidade Multibanco:</span> <span class="value">{{ $dados['entidade_multibanco'] }}</span></div>
        <div class="section"><span class="label">Referência Multibanco:</span> <span class="value">{{ $dados['referencia_multibanco'] }}</span></div>
    </div>
    <div class="box">
        <div class="section"><span class="label">Estado da Reserva:</span> <span class="value">{{ ucfirst($dados['status']) }}</span></div>
       
    </div>
</body>
</html>