@extends('layouts.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

       
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #547326;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 1rem;
        }

        /* Reservations Table */
        .reservations-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #547326 0%, #93A603 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .table-container {
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
        }

        .reservations-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reservations-table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .reservations-table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .reservations-table tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
            display: inline-block;
        }

        .status-pendente {
            background: #D9CB04;
            color: #000000;
        }

        .status-confirmada {
            background: #93A603;
            color: #FFFFFF;
        }

        .status-ativa {
            background: #547326;
            color: #FFFFFF;
        }

        .status-finalizada {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-cancelada {
            background: #000000;
            color: #FFFFFF;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 110px;
            width: 110px;
            text-align: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-edit {
            background: #93A603;
            color: white;
        }

        .btn-edit:hover {
            background: #7a8803;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #000000;
            color: white;
        }

        .btn-cancel:hover {
            background: #333333;
            transform: translateY(-2px);
        }

        .btn-view {
            background: #547326;
            color: white;
        }

        .btn-view:hover {
            background: #456020;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn:disabled:hover {
            transform: none;
        }

        .car-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .car-model {
            font-weight: 600;
            color: #333;
        }

        .car-details {
            font-size: 0.85rem;
            color: #666;
        }

        .price {
            font-weight: 600;
            color: #547326;
            font-size: 1.1rem;
        }

        .payment-status {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .payment-pago {
            background: #93A603;
            color: #FFFFFF;
        }

        .payment-pendente {
            background: #D9CB04;
            color: #000000;
        }

        .payment-falhado {
            background: #000000;
            color: #FFFFFF;
        }

        .payment-reembolsado {
            background: #547326;
            color: #FFFFFF;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }

            .nav-links {
                display: none;
            }

            .main-container {
                padding: 0 1rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .reservations-table {
                font-size: 0.85rem;
            }

            .reservations-table th,
            .reservations-table td {
                padding: 0.75rem 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Bem-vindo, {{ Auth::user()->name }}!</h1>
            <p class="welcome-subtitle">Gerencie as suas reservas e acompanhe o histórico dos seus alugueres</p>
            <button class="btn btn-edit" style="margin-top:1rem;" onclick="openEditProfileModal()">Editar Dados Pessoais</button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">★{{ $reservasAtivas }}</div>
                <div class="stat-label">Reservas Ativas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalReservas }}</div>
                <div class="stat-label">Total de Reservas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">€{{ number_format($totalGasto, 2) }}</div>
                <div class="stat-label">Total Gasto</div>
            </div>
        </div>

        <!-- Reservations Table -->
        <div class="reservations-section">
            <div class="section-header">
                <h2 class="section-title">As Minhas Reservas</h2>
                <a href="{{ route('logout') }}" class="logout-btn"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    🚪 Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            <div class="table-container">
                <table class="reservations-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Veículo</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Localização</th>
                            <th>Preço Total</th>
                            <th>Status Reserva</th>
                            <th>Status Pagamento</th>
                            <th>Valores a Receber</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td>#{{ str_pad($reserva->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="car-info">
                                    <div class="car-model">{{ $reserva->bemLocavel->marca->nome }} {{ $reserva->bemLocavel->modelo }}</div>
                                    <div class="car-details">{{ $reserva->bemLocavel->ano }} • {{ ucfirst($reserva->bemLocavel->transmissao) }} • {{ ucfirst($reserva->bemLocavel->combustivel) }}</div>
                                    <div class="car-details">{{ $reserva->bemLocavel->numero_passageiros }} passageiros • {{ $reserva->bemLocavel->numero_portas }} portas</div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y') }}</td>
                            <td>{{ $reserva->local_levantamento ?? 'N/A' }} → {{ $reserva->local_devolucao ?? 'N/A' }}</td>
                            <td><span class="price">€{{ number_format($reserva->preco_total, 2) }}</span></td>
                            <td><span class="status-badge status-{{ $reserva->status }}">{{ ucfirst($reserva->status) }}</span></td>
                            <td>
                                @if($reserva->pagamentos->isNotEmpty())
                                    <span class="payment-status payment-{{ $reserva->pagamentos->first()->status }}">
                                        {{ ucfirst($reserva->pagamentos->first()->status) }}
                                    </span>
                                @else
                                    <span class="payment-status payment-pendente">Pendente</span>
                                @endif
                            </td>
                            <td>
                                @if($reserva->status === 'cancelada')
                                    @if($reserva->valor_devolucao && $reserva->valor_devolucao > 0)
                                        <span style="color: #547326; font-weight: bold;">€{{ number_format($reserva->valor_devolucao, 2) }}</span>
                                    @else
                                        <span style="color: #b30000;">Nenhum valor a ser devolvido</span>
                                    @endif
                                @else
                                    <span style="color: #888;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('reservas.pdf', $reserva->id) }}" class="btn btn-view" target="_blank">Ver PDF</a>
                                    @if(in_array($reserva->status, ['pendente', 'confirmada']))
                                        <button type="button" class="btn btn-edit" onclick="openEditModal({{ $reserva->id }})">Editar</button>
                                    @else
                                        <button class="btn btn-edit" disabled>Editar</button>
                                    @endif
                                    @if(in_array($reserva->status, ['pendente', 'confirmada', 'ativa']))
                                        <form action="{{ route('reservas.cancel', $reserva->id) }}" method="POST" style="display: inline;" class="cancel-form"
                                              onsubmit="return cancelarReservaAjax(event, {{ $reserva->id }})">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-cancel">Cancelar</button>
                                        </form>
                                    @else
                                        <button class="btn btn-cancel" disabled>Cancelar</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2rem; color: #666;">
                                Não possui reservas registadas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>

    {{-- Modal de Edição de Reserva --}}
    <div id="editReservaModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:10px; max-width:500px; width:90%; margin:auto; padding:2rem; position:relative;">
            <button onclick="closeEditModal()" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
            <h2 style="margin-bottom:1rem;">Editar Reserva</h2>
            <form id="editReservaForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="reserva_id" id="editReservaId">
                <div style="margin-bottom:1rem;">
                    <label>Data Início:</label>
                    <input type="date" name="data_inicio" id="editDataInicio" class="form-control" required>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Data Fim:</label>
                    <input type="date" name="data_fim" id="editDataFim" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-edit" style="width:100%;">Guardar Alterações</button>
            </form>
        </div>
    </div>

    <!-- Modal de Edição de Dados Pessoais -->
    <div id="editProfileModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:10px; max-width:500px; width:90%; margin:auto; padding:2rem; position:relative;">
            <button onclick="closeEditProfileModal()" style="position:absolute; top:1rem; right:1rem; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
            <h2 style="margin-bottom:1rem;">Editar Dados Pessoais</h2>
            <form id="editProfileForm" method="POST" action="{{ route('cliente.updateProfile') }}">
                @csrf
                @method('PATCH')
                <div style="margin-bottom:1rem;">
                    <label>Nome:</label>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Morada:</label>
                    <input type="text" name="morada" class="form-control" value="{{ Auth::user()->morada }}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Telefone:</label>
                    <input type="text" name="telefone" class="form-control" value="{{ Auth::user()->telefone }}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento" class="form-control" value="{{ Auth::user()->data_nascimento }}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>NIF:</label>
                    <input type="text" name="nif" class="form-control" value="{{ Auth::user()->nif }}" required>
                </div>
                <button type="submit" class="btn btn-edit" style="width:100%;">Guardar Alterações</button>
            </form>
        </div>
    </div>

    <script>
        let reservas = @json($reservas);
        function openEditModal(reservaId) {
            const reserva = reservas.find(r => r.id === reservaId);
            if (!reserva) return;
            document.getElementById('editReservaId').value = reserva.id;
            document.getElementById('editDataInicio').value = reserva.data_inicio.substring(0,10);
            document.getElementById('editDataFim').value = reserva.data_fim.substring(0,10);
            // Limite máximo: data atual + 7 meses
            const today = new Date();
            const maxDate = new Date(today.getFullYear(), today.getMonth() + 7, today.getDate());
            const maxDateStr = maxDate.toISOString().slice(0,10);
            document.getElementById('editDataInicio').setAttribute('max', maxDateStr);
            document.getElementById('editDataFim').setAttribute('max', maxDateStr);
            document.getElementById('editReservaForm').action = '/reservas/reservas/' + reserva.id + '/atualizar';
            document.getElementById('editReservaModal').style.display = 'flex';
        }
        function closeEditModal() {
            document.getElementById('editReservaModal').style.display = 'none';
        }
        function openEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'flex';
        }
        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
        }
        function cancelarReservaAjax(event, reservaId) {
            event.preventDefault();
            // Confirmação personalizada
            if (!window.confirm('Tem certeza que deseja cancelar esta reserva?')) return false;
            const form = event.target;
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new URLSearchParams(new FormData(form))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar cards
                    document.querySelector('.stat-number').textContent = '★' + data.reservasAtivas;
                    document.querySelectorAll('.stat-number')[1].textContent = data.totalReservas;
                    document.querySelectorAll('.stat-number')[2].textContent = '€' + Number(data.totalGasto).toFixed(2);
                    // Reload só a tabela (simples: recarregar a página, ou melhor: remover linha)
                    location.reload(); // Para garantir atualização de status e valores
                } else {
                    alert('Erro ao cancelar reserva.');
                }
            })
            .catch(() => alert('Erro ao cancelar reserva.'));
            return false;
        }

        // --- NOVO: AJAX para editar reserva ---
        document.getElementById('editReservaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const reservaId = document.getElementById('editReservaId').value;
            const data = new FormData(form);
            // Garante que _method=PATCH é enviado
            data.set('_method', 'PATCH');
            fetch(form.action, {
                method: 'POST', // Laravel reconhece PATCH via _method
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: data
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro ao guardar alterações.');
                return response.json();
            })
            .then(json => {
                if (json.success && json.reserva) {
                    atualizarLinhaReserva(json.reserva);
                    closeEditModal();
                    if (json.reservasAtivas !== undefined) document.querySelector('.stat-number').textContent = '★' + json.reservasAtivas;
                    if (json.totalReservas !== undefined) document.querySelectorAll('.stat-number')[1].textContent = json.totalReservas;
                    if (json.totalGasto !== undefined) document.querySelectorAll('.stat-number')[2].textContent = '€' + Number(json.totalGasto).toFixed(2);
                } else {
                    alert(json.message || 'Erro ao guardar alterações.');
                }
            })
            .catch(() => alert('Erro ao guardar alterações.'));
        });

        function atualizarLinhaReserva(reserva) {
            // Procura a linha da tabela pelo id da reserva
            const row = Array.from(document.querySelectorAll('.reservations-table tbody tr')).find(tr => {
                return tr.querySelector('td') && tr.querySelector('td').textContent.replace('#','') == reserva.id.toString().padStart(3, '0');
            });
            if (!row) return;
            // Atualiza as células relevantes
            row.children[2].textContent = formatarData(reserva.data_inicio);
            row.children[3].textContent = formatarData(reserva.data_fim);
            row.children[4].textContent = reserva.localizacao;
            // Se preço total mudou
            if (row.children[5].querySelector('.price')) {
                row.children[5].querySelector('.price').textContent = '€' + Number(reserva.preco_total).toFixed(2);
            }
            // Status reserva
            if (row.children[6].querySelector('.status-badge')) {
                row.children[6].querySelector('.status-badge').textContent = reserva.status.charAt(0).toUpperCase() + reserva.status.slice(1);
                row.children[6].querySelector('.status-badge').className = 'status-badge status-' + reserva.status;
            }
            // Status pagamento
            if (row.children[7].querySelector('.payment-status')) {
                row.children[7].querySelector('.payment-status').textContent = reserva.pagamento_status.charAt(0).toUpperCase() + reserva.pagamento_status.slice(1);
                row.children[7].querySelector('.payment-status').className = 'payment-status payment-' + reserva.pagamento_status;
            }
            // Valores a receber (novo: diferença de dias)
            if (reserva.status === 'cancelada') {
                if (reserva.valor_devolucao && reserva.valor_devolucao > 0) {
                    row.children[8].innerHTML = '<span style="color: #547326; font-weight: bold;">€' + Number(reserva.valor_devolucao).toFixed(2) + '</span>';
                } else {
                    row.children[8].innerHTML = '<span style="color: #b30000;">Nenhum valor a ser devolvido</span>';
                }
            } else if (reserva.diferenca_valor && reserva.diferenca_valor < 0) {
                // Menos dias: reembolso
                row.children[8].innerHTML = '<span style="color: #547326; font-weight: bold;">Irá ser reembolsado com o valor da diferença</span>';
            } else if (reserva.diferenca_valor && reserva.diferenca_valor > 0) {
                // Mais dias: valor a pagar
                row.children[8].innerHTML = '<span style="color: #b30000;">Valor a pagar em falta: €' + Number(reserva.diferenca_valor).toFixed(2) + '</span>';
            } else {
                row.children[8].innerHTML = '<span style="color: #888;">-</span>';
            }
        }
        function formatarData(data) {
            // data: yyyy-mm-dd
            if (!data) return '';
            const [y,m,d] = data.split('-');
            return `${d}/${m}/${y}`;
        }
    </script>
@endsection


