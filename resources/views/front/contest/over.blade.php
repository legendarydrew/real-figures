@extends('front.contest')

@section('page-title', "The Contest is Over.")
@section('page-description', "The winner of the Real Figures Don't F.O.L.D Song Contest has been announced!")

@section('contest-header')
    <h1>The Contest is Over!</h1>
    <p class="mx-auto mb-3 text-base md:w-3/4">
        The votes are counted. The winners are announced. <b>The anthem has been chosen.</b>
    </p>
@endsection

@section('contest-content')

    <div class="site-container">
        <ul class="grid gap-2 md:grid-cols-4">
            <!-- Winner -->
            @foreach($results['winners'] as $song)
                <li class="display-text md:col-span-2 md:row-span-2 text-shadow-md">
                    <div class="relative w-full bg-yellow-200/30 text-left leading-none">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <p class="absolute top-0 p-5 text-xl text-yellow-300 uppercase">
                            <i class="fa-solid fa-trophy"></i>
                            Winner
                        </p>
                        <div
                                class="absolute bottom-0 w-full p-3 text-xl leading-tight lg:p-5">{{ $song['act']['name'] }}</div>
                    </div>
                </li>
            @endforeach

            <!-- Runners-up! -->
            @foreach($results['runners_up'] as $song)
                <li class="display-text col-span-1 row-span-1 text-shadow-md">
                    <div class="bg-secondary/30 relative w-full text-left leading-none">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <div class="absolute bottom-0 w-full p-3 text-base leading-tight">
                            {{ $song['act']['name'] }}
                            <p class="text-sm text-indigo-200 uppercase">
                                <i class="fa-solid fa-award"></i>
                                Runner-up
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="text-center my-8">
            <a href="{{ route('votes') }}" class="button primary large">View voting breakdown</a>
        </div>

        @include('front.advert')

        {{-- All entries. --}}
        @if(count($songs))
            <div class="my-8 text-center">
                <h2 class="page-subheading">All Songs</h2>
                <p>A huge thank you to all of our talented Acts for participating in this historic Song Contest:</p>
            </div>
            <div class="grid grid-cols-4 gap-4 my-8">
                @foreach($songs as $song)
                    @include('front.song-item',['golden_buzzer' => false])
                @endforeach
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
