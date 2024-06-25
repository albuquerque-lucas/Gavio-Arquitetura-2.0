<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gavio Arquitetura | Admin | Projetos</title>
    @vite('resources/scss/app.scss')
    @vite('resources/js/app.js')
    <link rel="icon" href="{{ asset('public/storage/logo/gavioarquitetura-icone-02.png') }}" type="image/png">
</head>

<body class='bg-dark'>
    @auth
    <header class='layout-header'>
        <div class="header-nav-container container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('admin.projetos.index') }}">Gavio Arquitetura | Admin</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <x-nav-item route="admin.projetos.index" text="Projetos" />
                            <x-nav-item route="admin.categories.index" text="Categorias" />
                            <x-nav-item route="admin.users.index" text="Usuários" />
                        </ul>
                        <div class="d-flex align-items-center">
                            <span class="navbar-text text-white mx-5 btn btn-secondary">
                                <span class='mx-1'>
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                    <a
                                    href="{{route('admin.users.edit', Auth::user()->id)}}"
                                    class="text-decoration-none"
                                    >
                                        {{ Auth::user()->name }}
                                    </a>
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    @endauth

    <main class='layout-main'>
        @yield('content')
    </main>
    <footer class='layout-footer'>
    </footer>
    @vite('resources/js/app.js')
</body>

</html>
