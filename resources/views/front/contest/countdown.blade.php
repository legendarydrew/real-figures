@extends('front.contest')

@section('page-title', "Countdown to {$stage['title']}")
@section('page-description', "{$stage['title']} is on the way — get ready to vote for your favourite Acts!")

@section('contest-header')
    <countdown timestamp="{{$countdown }}" size="large"></countdown>
    <h1>Countdown to {{$stage['title']}}</h1>
    {!! $stage['description'] !!}
@endsection

@section('contest-content')
    <div class="contest-subscribe">
        <subscribe-form></subscribe-form>
        <p>Your email address will only be used for sending notifications, and will
            not be shared with anyone else.</p>
    </div>
@endsection
