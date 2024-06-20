@extends('public-layout')

@section('extra-css')
    @vite('resources/scss/public/home.scss')
@endsection

@section('content')
    <section class='carousel_section container'>
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                @foreach($projects as $index => $project)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                        aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($projects as $index => $project)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">

                            <img src="{{ $project->coverUrl() }}" class="d-block w-100" alt="{{ $project->title }}">

                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
@endsection
