@extends('admin-layout')

@section('content')
<div class="container mt-5 mb-5 project-create-page">
    <section class="project-create-hero">
        <div>
            <h1 class="project-create-title">Novo Projeto</h1>
            <p class="project-create-subtitle">Cadastre um novo projeto para exibir no portfolio.</p>
        </div>
        <a href="{{ route('admin.projetos.index') }}" class="project-create-btn project-create-btn-ghost">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Voltar</span>
        </a>
    </section>

    <section class="project-create-messages" aria-live="polite">
        @if (session('success'))
            <div class="project-create-alert project-create-alert-success alert alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="project-create-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="project-create-alert project-create-alert-danger alert alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="project-create-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="project-create-alert project-create-alert-danger alert alert-dismissible fade show" role="alert">
                <div>
                    <strong>Corrija os campos abaixo:</strong>
                    <ul class="project-create-error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="project-create-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </section>

    <section class="project-create-surface">
        <form action="{{ route('admin.projetos.store') }}" method="POST" enctype="multipart/form-data" id="projectForm" class="project-create-form">
            @csrf

            <div class="project-create-grid">
                <div class="project-create-field">
                    <label for="title">Titulo</label>
                    <input type="text" class="project-create-input" id="title" name="title" value="{{ old('title') }}" placeholder="Ex: Casa Jardim" required>
                </div>

                <div class="project-create-field">
                    <label for="category_id">Categoria</label>
                    <select class="project-create-input project-create-select" id="category_id" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="project-create-field">
                    <label for="location">Localizacao</label>
                    <input type="text" class="project-create-input" id="location" name="location" value="{{ old('location') }}" placeholder="Ex: Sao Mateus, Juiz de Fora - MG" required>
                </div>

                <div class="project-create-field project-create-field-inline">
                    <div>
                        <label for="year">Ano</label>
                        <input type="number" class="project-create-input" id="year" name="year" min="1900" max="{{ date('Y') }}" value="{{ old('year') }}" placeholder="{{ date('Y') }}">
                    </div>
                    <div>
                        <label for="area">Area (m²)</label>
                        <input type="number" class="project-create-input" id="area" name="area" value="{{ old('area') }}" placeholder="150">
                    </div>
                </div>

                <div class="project-create-field project-create-field-full">
                    <label for="cover">Capa</label>
                    <input type="file" class="project-create-input" id="cover" name="cover" accept="image/*">
                    <small class="project-create-help">JPG, PNG ou WEBP.</small>
                    <div class="project-create-cover-preview" id="coverPreviewWrap" hidden>
                        <img id="coverPreview" src="" alt="Preview da capa">
                    </div>
                </div>

                <div class="project-create-field project-create-field-full">
                    <label for="description">Descricao</label>
                    <textarea class="project-create-input project-create-textarea" id="description" name="description" rows="6" oninput="updateCharacterCount()" placeholder="Descreva conceito, materiais e detalhes do projeto.">{{ old('description') }}</textarea>
                    <small class="project-create-help" id="charCount">0 caracteres</small>
                </div>
            </div>

            <div class="project-create-actions">
                <button type="button" class="project-create-btn project-create-btn-ghost" onclick="document.getElementById('projectForm').reset(); resetCoverPreview(); updateCharacterCount();">Limpar</button>
                <button type="submit" class="project-create-btn project-create-btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Criar Projeto</span>
                </button>
            </div>
        </form>
    </section>
</div>

<script>
    function updateCharacterCount() {
        const description = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        charCount.textContent = `${description.value.length} caracteres`;
    }

    function resetCoverPreview() {
        const wrap = document.getElementById('coverPreviewWrap');
        const img = document.getElementById('coverPreview');
        const input = document.getElementById('cover');

        if (img) {
            img.src = '';
        }

        if (wrap) {
            wrap.hidden = true;
        }

        if (input) {
            input.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateCharacterCount();

        const coverInput = document.getElementById('cover');
        const previewWrap = document.getElementById('coverPreviewWrap');
        const previewImg = document.getElementById('coverPreview');

        if (coverInput && previewWrap && previewImg) {
            coverInput.addEventListener('change', function () {
                const file = coverInput.files && coverInput.files[0] ? coverInput.files[0] : null;
                if (!file || !file.type.startsWith('image/')) {
                    resetCoverPreview();
                    return;
                }

                previewImg.src = URL.createObjectURL(file);
                previewWrap.hidden = false;
            });
        }
    });
</script>
@endsection
