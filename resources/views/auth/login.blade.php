@extends('admin-layout')

@section('content')
<section class="login-page">
    <div class="login-card">
        <header class="login-brand">
            <div class="login-brand-icon">
                <img
                    src="{{ $assets['brand_logo_icon_url'] ?? asset('favicon.ico') }}"
                    alt="Gavio Arquitetura Icone"
                    class="login-brand-image"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"
                >
                <span class="login-brand-fallback" aria-hidden="true">GA</span>
            </div>
            <h1 class="login-title">Gavio Arquitetura</h1>
            <p class="login-subtitle">Area administrativa</p>
        </header>

        @if (session('status'))
            <div class="alert alert-success login-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-admin-form">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" class="form-control" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Lembrar-me</label>
            </div>

            <button type="submit" class="btn login-submit-btn">Login</button>
        </form>
    </div>
</section>
@endsection
