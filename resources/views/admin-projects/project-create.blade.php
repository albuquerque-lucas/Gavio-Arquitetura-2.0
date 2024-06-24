@extends('admin-layout')

@section('content')
    <div class="container mt-5 mb-5">
        <div>
            <h1 class="text-white my-3">Novo Projeto</h1>
            <button onclick="window.location.href='{{ route('admin.projetos.index') }}'" class="btn btn-secondary my-3">Voltar</button>
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

        <form action="{{ route('admin.projetos.store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label text-white">Título</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label text-white">Capa</label>
                <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="location" class="form-label text-white">Localização</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label text-white">Categoria</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label text-white">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label text-white">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label text-white">Ano</label>
                <input type="number" class="form-control" id="year" name="year" min="1900" max="{{ date('Y') }}">
            </div>
            <button type="submit" class="btn btn-primary">Criar Projeto</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('projectForm').reset();">Limpar</button>
        </form>
    </div>
@endsection
