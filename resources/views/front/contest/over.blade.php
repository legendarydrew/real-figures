@extends('front.contest')

@section('page-title', "The Contest is Over.")
@section('page-description', "The winner of the Real Figures Don't F.O.L.D Song Contest has been announced!")

@section('contest-header')
    <h1>The Contest is Over!</h1>
    <p>
        The votes are counted. The winners are announced. <b>The anthem has been chosen.</b>
    </p>
@endsection

@section('contest-content')

    <div class="site-container">
        <ul class="contest-winners{{count($results['winners'] ) > 1 ? ' contest-joint-winners' : ''}}">
            <!-- Winner -->
            @foreach($results['winners'] as $song)
                <li class="winner">
                    <div class="inner">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <p class="badge">
                            <i class="fa-solid fa-trophy"></i>
                            Winner
                        </p>
                        <div class="act">
                            {{ $song['act']['name'] }}
                            @if ($song['act']['subtitle'])
                                <small>{{ $song['act']['subtitle'] }}</small>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach

            <!-- Runners-up! -->
            @foreach($results['runners_up'] as $song)
                <li class="runner-up">
                    <div class="inner">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <div class="act">
                            {{ $song['act']['name'] }}
                            @if ($song['act']['subtitle'])
                                <small>{{ $song['act']['subtitle'] }}</small>
                            @endif
                            <p class="badge">
                                <i class="fa-solid fa-award"></i>
                                Runner-up
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="contest-vote-breakdown">
            <a href="{{ route('votes') }}" class="button primary large">View voting breakdown</a>
        </div>

        @include('front.advert')

        {{-- All entries. --}}
        @if(count($songs))
            <div class="contest-all">
                <h2 class="page-subheading">All Songs</h2>
                <p>A huge thank you to all of our talented Acts for participating in this historic Song Contest:</p>

                <div class="contest-all-songs">
                    @foreach($songs as $song)
                        @include('front.song-item',['golden_buzzer' => false])
                    @endforeach
                </div>
            </div>
        @endif

        <footer class="contest-footer content">
            <p>Whether your favourite song made it to the top or not, <b>you helped make this contest unforgettable.</b>
            </p>
            <p>Your support amplified voices, celebrated creativity, and helped shine a light on an important cause.</p>
            <p> From everyone at CATAWOL Records &mdash; <b>thank you.</b></p>
        </footer>
    </div>
@endsection
