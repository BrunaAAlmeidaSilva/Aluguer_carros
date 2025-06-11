@extends('layouts.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #7ba05b 0%, #567a3a 100%);
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #567a3a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #567a3a;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Date Selection Section */
        .date-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .date-container {
            display: flex;
            gap: 1.2rem; /* levemente maior */
            align-items: center;
            justify-content: center;
        }

        .date-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 150px; /* maior para melhor leitura */
            max-width: 200px;
        }

        .date-label {
            font-weight: 600;
            color: #567a3a;
            font-size: 1.1rem;
        }

        .date-input {
            padding: 0.8rem; /* maior para melhor leitura */
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1.05rem; /* levemente maior */
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .date-input:focus {
            outline: none;
            border-color: #7ba05b;
            box-shadow: 0 0 0 3px rgba(123, 160, 91, 0.1);
        }

        /* Reservation Details */
        .reservation-details {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        /* Car Image Section */
        .car-image-section {
            text-align: center;
        }

        .car-image {
            width: 100%;
            max-width: 500px;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            margin-bottom: 1.5rem;
        }

        .car-title {
            font-size: 2rem;
            font-weight: bold;
            color: #567a3a;
            margin-bottom: 1rem;
        }

        .car-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
            font-weight: 500;
        }

        /* Characteristics */
        .characteristics {
            margin-top: 1rem;
        }

        .characteristics h4 {
            color: #567a3a;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .char-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.8rem;
        }

        .char-item {
            background: #7ba05b;
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Booking Summary */
        .booking-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            height: fit-content;
        }

        .summary-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #567a3a;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .price-breakdown {
            margin-bottom: 2rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .price-row:last-child {
            border-bottom: 2px solid #567a3a;
            font-weight: bold;
            font-size: 1.2rem;
            color: #567a3a;
        }

        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
            color: #856404;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .security-notice strong {
            color: #567a3a;
        }

        /* Login Button */
        .login-button {
            width: 100%;
            background: linear-gradient(135deg, #7ba05b 0%, #567a3a 100%);
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(123, 160, 91, 0.3);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(123, 160, 91, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .reservation-details {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .date-container {
                flex-direction: column;
                gap: 1rem;
            }

            .container {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .header {
                padding: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .date-section, .reservation-details {
            animation: fadeInUp 0.6s ease-out;
        }

        .reservation-details {
            animation-delay: 0.2s;
        }
    </style>

    <!-- Main Container -->
    <div class="container">
        <!-- Date Selection Section -->
        <div class="date-section">
            <div class="date-container">
                <div class="date-group">
                    <label class="date-label">Local de Levantamento</label>
                    <input type="text" class="date-input" value="{{ $local_levantamento ?? '' }}" readonly>
                </div>
                <div class="date-group">
                    <label class="date-label">Local de Devolução</label>
                    <input type="text" class="date-input" value="{{ $local_devolucao ?? '' }}" readonly>
                </div>
                <div class="date-group">
                    <label class="date-label">Data de Levantamento</label>
                    <input type="date" class="date-input" value="{{ $data_inicio ?? '' }}" readonly>
                </div>
                <div class="date-group">
                    <label class="date-label">Data de Devolução</label>
                    <input type="date" class="date-input" value="{{ $data_fim ?? '' }}" readonly>
                </div>
                <div class="date-group">
                    <label class="date-label">Nº de Dias</label>
                    <input type="text" class="date-input" value="{{ $dias ?? 1 }}" readonly>
                </div>
            </div>
            @if(!$bem)
                <div style="margin-top:2rem;text-align:center;">
                    <a href="{{ route('carrosEscolha.index') }}" class="btn btn-primary">Escolher Veículo</a>
                </div>
            @endif
        </div>

        <!-- Reservation Details -->
        <div class="reservation-details">
            @if($bem)
                <div>
                    <div class="car-image-section">
                        <img src="{{ $bem->imagem ?? '' }}" alt="Imagem do veículo" class="car-image">
                        <h2 class="car-title">
                            {{ $bem->marca->nome ?? '' }} {{ $bem->modelo ?? '' }} {{ $bem->ano ?? '' }}
                        </h2>
                        <div class="car-details">
                            <div class="detail-row">
                                <span class="detail-label">Matrícula:</span>
                                <span class="detail-value">{{ $bem->registo_unico_publico ?? '' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Cor:</span>
                                <span class="detail-value">{{ $bem->cor ?? '' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Combustível:</span>
                                <span class="detail-value">{{ ucfirst($bem->combustivel ?? '') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Transmissão:</span>
                                <span class="detail-value">{{ ucfirst($bem->transmissao ?? '') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Passageiros:</span>
                                <span class="detail-value">{{ $bem->numero_passageiros ?? '' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Portas:</span>
                                <span class="detail-value">{{ $bem->numero_portas ?? '' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Ano:</span>
                                <span class="detail-value">{{ $bem->ano ?? '' }}</span>
                            </div>
                        </div>
                        <div class="characteristics">
                            <h4>CARACTERÍSTICAS</h4>
                            <div class="char-grid">
                                @if($bem->caracteristicas && count($bem->caracteristicas))
                                    @foreach($bem->caracteristicas as $caracteristica)
                                        <div class="char-item">{{ $caracteristica->nome }}</div>
                                    @endforeach
                                @else
                                    <div class="char-item">Sem características adicionais</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="booking-summary">
                        <h3 class="summary-title">Resumo da Reserva</h3>
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span>Período:</span>
                                <span>{{ $data_inicio && $data_fim ? (\Carbon\Carbon::parse($data_inicio)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($data_fim)->format('d/m/Y')) : '-' }}</span>
                            </div>
                            <div class="price-row">
                                <span>Número de dias:</span>
                                <span>{{ $dias ?? '-' }} {{ isset($dias) && $dias == 1 ? 'dia' : 'dias' }}</span>
                            </div>
                            <div class="price-row">
                                <span>Preço por dia:</span>
                                <span>€{{ isset($bem->preco_diario) ? number_format($bem->preco_diario, 2, ',', '.') : '-' }}</span>
                            </div>
                            <div class="price-row">
                                <span>Subtotal:</span>
                                <span>€{{ isset($subtotal) ? number_format($subtotal, 2, ',', '.') : '-' }}</span>
                            </div>
                            <div class="price-row">
                                <span>Taxa de serviço:</span>
                                <span>€{{ isset($taxa_servico) ? number_format($taxa_servico, 2, ',', '.') : '-' }}</span>
                            </div>
                            <div class="price-row">
                                <span>TOTAL:</span>
                                <span>€{{ isset($bem) && isset($total) ? number_format($total, 2, ',', '.') : (isset($bem) && isset($bem->preco_total) ? number_format($bem->preco_total, 2, ',', '.') : '-') }}</span>
                            </div>
                        </div>
                        <div class="security-notice">
                            <strong>Importante:</strong> No ato do levantamento do carro será exigido um cartão de crédito físico em nome do titular da reserva para depósito de segurança, o qual será devolvido no término do contrato.
                        </div>
                        <form method="POST" action="{{ route('reservas.store') }}">
                            @csrf
                            <input type="hidden" name="bem_id" value="{{ $bem->id }}">
                            <input type="hidden" name="data_inicio" value="{{ $data_inicio }}">
                            <input type="hidden" name="data_fim" value="{{ $data_fim }}">
                            @if(!auth()->check())
                                <button type="submit" class="login-button" style="display: block; text-align: center; width: 100%;">Fazer Login para proceder ao pagamento</button>
                            @else
                                <button type="submit" class="login-button" style="display: block; text-align: center; width: 100%;">Proceder ao pagamento</button>
                            @endif
                        </form>
                        <a href="{{ route('register') }}" class="register-button" style="display: block; text-align: center;">
                            Fazer Registo para proceder ao pagamento
                        </a>
                    </div>
                    
                </div>
            @else
                <div class="booking-summary" style="text-align:center;">
                    <h3 class="summary-title">Nenhum veículo selecionado</h3>
                    <p>Por favor, escolha um veículo para ver o resumo da reserva e o preço detalhado.</p>
                    <a href="{{ route('carrosEscolha.index') }}" class="btn btn-primary">Escolher Veículo</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Limitar datas para hoje + 7 meses
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const maxDate = new Date(today.getFullYear(), today.getMonth() + 7, today.getDate());
            const maxDateStr = maxDate.toISOString().slice(0,10);
            const dataInicio = document.querySelector('input[name="data_inicio"]');
            const dataFim = document.querySelector('input[name="data_fim"]');
            if (dataInicio) dataInicio.setAttribute('max', maxDateStr);
            if (dataFim) dataFim.setAttribute('max', maxDateStr);
        });

        // Update dates and calculate total
        function updateReservation() {
            const checkinDate = new Date(document.getElementById('checkin').value);
            const checkoutDate = new Date(document.getElementById('checkout').value);
            
            if (checkinDate && checkoutDate && checkinDate < checkoutDate) {
                const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                // Update period display
                const formatDate = (date) => {
                    return date.toLocaleDateString('pt-PT', { 
                        day: '2-digit', 
                        month: 'short',
                        year: 'numeric'
                    });
                };
                
                document.getElementById('rental-period').textContent = 
                    `${formatDate(checkinDate)} - ${formatDate(checkoutDate)}`;
                document.getElementById('days-count').textContent = `${daysDiff} dias`;
                
                // Calculate prices
                const dailyRate = {{ $bem->preco_diario ?? 55.00 }};
                const subtotal = daysDiff * dailyRate;
                const serviceFee = 25.00;
                const total = subtotal + serviceFee;
                
                document.getElementById('subtotal').textContent = `€${subtotal.toFixed(2)}`;
                document.getElementById('total-price').textContent = `€${total.toFixed(2)}`;
            }
        }

        // Event listeners for date changes
        document.getElementById('checkin').addEventListener('change', updateReservation);
        document.getElementById('checkout').addEventListener('change', updateReservation);

        // Handle login button click
        function handleLogin() {
            alert('Redirecionando para a página de login...');
            // window.location.href = '/login';
        }

        // Initialize
        updateReservation();
    </script>
@endsection