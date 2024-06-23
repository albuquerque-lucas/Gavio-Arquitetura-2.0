@extends('public-layout')

@section('content')
<section class="project-show-container">
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <a href="{{ route('public.projects', $project->category->slug) }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-center">
                <div class="cover-image-container">
                    <img src="{{ $project->coverUrl() }}" class="cover-image" alt="{{ $project->title }}" data-bs-toggle="modal" data-bs-target="#coverImageModal">
                </div>
            </div>
        </div>
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h1 class="text-dark">{{ $project->title }}</h1>
            </div>
        </div>
        <div class="row info-row">
            <div class="col-md-6 info-cell technicals">
                <h3>Ficha Técnica</h3>
                <p><strong>Data:</strong> {{ $project->date }}</p>
                <p><strong>Localização:</strong> {{ $project->location }}</p>
                <p><strong>Categoria:</strong> {{ $project->category->name }}</p>
                {{-- <p><strong>Status:</strong> {{ $project->status ? 'Ativo' : 'Inativo' }}</p> --}}
            </div>
            <div class="col-md-6 info-cell">
                <h3 class="text-dark">Descrição</h3>
                <p>{{ $project->description }}</p>
            </div>
        </div>
        <div class="row">
            @foreach ($project->images as $image)
                <div class="col-md-4 mb-4">
                    <div class="img-container project-img-list-item">
                        <img src="{{ asset($image->path) }}" class="img-fluid project-image" alt="{{ $image->file_name }}" data-bs-toggle="modal" data-bs-target="#projectImageModal-{{ $image->id }}">
                    </div>
                </div>

                <!-- Modal for project images -->
                <div class="modal fade" id="projectImageModal-{{ $image->id }}" tabindex="-1" aria-labelledby="projectImageModalLabel-{{ $image->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-body">
                                <img src="{{ asset($image->path) }}" class="img-fluid" alt="{{ $image->file_name }}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal for cover image -->
    <div class="modal fade" id="coverImageModal" tabindex="-1" aria-labelledby="coverImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ $project->coverUrl() }}" class="img-fluid" alt="{{ $project->title }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
