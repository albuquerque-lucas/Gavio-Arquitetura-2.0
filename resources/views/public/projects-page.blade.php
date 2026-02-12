@extends('public-layout')

@section('content')
<section class='projects-container'>
    <div class="container mt-5">
        <div class="mb-4 category-select-container">
            <select id="categorySelect" class="form-select" onchange="location = this.value;">
                @foreach ($categories as $categoryItem)
                    <option value="{{ route('public.projects', $categoryItem->slug) }}" {{ $category->id == $categoryItem->id ? 'selected' : '' }}>
                        {{ $categoryItem->name }}
                    </option>
                @endforeach
            </select>
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

        <div class="row project-list-container">
            @forelse ($projects as $project)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('public.project.show', $project->slug) }}" class="card-link">
                        <div class="project-card">
                            <div class="img-container-projects-page">
                                @php($coverUrl = $project->coverUrl())
                                @if ($coverUrl)
                                    <img src="{{ $coverUrl }}" class="card-img-top" alt="{{ $project->title }}">
                                @else
                                    <div class="project-card-cover-fallback" aria-label="Sem capa"></div>
                                @endif
                            </div>
                            <div class="card-body card-body-projects-page transparent-bg text-start">
                                <h5 class="card-title">{{ $project->title }}</h5>
                                <p class="card-text">{{ "$project->location, $project->year" }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-center">Nenhum projeto encontrado para esta categoria.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('categorySelect').addEventListener('change', function () {
            window.location.href = this.value;
        });
    });
</script>

