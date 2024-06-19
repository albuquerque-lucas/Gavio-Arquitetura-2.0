@extends('admin-layout')

@section('extra-css')
    @vite('resources/scss/admin/category-list.scss')
@endsection

@section('content')
<div class="container mt-5">
    <div>
        <h1 class="text-white my-3">Categorias</h1>
        <button id="toggleFormButton" class="btn btn-primary my-3">Nova Categoria</button>
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

    <div id="categoryForm" class="mt-4" style="display: none;">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label text-white">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Categoria</button>
        </form>
    </div>

    <table class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row">{{ $category->id }}</th>
                    <td>
                        <span class="category-name">{{ $category->name }}</span>
                        <input type="text" class="form-control category-input d-none" value="{{ $category->name }}">
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm edit-button">Editar</a>
                        <button class="btn btn-danger btn-sm delete-button" data-category-id="{{ $category->id }}" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                            Excluir
                        </button>
                        <button class="btn btn-secondary btn-sm d-none cancel-button">Cancelar</button>
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="d-inline category-update-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="name" class="category-input-name">
                            <button type="submit" class="btn btn-success btn-sm d-none save-button">Salvar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleFormButton = document.getElementById('toggleFormButton');
        const categoryForm = document.getElementById('categoryForm');

        toggleFormButton.addEventListener('click', function () {
            if (categoryForm.style.display === 'none' || categoryForm.style.display === '') {
                categoryForm.style.display = 'block';
            } else {
                categoryForm.style.display = 'none';
                categoryForm.querySelector('form').reset();
            }
        });

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const row = this.closest('tr');
                row.querySelector('.category-name').classList.add('d-none');
                row.querySelector('.category-input').classList.remove('d-none');
                row.querySelector('.edit-button').classList.add('d-none');
                row.querySelector('.btn-danger').classList.add('d-none');
                row.querySelector('.cancel-button').classList.remove('d-none');
                row.querySelector('.save-button').classList.remove('d-none');
            });
        });

        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                row.querySelector('.category-name').classList.remove('d-none');
                row.querySelector('.category-input').classList.add('d-none');
                row.querySelector('.edit-button').classList.remove('d-none');
                row.querySelector('.btn-danger').classList.remove('d-none');
                row.querySelector('.cancel-button').classList.add('d-none');
                row.querySelector('.save-button').classList.add('d-none');
            });
        });

        document.querySelectorAll('.save-button').forEach(button => {
            button.addEventListener('click', function (e) {
                const row = this.closest('tr');
                const categoryInput = row.querySelector('.category-input').value;
                row.querySelector('.category-input-name').value = categoryInput;
            });
        });
    });
</script>

@include('components.confirm-delete-modal-category')
@endsection
