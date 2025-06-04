<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANURB Cars - Carros Disponíveis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #547326;
            min-height: 100vh;
            color: #333;
        }

      

        .header {
            background-color: #ffffff;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #547326;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .section-title {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-title h1 {
            font-size: 2rem;
            color: #547326;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .section-title p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
        }

        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .car-card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .car-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .car-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .car-card:hover .car-image {
            transform: scale(1.02);
        }

        .car-info {
            padding: 1.5rem;
        }

        .car-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .car-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: #547326;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: baseline;
            gap: 0.3rem;
        }

        .car-price .currency {
            font-size: 1.1rem;
        }

        .car-price .period {
            font-size: 0.9rem;
            color: #666;
            font-weight: 400;
        }

        .characteristics {
            margin-bottom: 1.5rem;
        }

        .characteristics-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .characteristics-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .characteristic-tag {
            background-color: #f8f9fa;
            color: #547326;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .characteristic-tag:hover {
            background-color: #547326;
            color: white;
            transform: translateY(-1px);
        }

        .rent-button {
            width: 100%;
            background-color: #547326;
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rent-button:hover {
            background-color: #3d5a1a;
            transform: translateY(-1px);
        }

        .availability-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: #547326;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }

        .car-card {
            position: relative;
        }

        @media (max-width: 768px) {
            .cars-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .section-title h1 {
                font-size: 1.6rem;
            }
            
            .header-content {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .nav-links a {
                font-size: 0.9rem;
            }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .stats-bar {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #547326;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.3rem;
        }

        @media (max-width: 640px) {
            .stats-bar {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">ANURB Cars S.A. 
                
            </div>
            <div class="nav-links">
                <a href="#sobre">Sobre</a>
                <a href="#login">Login</a>
                <a href="#registar">Registar</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section-title">
            <h1>Carros Disponíveis</h1>
            <p>Oferecemos uma experiência de aluguer de veículos premium com a melhor frota de carros disponível no mercado. Os nossos veículos são cuidadosamente selecionados e mantidos para garantir a sua segurança, conforto e satisfação durante toda a viagem.</p>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-number">32</span>
                <div class="stat-label">Veículos Disponíveis</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">24h</span>
                <div class="stat-label">Suporte</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">99%</span>
                <div class="stat-label">Satisfação</div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <h1 class="section-title text-3xl font-bold mb-8 text-center">Escolha o seu veículo</h1>
            <div class="cars-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($bensLocaveis as $bem)
                    <div class="car-card bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                        <img src="{{ $bem->imagem }}" alt="Imagem do veículo" class="car-image h-48 w-full object-cover">
                        <div class="car-info p-4 flex-1 flex flex-col">
                            <h2 class="car-name text-xl font-semibold mb-2">{{ $bem->marca->nome ?? '' }} {{ $bem->modelo }}</h2>
                            <p class="text-gray-600 mb-1">Ano: {{ $bem->ano }}</p>
                            <p class="text-gray-600 mb-1">Cor: {{ $bem->cor }}</p>
                            <p class="text-gray-600 mb-1">Passageiros: {{ $bem->numero_passageiros }}</p>
                            <p class="text-gray-600 mb-1">Transmissão: {{ $bem->transmissao }}</p>
                            <p class="car-price text-gray-800 font-bold mt-2">{{ $bem->precoFormatado }}/dia</p>
                            <div class="mt-auto pt-4">
                                <a href="{{ route('reservas.create', ['bem' => $bem->id]) }}" class="rent-button block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center transition">Reservar Agora</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($bensLocaveis->isEmpty())
                <div class="text-center text-gray-500 mt-8">Nenhum veículo disponível no momento.</div>
            @endif
        </div>
    </div>
</body>
</html>