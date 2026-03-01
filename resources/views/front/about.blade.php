@extends('front.layout')

@section('page-title', 'About The Project')
@section('page-description', 'Learn more about the CATAWOL Records Song Contest: why it was created, the message behind the music, and how it brings artists and audiences together.')
@section('page-image', asset('img/og/og-about.jpg'))
@section('event-category', 'About')
@section('content')

    <div class="site-container my-8">

        <div class="page-banner">
            <img src="{{ asset('img/banners/about-2.jpg') }}" alt="An ensemble of musicians, singers and performers.">
        </div>

        <h1 class="page-heading">Real Figures Don't F.O.L.D &mdash; About the Project</h1>

        <div class="about-intro">
            <p class="content-intro text-center">
                <b>Real Figures Don't F.O.L.D combines SilentMode's interest in LEGO</b> with music,<br>
                "artificial intelligence", web development and advocacy.
            </p>

            <ul class="about-points">
                <li class="content">
                    <strong>Revisiting one of SilentMode's earliest Creations.</strong><br/>
                    CATAWOL Records began life as a modular building, designed and built
                    near the beginning of SilentMode's time in the LEGO hobby.
                </li>
                <li class="content">
                    <strong>Embarking on an ambitious LEGO project.</strong><br/>
                    Expanding on his existing skills as a Maker, Artist and LEGO
                    Enthusiast, this is SilentMode's first project to fully incorporate music and
                    AI/computer-generated content.
                </li>
                <li class="content">
                    <strong>Creating the first ever anti-bullying campaign (that we know of) within the LEGO space.</strong><br/>
                    An opportunity to highlight an important issue,
                    affecting <b>both children and adults</b>, that has probably never been
                    addressed before in the context of LEGO.
                </li>
                <li class="content">
                    <strong>A live demonstration of coding ability.</strong><br/>
                    Designed and built by SilentMode himself, the site uses
                    Laravel with Inertia for the back end, and React with Tailwind for the front end.
                </li>

            </ul>
        </div>

        @include('front.advert')

        @include('front.collapse', ['title' => 'About CATAWOL Records', 'content' => 'front.about.catawol'])
        @include('front.collapse', ['title' => 'About the Song', 'content' => 'front.about.song'])
        @include('front.collapse', ['title' => 'What is F.O.L.D?', 'content' => 'front.about.fold'])
        @include('front.collapse', ['title' => 'Who is SilentMode?', 'content' => 'front.about.silentmode'])
        @include('front.collapse', ['title' => 'Credits', 'content' => 'front.about.credits'])

        @include('front.advert')
    </div>
@endsection
