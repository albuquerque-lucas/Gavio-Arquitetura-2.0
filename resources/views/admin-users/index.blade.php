@extends('admin-layout')

@section('content')
<div class="container mt-5 user-list-page">
    <section class="user-list-hero">
        <div>
            <h1 class="user-list-title">Usuarios</h1>
            <p class="user-list-subtitle">Gerencie contas administrativas da aplicacao.</p>
        </div>
        <div class="user-list-stats" aria-label="Resumo de usuarios">
            <span class="user-list-stat-label">Total</span>
            <strong class="user-list-stat-value">{{ $users->total() }}</strong>
        </div>
    </section>

    <section class="user-toolbar">
        <a href="{{ route('admin.users.create') }}" class="user-btn user-btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Novo usuario</span>
        </a>
    </section>

    <section class="user-messages" aria-live="polite">
        @if (session('success'))
            <div class="user-alert user-alert-success alert alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="user-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="user-alert user-alert-danger alert alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="user-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </section>

    <section class="user-table-surface mt-4">
        <div class="user-table-wrap">
            <table class="table user-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="actions-col">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="user-actions-menu-wrapper">
                                    <button
                                        type="button"
                                        class="user-actions-trigger"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        aria-label="Abrir menu de acoes"
                                    >
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="user-actions-menu" role="menu">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="user-action-item" role="menuitem">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            type="button"
                                            class="user-action-item user-action-item-danger delete-button"
                                            role="menuitem"
                                            data-user-id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal"
                                        >
                                            <i class="fa-regular fa-trash-can"></i>
                                            <span>Excluir</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="user-empty-state">
                                    <i class="fa-regular fa-folder-open"></i>
                                    <p>Nenhum usuario cadastrado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="user-pagination-wrap">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

@include('components.confirm-delete-modal-user')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuWrappers = document.querySelectorAll('.user-actions-menu-wrapper');

        function closeAllMenus() {
            menuWrappers.forEach((wrapper) => {
                wrapper.classList.remove('is-open');
                const trigger = wrapper.querySelector('.user-actions-trigger');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        }

        menuWrappers.forEach((wrapper) => {
            const trigger = wrapper.querySelector('.user-actions-trigger');
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

        const deleteUserModal = document.getElementById('deleteUserModal');
        deleteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const form = deleteUserModal.querySelector('#delete-user-form');
            form.action = `/admin/users/${userId}`;
            closeAllMenus();
        });
    });
</script>
@endsection
