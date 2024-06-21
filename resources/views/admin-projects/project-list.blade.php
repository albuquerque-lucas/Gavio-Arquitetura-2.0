@extends('admin-layout')

{{-- @section('extra-css')
    @vite('resources/scss/admin/project-list.scss')
@endsection --}}

@section('content')
    <div class="container mt-5">
        <div>
            <h1 class="text-white my-3">Projetos</h1>
            <a href="{{ route('admin.projetos.create') }}" class="btn btn-primary my-3">Novo Projeto</a>
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

        <div class="my-5 text-white d-flex align-items-center justify-content-center" style="height: 4rem">
            @foreach ($links as $link)
                <a href="{{ $link['url'] }}" class="btn btn-secondary mx-2 navlink {{ $link['active'] ? 'active' : '' }}">
                    {!! $link['label'] !!}
                </a>
            @endforeach
        </div>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Título</th>
                    <th scope="col">Capa</th>
                    <th scope="col">Localização</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Status</th>
                    <th scope="col">Editar / Excluir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr style="cursor: pointer;">
                        <th scope="row">{{ $project->id }}</th>
                        <td>{{ $project->title ?? 'Título não encontrado' }}</td>
                        <td>
                            @if ($project->coverUrl())
                                <img src="{{ $project->coverUrl() }}" alt="{{ $project->title }} Cover" width="100">
                            @else
                                Imagem não disponível
                            @endif
                        </td>
                        <td>{{ $project->location ?? 'Localização não encontrada' }}</td>
                        <td>{{ $project->category->name ?? 'Categoria não encontrada' }}</td>
                        <td>
                            <form action="{{ route('admin.projetos.toggleCarousel', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                                    @if ($project->status)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.projetos.edit', $project->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-button" data-project-id="{{ $project->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('components.confirm-delete-modal')
@endsection
