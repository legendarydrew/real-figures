@extends('front.layout')

@section('content')

    <div class="home-hero">
        <div class="site-container">
            Site Hero here.
        </div>
    </div>

    <div class="site-container">
        <div class="home-grid">
            <div class="home-grid-songs">2️⃣ Songs</div>
            <div class="home-grid-buzzer">
                <div class="font-display text-2xl">Golden Buzzer</div>
                <div class="font-bold">Support your favourite Acts and Songs!</div>
            </div>
            <a class="home-grid-rules" href="{{ route('rules') }}">
                <div class="font-display text-2xl">Contest Rules</div>
                <div class="font-bold">How it all works</div>
            </a>
            <div class="home-grid-youtube">5️⃣ YouTube channel</div>
            <div class="home-grid-about">7️⃣ About the project</div>
            <a class="home-grid-donor" href="{{ route('donate') }}">
                <div class="font-display">Donor Wall</div>
            </a>
        </div>

        <div class="home-advert">Advert</div>
    </div>
@endsection
