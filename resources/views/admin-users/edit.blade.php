@extends('admin-layout')

@section('content')
<div class="container mt-5 user-profile-page">
    <section class="userp-header">
        <a href="{{ route('admin.users.index') }}" class="userp-btn userp-btn-ghost">Voltar</a>
    </section>

    @if (session('success'))
        <div class="userp-alert userp-alert-success alert alert-dismissible fade show" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="userp-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="userp-alert userp-alert-danger alert alert-dismissible fade show" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="userp-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="userp-alert userp-alert-danger alert alert-dismissible fade show" role="alert">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="userp-alert-close" data-bs-dismiss="alert" aria-label="Fechar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <section class="userp-hero">
        @if($user->hasCoverPhoto())
            <button type="button" class="userp-avatar-btn" data-bs-toggle="modal" data-bs-target="#coverImageModal" aria-label="Abrir imagem de perfil">
                <img src="{{ $user->coverUrl() }}" alt="{{ $user->name }}" class="userp-avatar">
            </button>
        @else
            <div class="userp-avatar userp-avatar-placeholder" aria-label="Iniciais do usuario">
                <span>{{ $user->profileInitials() }}</span>
            </div>
        @endif

        <div class="userp-hero-meta">
            <h2>{{ $user->name }}</h2>
            <p>@ {{ $user->username }}</p>
            <div class="userp-badges">
                <span class="userp-badge">{{ $user->email }}</span>
                <span class="userp-badge {{ $user->ownership ? 'is-active' : '' }}">
                    {{ $user->ownership ? 'Exibe no sobre' : 'Nao exibe no sobre' }}
                </span>
            </div>
        </div>
    </section>

    <ul class="nav nav-tabs userp-tabs" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab" aria-controls="view" aria-selected="true">Visualizar</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">Editar</button>
        </li>
    </ul>

    <div class="tab-content userp-tab-content" id="userTabsContent">
        <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
            <section class="userp-view-grid">
                <article class="userp-info-card">
                    <h5>Nome</h5>
                    <p>{{ $user->name }}</p>
                </article>
                <article class="userp-info-card">
                    <h5>Username</h5>
                    <p>{{ $user->username }}</p>
                </article>
                <article class="userp-info-card">
                    <h5>Email</h5>
                    <p>{{ $user->email }}</p>
                </article>
            </section>

            <section class="userp-description-card">
                <h5>Descricao</h5>
                <p>{{ $user->description ?: 'Sem descricao cadastrada.' }}</p>
            </section>

        </div>

        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <section class="userp-form-surface">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="userp-form-grid">
                    @csrf
                    @method('PATCH')

                    <div class="userp-field">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="userp-field">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="userp-field">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="userp-field">
                        <label for="cover_path">Foto de perfil</label>
                        <input type="file" class="form-control" id="cover_path" name="cover_path" accept="image/*">
                    </div>

                    <div class="userp-field">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small>Deixe vazio para manter a senha atual.</small>
                    </div>

                    <div class="userp-field">
                        <label for="password_confirmation">Confirmar senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="userp-field userp-field-full">
                        <label for="description">Descricao</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $user->description) }}</textarea>
                    </div>

                    <div class="userp-field userp-field-full userp-check-field">
                        <input type="checkbox" class="form-check-input" id="ownership" name="ownership" value="1" {{ old('ownership', $user->ownership) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ownership">Exibir este usuario na pagina sobre</label>
                    </div>

                    <div class="userp-actions userp-field-full">
                        <button type="submit" class="userp-btn userp-btn-primary">Salvar alteracoes</button>
                        <button class="userp-btn userp-btn-ghost" type="button" data-bs-toggle="tab" data-bs-target="#view" aria-controls="view" aria-selected="true">Cancelar</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

@if($user->hasCoverPhoto())
    <div class="modal fade" id="coverImageModal" tabindex="-1" aria-labelledby="coverImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content gavio-modal-content">
                <div class="modal-body p-0">
                    <img src="{{ $user->coverUrl() }}" class="img-fluid w-100" style="height: auto;" alt="{{ $user->name }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="gavio-btn gavio-btn-ghost" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
