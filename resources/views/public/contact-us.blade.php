@extends('public-layout')

@section('content')
<section class="contact-form-container">
    <div class="container mt-5">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h1 class="text-dark">Contato</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('contact.send') }}" method="POST">
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
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
