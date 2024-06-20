@extends('admin-layout')

@section('content')
<div class="container">
    <h2>Registrar</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('login') }}" class="btn btn-link">Já possui uma conta? Faça login</a>
    </form>
</div>
@endsection
