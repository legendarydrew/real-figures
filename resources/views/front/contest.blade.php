@extends('front.layout')

@section('content')
    <div class="contest" style="background-image: url({{ asset('img/bg-stage.jpg') }}">

        <header class="contest-header">
            @yield('contest-header')
        </header>

        @yield('contest-content')
    </div>
@endsection
