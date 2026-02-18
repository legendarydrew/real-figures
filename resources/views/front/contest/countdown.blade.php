@extends('front.layout')

@section('content')
    <div class="contest contest-countdown">

        <header class="contest-header">
            <countdown timestamp="{{$countdown }}" size="large"></countdown>
            <h1>Countdown to {{$stage['title']}}</h1>
            {!! $stage['description'] !!}
        </header>

    </div>
@endsection
