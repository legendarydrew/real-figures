@extends('front.layout')

@section('content')

    <div class="home-hero">
        <div class="site-container">
            <div class="home-hero-mic" style="background-image: url({{ asset('img/microphone.png') }}"></div>
            <div class="class home-hero-text">
                <h1>32 Acts. One Anthem.</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur cumque delectus dolores
                    exercitationem labore maiores mollitia nam numquam obcaecati officia optio perferendis perspiciatis
                    quam, quas quos reiciendis repudiandae sequi tempora?</p>
            </div>
        </div>
    </div>

    <div class="site-container">
        <div class="home-grid">
            @if(\App\Facades\ContestFacade::shouldShowNews())
                <a class="home-grid-news" href="{{route('news')}}">
                    <div class="font-display text-2xl">Contest News</div>
                </a>
                <div class="home-grid-advert">Advert</div>
            @endif
            <a class="home-grid-songs" href="{{route('contest')}}">
                <div class="font-display text-2xl">Listen to the Songs</div>
                <div class="font-bold">and vote for your favourites!</div>
            </a>
            <div class="home-grid-buzzer">
                <div class="font-display text-2xl">Golden Buzzer</div>
                <div class="font-bold">Support your favourite Acts and Songs!</div>
            </div>
            <a class="home-grid-rules" href="{{ route('rules') }}">
                <div class="font-display text-2xl">Contest Rules</div>
                <div class="font-bold">How it all works</div>
            </a>
            <a class="home-grid-youtube" href="https://youtube.com/@silentmodetv" target="_blank">
                <div class="font-display text-lg">CATAWOL on YouTube</div>
            </a>
            <a class="home-grid-about" href="{{ route('about') }}">
                <div class="font-display text-lg">About the project</div>
            </a>
            <a class="home-grid-donor" href="{{ route('donate') }}">
                <div class="font-display text-lg">Donor Wall</div>
            </a>
        </div>

        <div class="home-advert">Advert</div>
    </div>
@endsection
