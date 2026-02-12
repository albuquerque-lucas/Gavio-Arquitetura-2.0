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
        <form id="filterForm" method="GET" action="{{ route('admin.projetos.index') }}">
            <div class="project-filters">
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

                <div class="project-filter-submit">
                    <button type="submit" class="project-btn project-btn-ghost">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="project-actions-inline">
            <button id="toggleReorderMode" type="button" class="project-btn project-btn-primary project-btn-reorder">Modo reordenar</button>
            <button id="saveReorder" type="button" class="project-btn project-btn-primary" disabled hidden>Salvar ordem</button>
            <button id="deleteSelected" type="button" class="project-btn project-btn-danger" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">Excluir selecionados</button>
            <a href="{{ route('admin.projetos.create') }}" class="project-btn project-btn-primary">Novo projeto</a>
        </div>
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
                        <th scope="col" class="actions-col">Acoes</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    @forelse ($projects as $project)
                        <tr data-project-uuid="{{ $project->uuid }}">
                            <td><input type="checkbox" name="selected_projects[]" value="{{ $project->uuid }}" class="project-checkbox"></td>
                            <th scope="row">{{ $project->id }}</th>
                            <td>{{ $project->title ?? 'Titulo nao encontrado' }}</td>
                            <td>
                                @php($coverUrl = $project->coverUrl())
                                @if ($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="{{ $project->title }} Cover" class="project-cover-thumb">
                                @else
                                    <div class="project-cover-thumb project-cover-thumb-fallback" aria-label="Sem capa"></div>
                                @endif
                            </td>
                            <td>{{ $project->location ?? 'Localizacao nao encontrada' }}</td>
                            <td>{{ $project->category->name ?? 'Categoria nao encontrada' }}</td>
                            <td class="project-actions">
                                <div class="project-actions-menu-wrapper">
                                    <button
                                        type="button"
                                        class="project-actions-trigger"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        aria-label="Abrir menu de acoes"
                                    >
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="project-actions-menu" role="menu">
                                        <a href="{{ route('admin.projetos.edit', $project) }}" class="project-action-item" role="menuitem">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            type="button"
                                            class="project-action-item project-action-item-danger delete-button"
                                            role="menuitem"
                                            data-project-uuid="{{ $project->uuid }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal"
                                        >
                                            <i class="fa-regular fa-trash-can"></i>
                                            <span>Excluir</span>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="project-drag-handle" aria-label="Arrastar para reordenar" title="Arraste para reordenar">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
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

        const menuWrappers = document.querySelectorAll('.project-actions-menu-wrapper');
        const deleteButtons = document.querySelectorAll('.delete-button');
        const deleteForm = document.getElementById('delete-form');
        const tableBody = document.getElementById('projectsTableBody');
        const toggleReorderButton = document.getElementById('toggleReorderMode');
        const saveReorderButton = document.getElementById('saveReorder');
        let sortableInstance = null;
        let hasReorderChanges = false;

        function closeAllMenus() {
            menuWrappers.forEach((wrapper) => {
                wrapper.classList.remove('is-open');
                const trigger = wrapper.querySelector('.project-actions-trigger');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        }

        menuWrappers.forEach((wrapper) => {
            const trigger = wrapper.querySelector('.project-actions-trigger');
            trigger.addEventListener('click', function (event) {
                event.stopPropagation();
                const willOpen = !wrapper.classList.contains('is-open');
                closeAllMenus();

                if (willOpen) {
                    wrapper.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', function () {
            closeAllMenus();
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const projectId = button.getAttribute('data-project-uuid');
                const deleteAction = "{{ route('admin.projetos.destroy', ':uuid') }}".replace(':uuid', projectId);
                deleteForm.setAttribute('action', deleteAction);
                closeAllMenus();
            });
        });

        function setReorderMode(active) {
            if (!tableBody || !toggleReorderButton || !saveReorderButton) {
                return;
            }

            if (active) {
                sortableInstance = new Sortable(tableBody, {
                    animation: 160,
                    handle: '.project-drag-handle',
                    ghostClass: 'project-row-ghost',
                    chosenClass: 'project-row-chosen',
                    dragClass: 'project-row-dragging',
                    onEnd: function () {
                        hasReorderChanges = true;
                        saveReorderButton.disabled = false;
                    }
                });

                tableBody.classList.add('is-reordering');
                toggleReorderButton.textContent = 'Cancelar reordenacao';
                saveReorderButton.hidden = false;
                saveReorderButton.disabled = true;
                return;
            }

            if (sortableInstance) {
                sortableInstance.destroy();
                sortableInstance = null;
            }

            hasReorderChanges = false;
            tableBody.classList.remove('is-reordering');
            toggleReorderButton.textContent = 'Modo reordenar';
            saveReorderButton.hidden = true;
            saveReorderButton.disabled = true;
        }

        if (toggleReorderButton) {
            toggleReorderButton.addEventListener('click', function () {
                const willActivate = !tableBody.classList.contains('is-reordering');
                setReorderMode(willActivate);
            });
        }

        if (saveReorderButton) {
            saveReorderButton.addEventListener('click', async function () {
                if (!hasReorderChanges) {
                    return;
                }

                const orderedProjects = Array.from(tableBody.querySelectorAll('tr[data-project-uuid]'))
                    .map((row) => row.getAttribute('data-project-uuid'))
                    .filter(Boolean);

                if (!orderedProjects.length) {
                    return;
                }

                saveReorderButton.disabled = true;
                saveReorderButton.textContent = 'Salvando...';

                try {
                    const response = await fetch("{{ route('admin.projetos.reorder') }}", {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ordered_projects: orderedProjects
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Erro ao salvar ordem.');
                    }

                    window.location.reload();
                } catch (error) {
                    saveReorderButton.disabled = false;
                    saveReorderButton.textContent = 'Salvar ordem';
                    alert(error.message || 'Erro ao salvar ordem.');
                }
            });
        }
    });
</script>
@endsection

