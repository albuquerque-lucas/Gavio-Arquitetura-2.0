<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gavio Arquitetura | Admin | Projetos</title>
    {{-- @vite('resources/scss/app.scss') --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" type="image/png">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9aa910470c.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- @vite('resources/js/app.js') --}}
</body>

</html>
