<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gavio Arquitetura | Admin | Projetos</title>
    @vite('resources/scss/app.scss', 'resources/js/app.js')
    @yield('extra-css')
</head>

<body class='bg-dark'>
    <header class='layout-header'>
        <div class="header-nav-container container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Gavio Arquitetura | Admin</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <x-nav-item route="admin.projetos.index" text="Home" />
                            <x-nav-item route="admin.projetos.index" text="Projetos" />
                            <x-nav-item route="admin.categories.index" text="Categorias" />
                            <x-nav-item route="admin.users.index" text="Usuários" />
                            {{-- <x-nav-item route="#" text="E-mail" /> --}}
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main class='layout-main'>
        @yield('content')
    </main>
    <footer class='layout-footer'>
    </footer>
    @vite('resources/js/app.js')
</body>

</html>
