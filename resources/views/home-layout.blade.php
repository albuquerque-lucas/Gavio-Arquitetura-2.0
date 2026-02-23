<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/publicapp.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<body class='body' style="--public-bg-image: url('{{ $assets['home_background_url'] }}');">

    <main class="">
        @yield('content')
    </main>

    <footer class='main-footer container w-100'>
        <div class="footer-content">
            <div class="footer-social-media">
                <a href="https://wa.me/5532988660027?text=Ol%C3%A1%2C+tudo+bem%3F+Vim+pelo+contato+do+site%21+Poder%C3%ADamos+fazer+um+or%C3%A7amento%3F" target="_blank"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.instagram.com/isagavio.arq/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="footer-address">
                <p>Rua Ataliba de Barros, 182, São Mateus - Juiz de Fora (Rossi 360 Business, sala 512)</p>
                <p>2021 - Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9aa910470c.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
