@extends('public-layout')

@section('content')
    <section class='institutional-container'>

        @foreach ($profiles as $profile)
        <div class="profile-container">
            <div class="profile-img-wrapper">
                <img src="{{ $profile->coverUrl() }}" alt="{{ $profile->name }}" class='profile-img'>
            </div>
            <h2>{{ $profile->name }}</h2>
            <p>{{ $profile->description }}</p>
        </div>
        @endforeach

    </section>
@endsection
