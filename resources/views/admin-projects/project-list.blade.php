@extends('admin-layout')

@section('content')
<div class="container mt-5 project-list-page">
    <section class="project-list-hero">
        <div>
            <h1 class="project-list-title">Projetos</h1>
            <p class="project-list-subtitle">Gerencie projetos, capas e ordenacao da vitrine.</p>
        </div>
        <div class="project-list-stats" aria-label="Resumo de projetos">
            <span class="project-list-stat-label">Total</span>
            <strong class="project-list-stat-value">{{ $projects->total() }}</strong>
        </div>
    </section>

    <section class="project-list-toolbar">
        <form id="filterForm" method="GET" action="{{ route('admin.projetos.index') }}" class="project-filters">
            <div class="project-field search-field">
                <label for="search">Pesquisar</label>
                <input id="search" type="text" name="search" placeholder="Nome do projeto" value="{{ request('search') }}" class="form-control">
            </div>

            <div class="project-field">
                <label for="category_id">Categoria</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="project-field compact-field">
                <label for="sort_by">Ordenar por</label>
                <select id="sort_by" name="sort_by" class="form-select">
                    <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                    <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Ordem</option>
                </select>
            </div>

            <div class="project-field compact-field">
                <label for="order">Direcao</label>
                <select id="order" name="order" class="form-select">
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                </select>
            </div>

            <div class="project-actions-inline">
                <button type="submit" class="project-btn project-btn-ghost">Filtrar</button>
                <button id="deleteSelected" type="button" class="project-btn project-btn-danger" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">Excluir selecionados</button>
                <a href="{{ route('admin.projetos.create') }}" class="project-btn project-btn-primary">Novo projeto</a>
            </div>
        </form>
    </section>

    <section class="project-messages" aria-live="polite">
        @if (session('success'))
            <div class="project-alert project-alert-success alert alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="project-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="project-alert project-alert-danger alert alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="project-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </section>

    @if($hasPages)
        <div class="project-pagination-wrap">
            {{ $projects->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <section class="project-table-surface">
        <div class="table-responsive">
            <table class="table project-table">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="selectAll"></th>
                        <th scope="col">#</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Capa</th>
                        <th scope="col">Localizacao</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Ordem</th>
                        <th scope="col">Editar / Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td><input type="checkbox" name="selected_projects[]" value="{{ $project->id }}" class="project-checkbox"></td>
                            <th scope="row">{{ $project->id }}</th>
                            <td>{{ $project->title ?? 'Titulo nao encontrado' }}</td>
                            <td>
                                @if ($project->coverUrl())
                                    <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" class="project-cover-thumb">
                                @else
                                    Imagem nao disponivel
                                @endif
                            </td>
                            <td>{{ $project->location ?? 'Localizacao nao encontrada' }}</td>
                            <td>{{ $project->category->name ?? 'Categoria nao encontrada' }}</td>
                            <td>
                                <form action="{{ route('admin.projetos.updateOrder', $project->id) }}" method="POST" class="project-order-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="order" value="{{ $project->order }}" class="form-control project-order-input">
                                    <button type="submit" class="project-icon-btn" aria-label="Atualizar ordem">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="project-row-actions">
                                    <a href="{{ route('admin.projetos.edit', $project->id) }}" class="project-icon-btn" aria-label="Editar projeto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="project-icon-btn project-icon-btn-danger delete-button" data-project-id="{{ $project->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" aria-label="Excluir projeto">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="project-empty-state">
                                    <i class="fa-regular fa-folder-open"></i>
                                    <p>Nenhum projeto encontrado com os filtros atuais.</p>
                                    <a href="{{ route('admin.projetos.create') }}" class="project-btn project-btn-primary">Criar primeiro projeto</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@include('components.confirm-delete-modal', ['text' => 'Tem certeza que deseja excluir este projeto?'])
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

        const deleteButtons = document.querySelectorAll('.delete-button');
        const deleteForm = document.getElementById('delete-form');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const projectId = button.getAttribute('data-project-id');
                const deleteAction = "{{ route('admin.projetos.destroy', ':id') }}".replace(':id', projectId);
                deleteForm.setAttribute('action', deleteAction);
            });
        });
    });
</script>
@endsection
