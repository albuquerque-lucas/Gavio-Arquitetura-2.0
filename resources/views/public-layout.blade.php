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
    <link rel="icon" href="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" type="image/png">
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
                            <a class="nav-link" href="#">Quem Somos</a>
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
    @yield('content')
</body>
</html>
