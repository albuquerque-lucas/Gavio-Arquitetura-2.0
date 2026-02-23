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
        <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" class="appearance-upload-form">
            @csrf

            <div class="appearance-layout">
                <div class="appearance-config">
                    <div class="appearance-form-grid">
                        <div class="appearance-field">
                            <label for="home_background">Background Home</label>
                            <input type="file" id="home_background" name="home_background" class="form-control js-asset-input" data-preview-target="preview-home-background" accept="image/*">
                            <small>JPG, PNG ou WEBP. Compressao automatica no upload.</small>
                            <small class="appearance-file-name">Arquivo atual: {{ $assets['home_background_name'] ?? 'nenhum arquivo salvo' }}</small>
                        </div>

                        <div class="appearance-field">
                            <label for="brand_logo_icon">Logo Icone</label>
                            <input type="file" id="brand_logo_icon" name="brand_logo_icon" class="form-control js-asset-input" data-preview-target="preview-logo-icon" accept="image/*">
                            <small>Usada na home (simbolo). Compressao automatica no upload.</small>
                            <small class="appearance-file-name">Arquivo atual: {{ $assets['brand_logo_icon_name'] ?? 'nenhum arquivo salvo' }}</small>
                        </div>

                        <div class="appearance-field">
                            <label for="brand_logo_written">Logo Escrita</label>
                            <input type="file" id="brand_logo_written" name="brand_logo_written" class="form-control js-asset-input" data-preview-target="preview-logo-written" accept="image/*">
                            <small>Usada no topo e home (logo escrita). Compressao automatica no upload.</small>
                            <small class="appearance-file-name">Arquivo atual: {{ $assets['brand_logo_written_name'] ?? 'nenhum arquivo salvo' }}</small>
                        </div>

                        <div class="appearance-field">
                            <label for="project_cover_fallback">Capa Padrao Projeto</label>
                            <input type="file" id="project_cover_fallback" name="project_cover_fallback" class="form-control js-asset-input" data-preview-target="preview-project-cover-fallback" accept="image/*">
                            <small>Usada quando um projeto nao tiver capa propria.</small>
                            <small class="appearance-file-name">Arquivo atual: {{ $assets['project_cover_fallback_name'] ?? 'nenhum arquivo salvo' }}</small>
                        </div>
                    </div>
                </div>

                <aside class="appearance-preview-panel">
                    <header class="appearance-preview-header">
                        <h2 class="appearance-preview-title">Pre-visualizacoes</h2>
                        <p class="appearance-preview-subtitle">Miniaturas padronizadas. Clique para ampliar.</p>
                    </header>

                    <div class="appearance-preview-list">
                        <article class="appearance-preview-item">
                            <div class="appearance-preview-thumb js-preview-card">
                                <img id="preview-home-background" src="{{ $assets['home_background_url'] }}" alt="Preview background home">
                                <div class="appearance-preview-placeholder" aria-hidden="true">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Sem imagem</span>
                                </div>
                            </div>
                            <div class="appearance-preview-meta">
                                <h3>Background Home</h3>
                                <button type="button" class="appearance-btn appearance-btn-ghost js-open-preview" data-preview-target="preview-home-background" data-preview-label="Background Home" data-preview-mode="background">
                                    Ver maior
                                </button>
                            </div>
                        </article>

                        <article class="appearance-preview-item">
                            <div class="appearance-preview-thumb js-preview-card">
                                <img id="preview-logo-icon" src="{{ $assets['brand_logo_icon_url'] }}" alt="Preview logo icone">
                                <div class="appearance-preview-placeholder" aria-hidden="true">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Sem imagem</span>
                                </div>
                            </div>
                            <div class="appearance-preview-meta">
                                <h3>Logo Icone</h3>
                                <button type="button" class="appearance-btn appearance-btn-ghost js-open-preview" data-preview-target="preview-logo-icon" data-preview-label="Logo Icone" data-preview-mode="logo-icon">
                                    Ver maior
                                </button>
                            </div>
                        </article>

                        <article class="appearance-preview-item">
                            <div class="appearance-preview-thumb js-preview-card">
                                <img id="preview-logo-written" src="{{ $assets['brand_logo_written_url'] }}" alt="Preview logo escrita">
                                <div class="appearance-preview-placeholder" aria-hidden="true">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Sem imagem</span>
                                </div>
                            </div>
                            <div class="appearance-preview-meta">
                                <h3>Logo Escrita</h3>
                                <button type="button" class="appearance-btn appearance-btn-ghost js-open-preview" data-preview-target="preview-logo-written" data-preview-label="Logo Escrita" data-preview-mode="logo-written">
                                    Ver maior
                                </button>
                            </div>
                        </article>

                        <article class="appearance-preview-item">
                            <div class="appearance-preview-thumb js-preview-card">
                                <img id="preview-project-cover-fallback" src="{{ $assets['project_cover_fallback_url'] }}" alt="Preview capa padrao projeto">
                                <div class="appearance-preview-placeholder" aria-hidden="true">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Sem imagem</span>
                                </div>
                            </div>
                            <div class="appearance-preview-meta">
                                <h3>Capa Padrao Projeto</h3>
                                <button type="button" class="appearance-btn appearance-btn-ghost js-open-preview" data-preview-target="preview-project-cover-fallback" data-preview-label="Capa Padrao Projeto" data-preview-mode="cover">
                                    Ver maior
                                </button>
                            </div>
                        </article>
                    </div>
                </aside>
            </div>

            <div class="appearance-actions">
                <button type="submit" class="appearance-btn appearance-btn-primary appearance-btn-save-all">Salvar Alteracoes</button>
            </div>
        </form>
    </section>
    <div class="appearance-modal" id="appearance-preview-modal" hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="appearance-preview-modal-title">
        <div class="appearance-modal-backdrop js-close-preview"></div>
        <div class="appearance-modal-dialog" role="document">
            <button type="button" class="appearance-modal-close js-close-preview" aria-label="Fechar visualizacao">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 id="appearance-preview-modal-title" class="appearance-modal-title">Pre-visualizacao</h2>
            <div class="appearance-modal-frame">
                <img id="appearance-preview-modal-image" src="" alt="Imagem ampliada do asset">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.js-asset-input');
        const previewCards = document.querySelectorAll('.js-preview-card');
        const modal = document.getElementById('appearance-preview-modal');
        const modalImage = document.getElementById('appearance-preview-modal-image');
        const modalTitle = document.getElementById('appearance-preview-modal-title');
        const modalFrame = modal ? modal.querySelector('.appearance-modal-frame') : null;
        const openButtons = document.querySelectorAll('.js-open-preview');
        const closeButtons = document.querySelectorAll('.js-close-preview');

        const togglePreviewState = (image) => {
            const card = image.closest('.js-preview-card');
            if (!card) {
                return;
            }

            const source = (image.getAttribute('src') || '').trim();
            card.classList.toggle('is-empty', source.length === 0);
        };

        const openModal = (source, title, mode) => {
            if (!modal || !modalImage || !modalTitle || !modalFrame || !source) {
                return;
            }

            modalImage.src = source;
            modalTitle.textContent = title || 'Pre-visualizacao';
            modalFrame.classList.remove('is-logo-icon', 'is-logo-written', 'is-background', 'is-cover');
            if (mode === 'logo-icon') {
                modalFrame.classList.add('is-logo-icon');
            } else if (mode === 'logo-written') {
                modalFrame.classList.add('is-logo-written');
            } else if (mode === 'background') {
                modalFrame.classList.add('is-background');
            } else if (mode === 'cover') {
                modalFrame.classList.add('is-cover');
            }
            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('appearance-modal-open');
        };

        const closeModal = () => {
            if (!modal || !modalImage || !modalFrame) {
                return;
            }

            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            modalImage.src = '';
            modalFrame.classList.remove('is-logo-icon', 'is-logo-written', 'is-background', 'is-cover');
            document.body.classList.remove('appearance-modal-open');
        };

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

                const temporaryUrl = URL.createObjectURL(file);
                previewImage.src = temporaryUrl;
                togglePreviewState(previewImage);
            });
        });

        previewCards.forEach((card) => {
            const image = card.querySelector('img');
            if (!image) {
                return;
            }

            image.addEventListener('load', function () {
                togglePreviewState(image);
            });

            image.addEventListener('error', function () {
                image.src = '';
                togglePreviewState(image);
            });

            togglePreviewState(image);
        });

        openButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const previewTarget = button.getAttribute('data-preview-target');
                const label = button.getAttribute('data-preview-label') || 'Pre-visualizacao';
                const mode = button.getAttribute('data-preview-mode') || '';
                const image = previewTarget ? document.getElementById(previewTarget) : null;
                const source = image ? (image.getAttribute('src') || '').trim() : '';

                if (!source) {
                    return;
                }

                openModal(source, label, mode);
            });
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal && !modal.hidden) {
                closeModal();
            }
        });
    });
</script>
@endsection
