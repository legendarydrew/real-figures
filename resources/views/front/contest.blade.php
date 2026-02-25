@extends('front.layout')

@section('vite')
    @vite(['resources/js/player.tsx'])
@endsection

@section('content')
    <div class="contest" style="background-image: url({{ asset('img/bg-stage.jpg') }}">

        <header class="contest-header">
            @yield('contest-header')
        </header>

        @yield('contest-content')

        <div class="site-container">
            @include('front.advert')
        </div>
    </div>

    <dialog id="song-player" popover="manual">
        <div class="song-player-video"></div>
        <div class="song-player-banner">
            <span class="song-player-banner-flag flag"></span>
            <div class="song-player-banner-song">
                <div class="song-player-banner-act"></div>
                <div class="song-player-banner-title"></div>
            </div>
            <button class="button gold icon">
                <i class="fa-solid fa-star"></i>
            </button>
            <button class="button ghost icon" type="button" onclick="closeSongPlayer()" title="Close">
                <i class="fa-solid fa-close"></i>
            </button>
        </div>
    </dialog>
@endsection
