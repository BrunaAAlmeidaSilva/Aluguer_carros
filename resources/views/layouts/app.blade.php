<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANURB Cars S.A.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .custom-background {
         background-color: #547326;
        }
        .navbar {
         background-color: #ffffff !important; 
        }


        .car-image {
            width: 60%;
            height: auto;
            display: block;
            margin: 20px auto;
        }

        .carro-image  {
            width: 5%;
            height: auto;
            display: block;
            padding: 10px;
        
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
        }
        .a{
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            color: #547326;
        }
    </style>
</head>
<body>
<body class="custom-background">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
    <span>ANURB Cars S.A.      Bem-Vindo</span>
    <img src="Imagens/car.png" class="carro-image ">
    <a href="https://www.flaticon.com/free-icons/car" title="car icons"></a>
</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="" id="sobre-link">Sobre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Registar</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link" style="display:inline; padding:0; margin:5px; background:none; border:none; color:inherit; font:inherit; text-align:center;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
@yield('content')


<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p><a href="#">Mais Informação</a> | <a href="#">Contactos</a> | <a href="#">Informação Legal</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert para o modal de "Sobre" -->
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
</script></body>
</html>