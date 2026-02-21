@extends('front.layout')

@section('page-title', 'About The Project')
@section('page-description')
    Learn more about the CATAWOL Records song contest: why it was created, the message behind the music, and how it brings artists and audiences together.
@endsection

@section('content')

    <div class="site-container my-8">

        <div class="page-banner">Page banner</div>

        <h1 class="page-heading">Real Figures Don't F.O.L.D &mdash; About the Project</h1>

        <div class="about-intro content">
            <p class="content-intro">
                <b>Real Figures Don't F.O.L.D combines SilentMode's interest in LEGO</b> with music,
                "artificial intelligence", web development and advocacy.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm my-8">
                <div class="p-4">
                    <b>Revisiting one of SilentMode's earliest Creations.</b><br/>
                    CATAWOL Records began life as a modular building, designed and built
                    near the beginning of SilentMode's time in the LEGO hobby.
                </div>
                <div class="p-4">
                    <b>Embarking on an ambitious LEGO project.</b><br/>
                    Expanding on his existing skills as a Maker, Artist and LEGO
                    Enthusiast, this is SilentMode's first project to fully incorporate music and
                    AI/computer-generated content.
                </div>
                <div class="p-4">
                    <b>Creating the first ever anti-bullying campaign (that we know of) within the LEGO space.</b><br/>
                    An opportunity to highlight an important issue,
                    affecting <b>both children and adults</b>, that has probably never been
                    addressed before in the context of LEGO.
                </div>
                <div class="p-4">
                    <b>A live demonstration of coding ability.</b><br/>
                    Designed and built by SilentMode himself, the site uses
                    Laravel with Inertia for the back end, and React with Tailwind for the front end.
                </div>

            </div>
        </div>

        @include('front.advert')

        @include('front.collapse', ['title' => 'About CATAWOL Records', 'content' => 'front.about.catawol'])
        @include('front.collapse', ['title' => 'About the Song', 'content' => 'front.about.song'])
        @include('front.collapse', ['title' => 'What is F.O.L.D?', 'content' => 'front.about.fold'])
        @include('front.collapse', ['title' => 'Who is SilentMode?', 'content' => 'front.about.silentmode'])

        @include('front.advert')
    </div>
@endsection
