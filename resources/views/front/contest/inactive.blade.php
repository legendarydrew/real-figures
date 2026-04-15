@extends('front.contest')

@section('page-title', 'Coming Soon.')
@section('page-description', 'CATAWOL Records is launching the Real Figures Don\'t F.O.L.D Song Contest, where your vote makes a difference.')

@section('contest-header')
    <h1>The Song Contest is On The Way.</h1>
    <p><b>Be the first to hear when voting opens, songs drop and surprises land.</b> Subscribe below to get
        updates straight to your inbox.
@endsection

@section('contest-content')
    <div class="contest-subscribe">
        <subscribe-form></subscribe-form>
        <p>Your email address will only be used for sending notifications, and will
            not be shared with anyone else.</p>
    </div>

    {{-- "Hype" video. --}}
    <iframe class="contest-hype"
            src="https://www.youtube-nocookie.com/embed/kyVJc9Q01AY?si=L1969HLY3PrJRT1o&amp;controls=0"
            title="YouTube video player"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
@endsection
