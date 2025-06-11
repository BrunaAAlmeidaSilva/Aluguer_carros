@extends('layouts.app')

@section('content')
<style>
        .custom-background {
            background-color: #547326;
            min-height: 100vh;
        }
        .navbar {
            background-color: #ffffff !important;
        }
        
        .carro-image {
            width: 5%;
            height: auto;
            display: block;
            padding: 10px;
        }
        
        .booking-container {
            background-image: url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 15px;
            padding: 40px;
            margin: 40px auto;
            max-width: 1000px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .booking-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            border-radius: 15px;
        }
        
        .booking-form {
            position: relative;
            z-index: 2;
        }
        
        .form-control, .form-select {
            background: rgba(255,255,255,0.9);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.95);
            border-color: #547326;
            box-shadow: 0 0 0 0.2rem rgba(84, 115, 38, 0.25);
        }
        
        .form-label {
            color: white;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
            margin-bottom: 8px;
        }
        
        .description-section {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            max-width: 1000px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .description-section h3 {
            color: #547326;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .features-section {
            margin: 40px auto;
            max-width: 1000px;
        }
        
        .feature-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #547326;
            margin-bottom: 20px;
        }
        
        .feature-card h4 {
            color: #547326;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .btn-reserve {
            background-color: #547326;
            border-color: #547326;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .btn-reserve:hover {
            background-color: #3d5a1c;
            border-color: #3d5a1c;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(84, 115, 38, 0.3);
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
        }
    </style>

<!-- Booking Form Section -->
<div class="container">
    <div class="booking-container">
        <div class="booking-form">
            <form method="GET" action="{{ route('carrosEscolha.index') }}" class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="local_levantamento" class="form-label">Local de Levantamento</label>
                    <select class="form-select" id="local_levantamento" name="local_levantamento" required>
                        <option value="" disabled selected>Escolha uma filial</option>
                        @foreach($filiais as $filial)
                            <option value="{{ $filial }}">{{ $filial }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="local_devolucao" class="form-label">Local de Devolução</label>
                    <select class="form-select" id="local_devolucao" name="local_devolucao" required>
                        <option value="" disabled selected>Escolha uma filial</option>
                        @foreach($filiais as $filial)
                            <option value="{{ $filial }}">{{ $filial }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="data_hora_levantamento" class="form-label">Data e Hora de Levantamento</label>
                    <input type="datetime-local" class="form-control" id="data_hora_levantamento" name="data_hora_levantamento" required>
                </div>
                <div class="col-md-6">
                    <label for="data_hora_devolucao" class="form-label">Data e Hora de Devolução</label>
                    <input type="datetime-local" class="form-control" id="data_hora_devolucao" name="data_hora_devolucao" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-reserve w-100">Ver Veículos Disponíveis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Description Section -->
<div class="container">
    <div class="description-section">
        <h3><i class="bi bi-info-circle"></i> Sobre o Nosso Serviço de Aluguer</h3>
        <p class="lead">
            Na ANURB Cars S.A., oferecemos uma experiência de aluguer de veículos premium com a melhor frota de carros disponível no mercado. 
            Os nossos veículos são cuidadosamente selecionados e mantidos para garantir a sua segurança, conforto e satisfação durante toda a viagem.
        </p>
        <p>
            Seja para uma viagem de negócios, férias em família ou uma ocasião especial, temos o veículo perfeito para as suas necessidades. 
            Todos os nossos carros passam por inspeções rigorosas e são equipados com as mais recentes tecnologias de segurança e conforto.
        </p>
    </div>
</div>

<!-- Features Section -->
<div class="container">
    <div class="features-section">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>Segurança Garantida</h4>
                    <p>Todos os nossos veículos passam por inspeções rigorosas e são equipados com os mais avançados sistemas de segurança. A sua tranquilidade é a nossa prioridade.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h4>Disponibilidade 24/7</h4>
                    <p>Serviço de apoio ao cliente disponível 24 horas por dia, 7 dias por semana. Estamos sempre prontos para ajudar, independentemente da hora ou local.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h4>Qualidade Premium</h4>
                    <p>Frota renovada regularmente com veículos de marcas reconhecidas mundialmente. Oferecemos apenas o melhor em termos de qualidade e conforto.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Features Section -->
<div class="container">
    <div class="features-section">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>Política de Cancelamento</h4>
                    <p>Aluguer de viaturas com cancelamento gratuito: pode cancelar a sua reserva sem custos até 48h antes da viagem.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h4>Sempre Perto de Si</h4>
                    <p>ANURB Cars dispõe de estações de aluguer de veículos em vários pontos do país. Levante e devolva o veículo em qualquer uma das nossas estações</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h4>Vasta Gama de eículos</h4>
                    <p>Encontre a melhor solução para alugar carro, numa vasta gama de opções disponíveis.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p><a href="#">Mais Informação</a> | <a href="#">Contactos</a> | <a href="#">Informação Legal</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sobreLink = document.getElementById('sobre-link');
        if (sobreLink) {
            sobreLink.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Sobre a ANURB Cars S.A.',
                    html: `<p class='lead'>Na ANURB Cars S.A., oferecemos uma experiência de aluguer de veículos premium com a melhor frota de carros disponível no mercado.<br>Os nossos veículos são cuidadosamente selecionados e mantidos para garantir a sua segurança, conforto e satisfação durante toda a viagem.</p><p>Seja para uma viagem de negócios, férias em família ou uma ocasião especial, temos o veículo perfeito para as suas necessidades.<br>Todos os nossos carros passam por inspeções rigorosas e são equipados com as mais recentes tecnologias de segurança e conforto.</p>`,
                    icon: 'info',
                    confirmButtonText: 'Fechar'
                });
            });
        }
    });
    
    // Set minimum datetime to current time
    const now = new Date();
    const currentDateTime = now.toISOString().slice(0, 16);
    document.getElementById('dataHoraLevantamento').setAttribute('min', currentDateTime);
    document.getElementById('dataHoraDevolucao').setAttribute('min', currentDateTime);
    
    // Update return datetime minimum when pickup datetime changes
    document.getElementById('dataHoraLevantamento').addEventListener('change', function() {
        const pickupDateTime = this.value;
        document.getElementById('dataHoraDevolucao').setAttribute('min', pickupDateTime);
    });
</script>
@endsection