@extends('front.layout')

@section('page-title', 'Competing Acts')
@section('page-description', 'Meet the 32 Acts competing in the CATAWOL Records Song Contest.')
@section('event-category', 'Acts')

@section('content')

    <div class="site-container">
        <h1 class="page-heading">Competing Acts</h1>
        <p>Meet the 32 Acts that were shortlisted for CATAWOL Records' Real Figures Don't F.O.L.D Song Contest.</p>

        @if(count($acts))
            <div class="grid auto-rows-min gap-2 md:grid-cols-3 lg:grid-cols-4 my-8">
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
