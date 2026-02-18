@extends('front.contest')

@section('content')
    <div class="contest contest-stage">

        <header class="contest-header">
            <h1>{{ $stage['title'] }}</h1>
            <p>{!! $stage['description'] !!}</p>
        </header>

    </div>
@endsection
