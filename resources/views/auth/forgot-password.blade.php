@extends('admin-layout')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>Esqueceu a Senha</h2>
    <form method="POST" action="{{ route('password.email') }}" class="forgot-password-form">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Link de Redefinição</button>
    </form>
</div>
@endsection
