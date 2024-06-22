<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite('resources/scss/app.scss')
    @vite('resources/scss/public-app.scss')
    @vite('resources/js/app.js')
    @vite('resources/js/public-app.js')
    @yield('extra-css')
    <link rel="icon" href="{{ asset('public/storage/logo/gavioarquitetura-icone-02.png') }}" type="image/png">
</head>
<body class='body'>
    <header class="header">
        <div class="container container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ route('public.home') }}">
                    <img src="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" alt="Gavio Arquitetura Logo" class="logo-image-primary">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('public.about.us') }}">Quem Somos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Projetos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contato</a>
                        </li>
                    </ul>
                    <span class="navbar-text d-none d-lg-inline">
                        <img src="{{ asset('storage/logo/gavioarquitetura-escrita-02.png') }}" alt="Gavio Arquitetura Escrita" class="logo-image-secondary">
                    </span>
                </div>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class='main-footer container'>
        <div class="footer-content">
            <div class="footer-social-media">
                <a href="https://wa.me/5532988660027?text=Ol%C3%A1%2C+tudo+bem%3F+Vim+pelo+contato+do+site%21+Poder%C3%ADamos+fazer+um+or%C3%A7amento%3F" target="_blank"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.instagram.com/isagavio.arq/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="footer-address">
                <p>Rua Ataliba de Barros, 182, São Mateus - Juiz de Fora (Rossi 360 Business, sala 407)</p>
                <p>2021 - Todos os direitos reservados.</p>
            </div>
        </div>
        <div class="footer-content">
            <strong>Entre em contato conosco!</strong>
            @if(!empty($message))
                <div class="alert alert-success w-50 text-center">
                    {{ $message }}
                </div>
            @endif
            <form action="#" method="POST">
                @csrf
                <input type="text" name='name' placeholder='Seu nome'>

                <input type="text" name='email' placeholder='Seu e-mail'>

                <input type="text" name='subject' placeholder='Digite um assunto'>

                <textarea name="message" id="message-text" placeholder='Mensagem'></textarea>
                <button type='submit' name='submit' class='btn'>Enviar</button>
            </form>
        </div>


    </footer>
</body>
</html>
