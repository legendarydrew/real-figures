@extends('front.layout')

@section('page-description', '32 Acts, One Anthem. Discover the CATAWOL Records Song Contest, where your vote decides the winner. Follow the journey and support music that makes a difference.')

@section('content')

    <div class="home-hero{{\App\Facades\ContestFacade::isOver() ? ' contest-over' : ''}}">
        <div class="site-container">
            <div class="home-hero-content">
                <div class="home-hero-text">
                    <h1>32 Acts. One Anthem.</h1>
                    @if(\App\Facades\ContestFacade::isOver())
                        <p>We've raised awareness about <b>bullying</b> through music &mdash;
                            and <b>you helped pick the winner!</b></p>
                    @else
                        <p>We're raising awareness about <b>bullying</b> through music &mdash;
                            and <b>you</b> help pick the winner!</p>
                    @endif
                </div>
                <div class="home-hero-subscribe">
                    @if(\App\Facades\ContestFacade::isOver())
                        <p>The Contest is now over. Visit the <a href="{{ route('contest') }}">Contest page</a>
                            to find out which Acts came out on top.</p>
                    @else
                        <h2>Subscribe for updates!</h2>
                        <subscribe-form></subscribe-form>
                        <p>
                            Stay updated about the Contest's progress, and be informed about when
                            it's time to cast your votes!<br>
                            <em>Your details will not be used for anything else.</em>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="site-container">
        <div class="home-grid">
            @if(\App\Facades\ContestFacade::shouldShowNews())
                <a class="home-grid-news" href="{{route('news')}}">
                    <span class="home-grid-title">Contest News</span>
                </a>
                @include('front.advert', ['class' => 'home-grid-advert'])
            @endif
            <a class="home-grid-songs" href="{{route('contest')}}">
                <span class="home-grid-title">Listen to the Songs</span>
                <span class="home-grid-subtitle">and vote for your favourites!</span>
            </a>
            <a class="home-grid-buzzer"
               href="{{ \App\Facades\ContestFacade::isRunning() && !\App\Facades\ContestFacade::isOver() ? route('contest') : route('rules') . '#the-golden-buzzer' }}">
                <div class="inner"></div>
                <span class="home-grid-title">Golden Buzzer</span>
                <span class="home-grid-subtitle">Support your favourite Acts and Songs!</span>
            </a>
            <a class="home-grid-rules" href="{{ route('rules') }}">
                <span class="home-grid-title">Contest Rules</span>
                <span class="home-grid-subtitle">How it all works</span>
            </a>
            <a class="home-grid-youtube" href="https://youtube.com/@silentmodetv" target="_blank">
                <span class="home-grid-small-title">CATAWOL on YouTube</span>
            </a>
            <a class="home-grid-about" href="{{ route('about') }}">
                <span class="home-grid-small-title">About the project</span>
            </a>
            <a class="home-grid-donor" href="{{ route('donate') }}">
                <span class="home-grid-small-title">Donor Wall</span>
            </a>
        </div>

        @include('front.advert')
    </div>
@endsection
