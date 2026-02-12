@extends('home-layout')

@section('content')
<section class='home_section_container fade-in'>
    <div class="home-item home-images fade-in">
        <div class="brand-lockup fade-in">
            <a href="{{ route('public.home') }}" class="icon-link">
                <img
                    src="{{ $assets['brand_logo_icon_url'] }}"
                    alt="gavio-arquitetura-logo"
                    class="home-logo icon-logo fade-in"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"
                >
                <span class="home-logo-fallback home-icon-fallback fade-in" aria-hidden="true">GA</span>
            </a>
            <a href="{{ route('public.home') }}" class="icon-link written-link">
                <img
                    src="{{ $assets['brand_logo_written_url'] }}"
                    alt="gavio-arquitetura-logo"
                    class="home-logo written-logo fade-in"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';"
                >
                <span class="home-logo-fallback home-written-fallback fade-in" aria-hidden="true">Gavio Arquitetura</span>
            </a>
        </div>
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
