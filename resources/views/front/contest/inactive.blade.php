@extends('front.layout')

@section('content')

    <div class="contest" style="background-image: url({{ asset('img/bg-stage.jpg') }}">

        <header class="contest-header">
            <h1>The Song Contest is On The Way.</h1>
            <p><b>Be the first to hear when voting opens, songs drop and surprises land.</b> Subscribe below to get
                updates straight to your inbox.
        </header>

        <div class="max-w-xl mx-auto">
            <subscribe-form></subscribe-form>
            <p class="text-xs text-center my-4">Your email address will only be used for sending notifications, and will
                not be shared with anyone else.</p>
        </div>
    </div>
@endsection
