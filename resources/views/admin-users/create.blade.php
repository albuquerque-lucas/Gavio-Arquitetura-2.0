@extends('admin-layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-white my-3">Novo Usuario</h1>
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label text-white">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label text-white">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label text-white">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-white">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label text-white">Confirmar Senha</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label for="cover_path" class="form-label text-white">Imagem de Perfil</label>
            <input type="file" class="form-control" id="cover_path" name="cover_path" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label text-white">Descricao</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="ownership" name="ownership" value="1" {{ old('ownership') ? 'checked' : '' }}>
            <label class="form-check-label text-white" for="ownership">Exibir este usuario na pagina "sobre"</label>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Usuario</button>
    </form>
</div>
@endsection
