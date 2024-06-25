@extends('admin-layout')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Projeto: {{ $project->title }}</h1>
        <a href="{{ route('admin.projetos.index') }}" class="btn btn-secondary">Voltar</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <ul class="nav nav-tabs" id="projectTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="view-tab" data-bs-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="true">Visualizar</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Editar</a>
        </li>
    </ul>

    <div class="tab-content" id="projectTabsContent">
        <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
            <div class="mt-4">
                <h5 class="text-white">Título:</h5>
                <p class="text-white">{{ $project->title }}</p>

                <h5 class="text-white">Localização:</h5>
                <p class="text-white">{{ $project->location }}</p>

                <h5 class="text-white">Categoria:</h5>
                <p class="text-white">{{ $project->category->name ?? 'Categoria não encontrada' }}</p>

                <h5 class="text-white">Área:</h5>
                <p class="text-white">{{ $project->area }} m²</p>

                <h5 class="text-white">Status:</h5>
                <p class="text-white">{{ $project->status ? 'Ativo' : 'Inativo' }}</p>

                <h5 class="text-white">Descrição:</h5>
                <p class="text-white">{{ $project->description }}</p>

                <h5 class="text-white">Data:</h5>
                <p class="text-white">{{ $project->year }}</p>

                @if ($project->cover)
                    <h5 class="text-white">Capa:</h5>
                    <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="150" data-bs-toggle="modal" data-bs-target="#coverImageModal">
                @endif
            </div>

            <div class="mt-4">
                <h5 class="text-white">Adicionar Imagem</h5>
                <form action="{{ route('admin.projetos.addImage', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="images" class="form-label text-white">Imagens</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Imagens</button>
                </form>
            </div>

            <div class="mt-4">
                <h5 class="text-white">Imagens do Projeto</h5>
                <div class="row">
                    @foreach ($project->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ asset($image->path) }}" class="card-img-top" alt="{{ $image->file_name }}" data-bs-toggle="modal" data-bs-target="#projectImageModal-{{ $image->id }}">
                                <div class="card-body">
                                    <form action="{{ route('admin.projetos.deleteImage', ['projectId' => $project->id, 'imageId' => $image->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for project images -->
                        <div class="modal fade" id="projectImageModal-{{ $image->id }}" tabindex="-1" aria-labelledby="projectImageModalLabel-{{ $image->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
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
        </div>

        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <form action="{{ route('admin.projetos.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="title" class="form-label text-white">Título</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="cover" class="form-label text-white">Capa</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        @if ($project->cover)
                            <div class="mt-2">
                                <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="150">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label text-white">Localização</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $project->location) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="area" class="form-label text-white">Área (m²)</label>
                        <input type="number" class="form-control" id="area" name="area" value="{{ old('area', $project->area) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label text-white">Categoria</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == old('category_id', $project->category_id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label text-white">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1" {{ old('status', $project->status) ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ !old('status', $project->status) ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label text-white">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label text-white">Data</label>
                        <input type="text" class="form-control" id="date" name="date" value="{{ old('date', $project->year) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Atualizar Projeto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for cover image -->
<div class="modal fade" id="coverImageModal" tabindex="-1" aria-labelledby="coverImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
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
@endsection
