@extends('admin-layout')

@section('content')
    <div class="container mt-5">
        <div>
            <h1 class="text-white my-3">Projetos</h1>
            <a href="#" class="btn btn-primary my-3">Novo Projeto</a>
        </div>
        <div
        class="my-5 text-white d-flex align-items-center justify-content-center"
        style="height: 4rem"
        >
            @foreach ($links as $link)
            <a
            href="{{ $link['url'] }}"
            class="btn btn-secondary mx-2"
            >
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
                    <th scope="col">Editar / Excluir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr style="cursor: pointer;">
                        <th scope="row">{{ $project->id }}</th>
                        <td>{{ $project->title }}</td>
                        <td>
                            @if ($project->cover)
                                <img src="{{ $project->cover }}" alt="{{ $project->title }} Cover" width="100">
                            @else
                                <img src="{{ asset('storage/projects/cover/no-image.jpg') }}" alt="Default Cover" width="100">
                            @endif
                        </td>
                        <td>{{ $project->location }}</td>
                        <td>
                            <button class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger delete-button" data-project-id="{{ $project->id }}">
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
