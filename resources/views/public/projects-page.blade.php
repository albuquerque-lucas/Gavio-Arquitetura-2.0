@extends('public-layout')

@section('content')
<section class='projects-container'>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center projects-title-container">
            <h1 class="text-dark">Nossos Projetos</h1>
        </div>

        <div class="btn-group mb-4 category-btn-container" role="group" aria-label="Categorias">
            @foreach ($categories as $category)
                <a href="{{ route('public.projects', $category->id) }}" class="category-btns {{ $categoryId == $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @if ($projects->hasPages())
            <div class="links-btn-container" style="height: 4rem">
                @foreach ($links as $link)
                    <a href="{{ $link['url'] }}" class="btn mx-2 navlink {{ $link['active'] ? 'active' : '' }}">
                        {!! $link['label'] !!}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="row">
            @forelse ($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 project-card">
                        <div class="img-container">
                            <img src="{{ $project->coverUrl() }}" class="card-img-top" alt="{{ $project->title }}">
                        </div>
                        <div class="card-body transparent-bg">
                            <h5 class="card-title">{{ $project->title }}</h5>
                            <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                            <a href="{{ route('public.project.show', $project->id) }}" class="btn">Ver mais</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Nenhum projeto encontrado para esta categoria.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
