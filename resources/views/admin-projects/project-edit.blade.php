@extends('admin-layout')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Projeto: {{ $project->title }}</h1>
        <a href="{{ route('admin.projetos.index') }}" class="btn btn-secondary">Voltar</a>
    </div>

    <div style="height:100px" class="mt-2">
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

    </div>

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

    <ul class="nav nav-tabs" id="projectTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link text-dark active" id="view-tab" data-bs-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="true">Visualizar</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Editar</a>
        </li>
    </ul>

    <div class="tab-content" id="projectTabsContent">
        <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
            <div class="mt-4">
                <h5 class="text-white">Título:</h5>
                <p class="text-white">{{ $project->title }}</p>

                <h5 class="text-white">Localização:</h5>
                <p class="text-white">{{ $project->location }}</p>

                <h5 class="text-white">Categoria:</h5>
                <p class="text-white">{{ $project->category->name ?? 'Categoria não encontrada' }}</p>

                <h5 class="text-white">Área:</h5>
                <p class="text-white">{{ $project->area }} m²</p>

                <h5 class="text-white">Descrição:</h5>
                <p class="text-white">{{ $project->description }}</p>

                <h5 class="text-white">Data:</h5>
                <p class="text-white">{{ $project->year }}</p>

                @if ($project->cover)
                    <h5 class="text-white">Capa:</h5>
                    <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="150" data-bs-toggle="modal" data-bs-target="#coverImageModal">
                @endif
            </div>

            <div class="mt-4">
                <h5 class="text-white">Adicionar Imagem</h5>
                <form id="imageUploadForm" action="{{ route('admin.projetos.addImage', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="images" class="form-label text-white">Imagens</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" id="uploadButton" class="btn btn-light">Adicionar Imagens</button>
                    <progress id="uploadProgress" value="0" max="100" class="w-100 mt-3 progress-upload-bar"></progress>
                    <div id="uploadPercentage" class="text-white mt-1">0%</div>
                </form>
            </div>

            <div class="mt-4 project-images-list-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white">Imagens do Projeto</h5>
                    <div>
                        <input type="checkbox" id="selectAll">
                        <button id="deleteSelected" class="btn btn-danger btn-sm" disabled data-bs-toggle="modal" data-bs-target="#bulkDeleteConfirmationModal">Excluir Selecionados</button>
                    </div>
                </div>
                <form id="bulkDeleteForm" action="{{ route('admin.projetos.bulkDeleteImages', $project->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="row">
                        @foreach ($project->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card position-relative">
                                    <img src="{{ asset($image->path) }}" class="card-img-top" alt="{{ $image->file_name }}" data-bs-toggle="modal" data-bs-target="#projectImageModal-{{ $image->id }}">
                                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                                        <input type="checkbox" name="selected_images[]" value="{{ $image->id }}" class="image-checkbox">
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.projetos.deleteImage', ['projectId' => $project->id, 'imageId' => $image->id]) }}" method="POST" class="d-flex justify-content-between align-items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-dark">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <p class="text-white">
                                                {{$image->file_name}}
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for project images -->
                            <div class="modal fade" id="projectImageModal-{{ $image->id }}" tabindex="-1" aria-labelledby="projectImageModalLabel-{{ $image->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content modal-content-popup">
                                        <div class="modal-body">
                                            <img src="{{ asset($image->path) }}" class="img-fluid" alt="{{ $image->file_name }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <form action="{{ route('admin.projetos.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <h5>
                            <label for="title" class="form-label text-white">Título:</label>
                        </h5>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="cover" class="form-label text-white">Capa:</label>
                        </h5>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        @if ($project->cover)
                            <div class="mt-2">
                                <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="150">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="location" class="form-label text-white">Localização:</label>
                        </h5>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $project->location) }}" required>
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="area" class="form-label text-white">Área (m²):</label>
                        </h5>
                        <input type="number" class="form-control" id="area" name="area" value="{{ old('area', $project->area) }}">
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="category_id" class="form-label text-white">Categoria:</label>
                        </h5>
                        <select class="form-control" id="category_id" name="category_id" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == old('category_id', $project->category_id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="description" class="form-label text-white">Descrição:</label>
                        </h5>
                        <textarea class="form-control" id="description" name="description" rows="3" oninput="updateCharacterCountEdit()">{{ old('description', $project->description) }}</textarea>
                        <small class="text-white" id="charCountEdit">{{ strlen(old('description', $project->description)) }} caracteres</small>
                    </div>

                    <div class="mb-3">
                        <h5>
                            <label for="year" class="form-label text-white">Ano:</label>
                        </h5>
                        <input type="text" class="form-control" id="year" name="year" value="{{ old('year', $project->year) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Atualizar Projeto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for cover image -->
<div class="modal fade" id="coverImageModal" tabindex="-1" aria-labelledby="coverImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ $project->coverUrl() }}" class="img-fluid" alt="{{ $project->title }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCharacterCountEdit() {
        const descriptionEdit = document.getElementById('description');
        const charCountEdit = document.getElementById('charCountEdit');
        charCountEdit.textContent = `${descriptionEdit.value.length} caracteres`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateCharacterCountEdit();

        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.image-checkbox');
        const deleteSelectedButton = document.getElementById('deleteSelected');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        let selectedImages = [];

        selectAll.addEventListener('change', function () {
            selectedImages = [];
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
                if (selectAll.checked) {
                    selectedImages.push(checkbox.value);
                }
            });
            toggleDeleteButton();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (checkbox.checked) {
                    selectedImages.push(checkbox.value);
                } else {
                    selectedImages = selectedImages.filter(id => id !== checkbox.value);
                }
                toggleDeleteButton();
            });
        });

        function toggleDeleteButton() {
            const anyChecked = selectedImages.length > 0;
            deleteSelectedButton.disabled = !anyChecked;
        }

        deleteSelectedButton.addEventListener('click', function (event) {
            event.preventDefault();  // Previne o envio imediato do formulário

            // Desabilitar o botão e mudar o texto para "Excluindo..."
            deleteSelectedButton.disabled = true;
            deleteSelectedButton.innerHTML = 'Excluindo... <i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(bulkDeleteForm);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', bulkDeleteForm.action, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Imagens excluídas com sucesso!');
                    window.location.reload();
                } else {
                    alert('Erro ao excluir imagens. Tente novamente.');
                    deleteSelectedButton.disabled = false;
                    deleteSelectedButton.innerHTML = 'Excluir Selecionados';
                }
            };

            xhr.onerror = function() {
                alert('Erro no servidor. Tente novamente.');
                deleteSelectedButton.disabled = false;
                deleteSelectedButton.innerHTML = 'Excluir Selecionados';
            };

            xhr.send(formData);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('imageUploadForm');
        const uploadButton = document.getElementById('uploadButton');
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadPercentage = document.getElementById('uploadPercentage');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Desabilitar o botão e mudar o texto para "Aguarde..."
            uploadButton.disabled = true;
            uploadButton.innerHTML = 'Aguarde... <i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', form.action, true);

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    uploadProgress.value = percentComplete;
                    uploadPercentage.textContent = `${Math.round(percentComplete)}%`;
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Upload completo!');
                    window.location.reload();
                } else {
                    alert('Erro no upload, tente novamente.');
                    uploadButton.disabled = false;
                    uploadButton.innerHTML = 'Adicionar Imagens';
                }
            };

            xhr.onerror = function() {
                alert('Erro no upload, tente novamente.');
                uploadButton.disabled = false;
                uploadButton.innerHTML = 'Adicionar Imagens';
            };

            xhr.send(formData);
        });
    });
</script>

@endsection
