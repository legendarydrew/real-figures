@extends('front.contest')

@section('page-title', 'Voting Breakdown')
@section('page-description', 'Find out how each of the Acts scored in previous Stages! See how the votes were counted, which songs advanced, and how the winners were decided.')

@section('contest-header')
    <h1>Voting Breakdown</h1>
@endsection

@section('contest-content')

    <div class="site-container">

        @foreach ($stages as $stage)
            <div
                class="vote-breakdown w-full flex gap-2 items-center justify-center cursor-pointer bg-zinc-700 hover:bg-zinc-600 text-center p-2">
                <h2 class="display-text text-lg">{{$stage['title']}}</h2>

                @foreach($stage['breakdowns'] as $breakdown)
                    {{-- each Round in the Stage. --}}
                    <div class="flex bg-zinc-600 py-2 text-sm font-semibold items-end leading-none sticky-top">
                        <div class="flex-grow flex gap-3 items-end">
                            <h3 class="display-text pl-3 text-base">{{$breakdown['title']}}</h3>
                            {{--                            {wasManualOutcome() && <Badge variant="secondary" title="Votes cast by an independent panel.">Judged</Badge>}--}}
                        </div>
                        <div class="w-[6em] px-3 text-right">
                            <span class="text-xs">Score</span>
                        </div>
                        <div class="w-[6em] px-3 text-right">
                            <span class="text-xs">1st<br/>choice</span>
                        </div>
                        <div class="w-[6em] px-3 text-right">
                            <span class="text-xs">2nd<br/>choice</span>
                        </div>
                        <div class="w-[6em] px-3 text-right">
                            <span class="text-xs">3rd<br/>choice</span>
                        </div>
                    </div>
                    <ul>
                        @foreach($breakdown['songs'] as $song)
                            <li class="flex items-center hover:bg-zinc-200/20 p-1 leading-tight rounded-sm">
                                {{--                                <SongBanner class="flex-grow" song={song.song}/>--}}
                                <div class="w-[6em] p-3 text-right font-semibold">{{$song['score']}}</div>
                                <div class="w-[6em] p-3 text-sm text-right">{{$song['first_choice_votes']}}</div>
                                <div class="w-[6em] p-3 text-sm text-right">{{$song['second_choice_votes']}}</div>
                                <div class="w-[6em] p-3 text-sm text-right">{{$song['third_choice_votes']}}</div>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endforeach

    </div>

@endsection
