@extends('admin-layout')

@section('content')
<div class="container mt-5">
    <div>
        <h1 class="text-white my-3">Usuários</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary my-3">Novo Usuário</a>
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

    <table class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm delete-button" data-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Excluir</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>

@include('components.confirm-delete-modal-user')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteUserModal = document.getElementById('deleteUserModal');
        deleteUserModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            let userId = button.getAttribute('data-user-id');
            let form = deleteUserModal.querySelector('#delete-user-form');
            form.action = `/admin/users/${userId}`;
        });
    });
</script>
