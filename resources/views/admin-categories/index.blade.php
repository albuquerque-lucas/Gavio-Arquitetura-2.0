@extends('admin-layout')

@section('content')
<div class="container mt-5 category-list-page">
    <section class="category-list-hero">
        <div>
            <h1 class="category-list-title">Categorias</h1>
            <p class="category-list-subtitle">Organize os tipos de projeto usados na vitrine.</p>
        </div>
        <div class="category-list-stats" aria-label="Resumo de categorias">
            <span class="category-list-stat-label">Total</span>
            <strong class="category-list-stat-value">{{ $categories->total() }}</strong>
        </div>
    </section>

    <section class="category-toolbar">
        <button id="toggleFormButton" class="category-btn category-btn-primary" type="button">
            <i class="fa-solid fa-plus"></i>
            <span>Nova categoria</span>
        </button>
    </section>

    <section class="category-messages" aria-live="polite">
        @if (session('success'))
            <div class="category-alert category-alert-success alert alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="category-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="category-alert category-alert-danger alert alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="category-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </section>

    <section id="categoryForm" class="category-form-panel" hidden>
        <h2>Nova categoria</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="category-form-grid">
            @csrf
            <div class="category-field">
                <label for="name">Nome</label>
                <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <button type="submit" class="category-btn category-btn-primary">Salvar categoria</button>
        </form>
    </section>

    <section class="category-table-surface mt-4">
        <div class="table-responsive">
            <table class="table category-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="actions-col">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="category-row" data-category-id="{{ $category->id }}">
                            <th scope="row">{{ $category->id }}</th>
                            <td>
                                <span class="category-name" data-original-name="{{ $category->name }}">{{ $category->name }}</span>
                                <input type="text" class="form-control category-input-field is-hidden" value="{{ $category->name }}" aria-label="Editar nome da categoria {{ $category->name }}">
                            </td>
                            <td>
                                <div class="category-actions">
                                    <div class="category-actions-menu-wrapper">
                                        <button
                                            type="button"
                                            class="category-actions-trigger"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            aria-label="Abrir menu de acoes"
                                        >
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <div class="category-actions-menu" role="menu">
                                            <button type="button" class="category-action-item edit-button" role="menuitem">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                                <span>Editar</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="category-action-item category-action-item-danger delete-button"
                                                role="menuitem"
                                                data-category-id="{{ $category->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal"
                                            >
                                                <i class="fa-regular fa-trash-can"></i>
                                                <span>Excluir</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="category-edit-controls is-hidden">
                                        <button type="button" class="category-btn category-btn-ghost cancel-button">Cancelar</button>
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="category-update-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="name" class="category-input-name">
                                            <button type="submit" class="category-btn category-btn-primary save-button">Salvar</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="category-empty-state">
                                    <i class="fa-regular fa-folder-open"></i>
                                    <p>Nenhuma categoria cadastrada.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="category-pagination-wrap">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>

@include('components.confirm-delete-modal-category')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleFormButton = document.getElementById('toggleFormButton');
        const categoryForm = document.getElementById('categoryForm');

        toggleFormButton.addEventListener('click', function () {
            const isHidden = categoryForm.hasAttribute('hidden');
            if (isHidden) {
                categoryForm.removeAttribute('hidden');
                return;
            }

            categoryForm.setAttribute('hidden', 'hidden');
            const form = categoryForm.querySelector('form');
            if (form) {
                form.reset();
            }
        });

        const menuWrappers = document.querySelectorAll('.category-actions-menu-wrapper');

        function closeAllMenus() {
            menuWrappers.forEach((wrapper) => {
                wrapper.classList.remove('is-open');
                const trigger = wrapper.querySelector('.category-actions-trigger');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        }

        menuWrappers.forEach((wrapper) => {
            const trigger = wrapper.querySelector('.category-actions-trigger');
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

        document.querySelectorAll('.edit-button').forEach((button) => {
            button.addEventListener('click', function () {
                const row = button.closest('.category-row');
                const name = row.querySelector('.category-name');
                const input = row.querySelector('.category-input-field');
                const menuWrapper = row.querySelector('.category-actions-menu-wrapper');
                const controls = row.querySelector('.category-edit-controls');

                input.value = name.dataset.originalName || name.textContent.trim();
                name.classList.add('is-hidden');
                input.classList.remove('is-hidden');
                menuWrapper.classList.add('is-hidden');
                controls.classList.remove('is-hidden');
                input.focus();
                closeAllMenus();
            });
        });

        document.querySelectorAll('.cancel-button').forEach((button) => {
            button.addEventListener('click', function () {
                const row = button.closest('.category-row');
                const name = row.querySelector('.category-name');
                const input = row.querySelector('.category-input-field');
                const menuWrapper = row.querySelector('.category-actions-menu-wrapper');
                const controls = row.querySelector('.category-edit-controls');

                input.value = name.dataset.originalName || name.textContent.trim();
                name.classList.remove('is-hidden');
                input.classList.add('is-hidden');
                menuWrapper.classList.remove('is-hidden');
                controls.classList.add('is-hidden');
            });
        });

        document.querySelectorAll('.category-update-form').forEach((form) => {
            form.addEventListener('submit', function () {
                const row = form.closest('.category-row');
                const input = row.querySelector('.category-input-field');
                const hiddenInput = row.querySelector('.category-input-name');
                hiddenInput.value = input.value.trim();
            });
        });
    });
</script>
@endsection
