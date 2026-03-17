@extends('front.layout')

@section('page-title', 'Subscription Confirmed')
@section('content')
    <div class="site-container my-8">

        <h1 class="page-heading">Real Figures Don't F.O.L.D</h1>

        <p class="content-intro text-center">
            <b>Thank you very much!</b> Your email address has been confirmed for receiving Contest updates.
        </p>

        <p class="flex flex-col gap-2 align-center max-w-80 mx-auto my-8">
            <a class="button large primary" href="{{ route('contest') }}">View the Contest</a>
            @if (\App\Facades\ContestFacade::shouldShowNews())
                <a class="button small" href="{{ route('news') }}">Contest News</a>
            @endif
            <a class="button small" href="{{ route('about') }}">About the Contest</a>
        </p>
        @include('front.advert')
    </div>
@endsection
