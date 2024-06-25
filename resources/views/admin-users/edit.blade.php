@extends('admin-layout')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Usuário: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Voltar</a>
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

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="view-tab" data-bs-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="true">Visualizar</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Editar</a>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
            <div class="mt-4">
                <h5 class="text-white">Nome:</h5>
                <p class="text-white">{{ $user->name }}</p>

                <h5 class="text-white">Email:</h5>
                <p class="text-white">{{ $user->email }}</p>

                <h5 class="text-white">Descrição:</h5>
                <p class="text-white">{{ $user->description }}</p>

                <h5 class="text-white">Admin:</h5>
                <p class="text-white">{{ $user->ownership ? 'Sim' : 'Não' }}</p>

                @if ($user->cover_path)
                    <h5 class="text-white">Imagem de Perfil:</h5>
                    <img src="{{ $user->cover_path }}" alt="{{ $user->name }} Cover" width="150" data-bs-toggle="modal" data-bs-target="#coverImageModal">
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted">Deixe em branco se não deseja alterar a senha.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-white">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="mb-3">
                        <label for="cover_path" class="form-label text-white">Imagem de Perfil</label>
                        <input type="file" class="form-control" id="cover_path" name="cover_path" accept="image/*">
                        @if ($user->cover_path)
                            <div class="mt-2">
                                <img src="{{ $user->cover_path }}" alt="{{ $user->name }} Cover" width="150">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label text-white">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $user->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="ownership" class="form-label text-white">Ownership</label>
                        <select class="form-control" id="ownership" name="ownership" required>
                            <option value="1" {{ old('ownership', $user->ownership) == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('ownership', $user->ownership) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for cover image -->
@if ($user->cover_path)
    <div class="modal fade" id="coverImageModal" tabindex="-1" aria-labelledby="coverImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img src="{{ $user->cover_path }}" class="img-fluid w-100" style="height: auto;" alt="{{ $user->name }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
