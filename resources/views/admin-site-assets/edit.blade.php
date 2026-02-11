@extends('admin-layout')

@section('content')
<div class="container mt-5 appearance-page">
    <section class="appearance-hero">
        <div>
            <h1 class="appearance-title">Aparencia do Site</h1>
            <p class="appearance-subtitle">Atualize background da home e logos principais sem editar codigo.</p>
        </div>
    </section>

    <section class="appearance-messages" aria-live="polite">
        @if (session('success'))
            <div class="appearance-alert appearance-alert-success alert alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="appearance-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="appearance-alert appearance-alert-danger alert alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="appearance-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </section>

    <section class="appearance-form-surface">
        <div class="appearance-form-grid">
            <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" class="appearance-field appearance-upload-form">
                @csrf
                <label for="home_background">Background Home</label>
                <input type="file" id="home_background" name="home_background" class="form-control js-asset-input" data-preview-target="preview-home-background" accept="image/*">
                <small>JPG, PNG ou WEBP. O sistema comprime automaticamente no upload.</small>
                <small class="appearance-file-name">Arquivo atual: {{ $assets['home_background_name'] ?? 'nenhum arquivo salvo' }}</small>
                <div class="appearance-preview appearance-preview-background">
                    <img id="preview-home-background" src="{{ $assets['home_background_url'] }}" alt="Preview background home">
                </div>
                <button type="submit" class="appearance-btn appearance-btn-primary">Salvar Background</button>
            </form>

            <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" class="appearance-field appearance-upload-form">
                @csrf
                <label for="brand_logo_icon">Logo Icone</label>
                <input type="file" id="brand_logo_icon" name="brand_logo_icon" class="form-control js-asset-input" data-preview-target="preview-logo-icon" accept="image/*">
                <small>Usada na home (simbolo). Compressao automatica no upload.</small>
                <small class="appearance-file-name">Arquivo atual: {{ $assets['brand_logo_icon_name'] ?? 'nenhum arquivo salvo' }}</small>
                <div class="appearance-preview appearance-preview-logo">
                    <img id="preview-logo-icon" src="{{ $assets['brand_logo_icon_url'] }}" alt="Preview logo icone">
                </div>
                <button type="submit" class="appearance-btn appearance-btn-primary">Salvar Logo Icone</button>
            </form>

            <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" class="appearance-field appearance-upload-form">
                @csrf
                <label for="brand_logo_written">Logo Escrita</label>
                <input type="file" id="brand_logo_written" name="brand_logo_written" class="form-control js-asset-input" data-preview-target="preview-logo-written" accept="image/*">
                <small>Usada no topo e home (logo escrita). Compressao automatica no upload.</small>
                <small class="appearance-file-name">Arquivo atual: {{ $assets['brand_logo_written_name'] ?? 'nenhum arquivo salvo' }}</small>
                <div class="appearance-preview appearance-preview-logo">
                    <img id="preview-logo-written" src="{{ $assets['brand_logo_written_url'] }}" alt="Preview logo escrita">
                </div>
                <button type="submit" class="appearance-btn appearance-btn-primary">Salvar Logo Escrita</button>
            </form>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.js-asset-input');

        inputs.forEach((input) => {
            input.addEventListener('change', function () {
                const file = input.files && input.files[0] ? input.files[0] : null;
                if (!file || !file.type.startsWith('image/')) {
                    return;
                }

                const previewId = input.getAttribute('data-preview-target');
                const previewImage = document.getElementById(previewId);
                if (!previewImage) {
                    return;
                }

                previewImage.src = URL.createObjectURL(file);
            });
        });
    });
</script>
@endsection
