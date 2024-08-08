@extends('home-layout')

@section('content')
<section class='home_section_container fade-in'>
    <div class="home-item home-images fade-in">
        <a href="{{ route('public.home') }}" class="icon-link">
            <img src="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" alt="gavio-arquitetura-logo" class="home-logo icon-logo fade-in">
        </a>
        <a href="{{ route('public.home') }}" class="icon-link">
            <img src="{{ asset('storage/logo/gavioarquitetura-escrita-01.png') }}" alt="gavio-arquitetura-logo" class="home-logo fade-in">
        </a>
    </div>
    <nav class="home-item home-nav fade-in">
        <ul>
            <li class="fade-in">
                <a href="{{ route('public.about.us') }}">
                    sobre
                </a>
            </li>
            <li class="fade-in">
                <a href="{{ route('public.projects', 'residencial') }}">
                    projetos
                </a>
            </li>
            <li class="fade-in">
                <a href="{{ route('public.contact.us') }}">
                    contato
                </a>
            </li>
        </ul>
    </nav>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');

    fadeElements.forEach((element) => {
        element.classList.add('show');
    });
});

</script>
@endsection
