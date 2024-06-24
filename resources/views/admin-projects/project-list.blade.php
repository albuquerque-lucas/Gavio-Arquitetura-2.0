@extends('admin-layout')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-white my-3">Projetos</h1>
            <div>
                <form id="filterForm" method="GET" action="{{ route('admin.projetos.index') }}" class="d-inline">
                    <input type="text" name="search" placeholder="Pesquisar por nome" value="{{ request('search') }}" class="form-control d-inline w-auto btn-spacing">

                    <select name="category_id" class="form-select d-inline w-auto btn-spacing">
                        <option value="">Todos</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select name="sort_by" class="form-select d-inline w-auto btn-spacing">
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Ordem</option>
                    </select>

                    <select name="order" class="form-select d-inline w-auto btn-spacing">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                    </select>

                    <button type="submit" class="btn btn-secondary btn-spacing">Filtrar</button>
                </form>
                <button id="deleteSelected" class="btn btn-danger btn-spacing" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">Excluir Selecionados</button>
                <a href="{{ route('admin.projetos.create') }}" class="btn btn-secondary btn-spacing">Novo Projeto</a>
            </div>
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

        <div class="my-5 text-white d-flex align-items-center justify-content-center" style="height: 4rem">
            {{ $projects->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" id="selectAll"></th>
                    <th scope="col">#</th>
                    <th scope="col">Título</th>
                    <th scope="col">Capa</th>
                    <th scope="col">Localização</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Ordem</th>
                    <th scope="col">Status</th>
                    <th scope="col">Editar / Excluir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td><input type="checkbox" name="selected_projects[]" value="{{ $project->id }}" class="project-checkbox"></td>
                        <th scope="row">{{ $project->id }}</th>
                        <td>{{ $project->title ?? 'Título não encontrado' }}</td>
                        <td>
                            @if ($project->coverUrl())
                                <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="100">
                            @else
                                Imagem não disponível
                            @endif
                        </td>
                        <td>{{ $project->location ?? 'Localização não encontrada' }}</td>
                        <td>{{ $project->category->name ?? 'Categoria não encontrada' }}</td>
                        <td>
                            <form action="{{ route('admin.projetos.updateOrder', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="order" value="{{ $project->order }}" class="form-control d-inline w-auto">
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.projetos.toggleCarousel', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                                    @if ($project->status)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.projetos.edit', $project->id) }}" class="btn btn-primary btn-sm btn-spacing">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm btn-spacing delete-button" data-project-id="{{ $project->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('components.confirm-delete-modal')
    @include('components.confirm-bulk-delete-project-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.project-checkbox');
            const deleteSelectedButton = document.getElementById('deleteSelected');
            const selectedProjectsInput = document.getElementById('selected_projects');
            let selectedProjects = [];

            selectAll.addEventListener('change', function () {
                selectedProjects = [];
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                    if (selectAll.checked) {
                        selectedProjects.push(checkbox.value);
                    }
                });
                toggleDeleteButton();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (checkbox.checked) {
                        selectedProjects.push(checkbox.value);
                    } else {
                        selectedProjects = selectedProjects.filter(id => id !== checkbox.value);
                    }
                    toggleDeleteButton();
                });
            });

            function toggleDeleteButton() {
                const anyChecked = selectedProjects.length > 0;
                deleteSelectedButton.disabled = !anyChecked;
            }

            deleteSelectedButton.addEventListener('click', function () {
                selectedProjectsInput.value = JSON.stringify(selectedProjects);
            });
        });
    </script>
@endsection
