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
@endsection
