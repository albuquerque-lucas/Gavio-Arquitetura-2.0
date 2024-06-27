@extends('admin-layout')

@section('content')
<div class="container">
    <h2>Registrar</h2>
    <form method="POST" action="{{ route('register') }}" class="my-5">
        @csrf
        <div class="form-group">
            <label for="name" style="color: white;">Nome:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="username" style="color: white;">Nome de usuário:</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email" style="color: white;">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password" style="color: white;">Senha:</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation" style="color: white;">Confirmar Senha:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary my-5">Registrar</button>
        <a href="{{ route('login') }}" class="btn btn-link my-5">Já possui uma conta? Faça login</a>
    </form>
</div>
@endsection
