@extends('front.layout')

@section('page-title', 'Competing Acts')
@section('page-description', 'Meet the 32 Acts competing in the CATAWOL Records Song Contest.')
@section('page-image', asset('img/og/og-acts.jpg'))
@section('event-category', 'Acts')

@section('content')

    <div class="site-container">
        <h1 class="page-heading">Competing Acts</h1>
        <p>
            Meet the 32 Acts that were shortlisted for CATAWOL Records' Real Figures Don't F.O.L.D Song Contest.
            Your votes determine which Acts wil go through to the Final, and which Song will be chosen as the
            official anthem of the Contest.
        </p>

        @if(count($acts))
            <div class="acts-grid">
                @foreach($acts as $act)
                    @include('front.act-item', ['act' => $act])
                @endforeach
            </div>
        @else
            <div class="nothing">
                No Acts have entered the Contest &mdash; yet!
            </div>
        @endif

        @include('front.advert')
    </div>

@endsection
