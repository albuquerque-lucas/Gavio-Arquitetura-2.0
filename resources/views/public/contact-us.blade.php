@extends('public-layout')

@section('content')
<section class="contact-form-container">
    <div class="container mt-5">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="contact-title">Entre em contato conosco</h2>
                <p class="contact-subtitle">Envie sua mensagem e retornaremos em breve.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="contact-form-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show contact-alert" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show contact-alert" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="mb-3 contact-field">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Seu nome" required>
                    </div>
                    <div class="mb-3 contact-field">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Seu email" required>
                    </div>
                    <div class="mb-3 contact-field">
                        <label for="subject" class="form-label">Assunto</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Assunto" required>
                    </div>
                    <div class="mb-3 contact-field">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Sua mensagem" required></textarea>
                    </div>
                    <button type="submit" class="btn contact-submit-btn">Enviar</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
