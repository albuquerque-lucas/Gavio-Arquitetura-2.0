@extends('admin-layout')

{{-- @section('extra-css')
    @vite('resources/scss/auth/login.scss')
@endsection --}}

@section('content')
<div class="container">
    <div class="text-center mb-4 image-box">
        <img src="{{ asset('storage/logo/gavioarquitetura-icone-02.png') }}" alt="Gavio Arquitetura Logo" class="logo-image">
        <h2 class="text-center page-title">Gávio Arquitetura - Admin</h2>
    </div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control teste" value="{{ old('email') }}" required autofocus>
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
        <div class="form-group form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Lembrar-me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('password.request') }}" class="btn btn-link">Esqueceu sua senha?</a>
    </form>
</div>
@endsection
