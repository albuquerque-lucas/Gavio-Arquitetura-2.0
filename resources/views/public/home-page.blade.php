@extends('home-layout')

@section('content')
<section class='home_section_container'>
    <div class="d-flex home-item home-images">
        <a href="{{ route('public.home') }}">
            <img src="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" alt="gavio-arquitetura-logo" class="home-logo">
        </a>
        <a href="{{ route('public.home') }}">
            <img src="{{ asset('storage/logo/gavioarquitetura-escrita-01.png') }}" alt="gavio-arquitetura-logo" class="home-logo">
        </a>
    </div>
    <nav class="home-item home-nav">
        <ul>
            <li>
                <a href="{{ route('public.about.us') }}">
                    Sobre
                </a>
            </li>
            <li>
                <a href="{{ route('public.projects', 'residencial') }}">
                    Projetos
                </a>
            </li>
            <li>
                <a href="{{ route('public.contact.us') }}">
                    Contato
                </a>
            </li>
        </ul>
    </nav>
</section>
@endsection
