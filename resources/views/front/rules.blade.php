@extends('front.layout')

@section('page-title', 'Contest Rules')
@section('page-description', 'The official rules for the CATAWOL Records Song Contest: how it works, who can vote, what the stages involve, and how winners are chosen.')
@section('page-image', asset('img/og/og-rules.jpg'))
@section('event-category', 'Rules')

@section('content')

    <div class="site-container my-8">

        <div class="page-banner">
            <img src="{{ asset('img/banners/rules-5.jpg') }}"
                 alt="A representative panel of judges for the Song Contest.">
        </div>

        <h1 class="page-heading">Real Figures Don't F.O.L.D &ndash; Contest Rules</h1>

        <div class="content rules-intro">
            <p class="rules-large">
                Welcome to <strong>Real Figures Don't F.O.L.D: CATAWOL Records' first-ever Song Contest!</strong>
            </p>
            <p>
                We’re bringing together 32 of our biggest Acts to showcase incredible
                creativity and raise awareness of bullying in adult and hobby spaces.
            </p>
            <p>
                And we need <b>your votes</b> to help decide which Song becomes
                the <b>official anthem!</b>
            </p>
        </div>

        @include('front.advert')

        @include('front.collapse', ['title' => 'Terminology', 'icon' => 'fa-solid fa-message', 'content' => 'front.rules.terminology'])
        @include('front.collapse', ['title' => 'Contest Brief', 'icon' => 'fa-solid fa-book', 'content' => 'front.rules.contest-brief'])
        @include('front.collapse', ['title' => 'Eligibility', 'icon' => 'fa-solid fa-check-circle', 'content' => 'front.rules.contest-eligibility'])
        @include('front.collapse', ['title' => 'Song Criteria', 'icon' => 'fa-solid fa-list-check', 'content' => 'front.rules.song-criteria'])
        @include('front.collapse', ['title' => 'Stage 1: Knockout Stage', 'icon' => 'fa-solid fa-music', 'content' => 'front.rules.stage-one'])
        @include('front.collapse', ['title' => 'Stage 2: Finals', 'icon' => 'fa-solid fa-ranking-star', 'content' => 'front.rules.stage-two'])
        @include('front.collapse', ['title' => 'How Votes Are Calculated', 'icon' => 'fa-solid fa-check-to-slot', 'content' => 'front.rules.vote-calculation'])
        @include('front.collapse', ['title' => 'The Golden Buzzer', 'icon' => 'fa-solid fa-star text-yellow-500', 'content' => 'front.rules.golden-buzzer'])
        @include('front.collapse', ['title' => 'Special Situations', 'icon' => 'fa-solid fa-shield', 'content' => 'front.rules.special-situations'])
        @include('front.collapse', ['title' => 'Advice for Visitors', 'content' => 'front.rules.advice-for-visitors'])

        @include('front.advert')
    </div>
@endsection
