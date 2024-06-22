@extends('public-layout')

@section('content')
<section class="contact-form-container">
    <div class="container mt-5">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="text-dark my-5">Entre em contato conosco</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class='d-flex flex-column'>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label"></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Seu nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label"></label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Assunto" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label"></label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Sua mensagem" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-50 align-self-center">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
