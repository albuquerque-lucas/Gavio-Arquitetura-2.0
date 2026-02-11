@extends('public-layout')

@section('content')
    <section class='institutional-container'>

        @foreach ($profiles as $profile)
        <div class="profile-container">
            <div class="profile-img-wrapper">
                @if($profile->hasCoverPhoto())
                    <img
                        src="{{ $profile->coverUrl() }}"
                        alt="{{ $profile->name }}"
                        class="profile-img"
                        loading="lazy"
                        decoding="async"
                    >
                @else
                    <div class="profile-img profile-img-placeholder">
                        <span>{{ $profile->profileInitials() }}</span>
                    </div>
                @endif
            </div>
            <div class="profile-content">
                <h2 class="profile-name">{{ $profile->name }}</h2>
                <p class="profile-description">{{ $profile->description }}</p>
            </div>
        </div>
        @endforeach

    </section>
@endsection
