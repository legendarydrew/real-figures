@extends('front.contest')

@section('page-title', $stage['title'])
@section('page-description', "Voting is underway for {$stage['title']}!")

@section('contest-header')
    <h1>{{ $stage['title'] }}</h1>
    <p>{!! $stage['description'] !!}</p>
@endsection

@section('contest-content')

    <div class="site-container">

        @if($current_round)
            {{-- The current round. --}}
            <div class="flex justify-between my-4">
                <h2 class="page-subheading">{{ $current_round['full_title'] }}</h2>
                <div class="flex flex-end items-center gap-2">
                    <span class="text-sm">Voting ends in</span>
                    <countdown timestamp="{{$countdown}}"></countdown>
                </div>
            </div>
        @endif

        {{-- The Acts and their songs. --}}
        <ul class="grid gap-2 grid-cols-2 md:grid-cols-4 select-none mb-8">
            @foreach($current_round['songs'] as $song)
                <li class="leading-none">
                    <button type="button"
                            class="aspect-square w-full text-left bg-secondary/30 leading-none relative hover:bg-secondary/50 cursor-pointer">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <div class="p-3 lg:p-5 absolute bottom-0 w-full">
                            <div class="text-lg font-semibold leading-tight">{{ $song['act']['name'] }}</div>
                            <div class="flex items-center truncate gap-2 text-sm font-semibold leading-tight">
                                <span class="flag:{{ strtoupper($song['language']['flag']) }}"
                                      title="{{ $song['language']['name'] }}"></span>
                                {{ $song['title'] }}
                            </div>
                        </div>
                    </button>
                    <button class="button gold w-full rounded-none" type="button"
                            command="show-modal" commandfor="golden-buzzer-dialog-{{ $song['act_id'] }}"
                            aria-controls="golden-buzzer-dialog-{{ $song['act_id'] }}">
                        <i class="fa-solid fa-star"></i> Golden Buzzer
                    </button>
                    @include('front.contest.golden-buzzer', ['stage' => $stage, 'song' => $song, 'round' => $current_round])
                </li>
            @endforeach
        </ul>

        {{-- A big button for casting a vote. --}}
        <button type="button" class="button large w-full my-4"
                command="show-modal" commandfor="vote-dialog" aria-controls="vote-dialog">Cast your Vote...
        </button>

        @if(count($previous_rounds))
            @include('front.advert')

            <h2 class="page-subheading">Previous Rounds</h2>

            {{-- Previous rounds. --}}
            @foreach ($previous_rounds as $round)
                @include('front.contest.previous-round', ['round' => $round, 'show_title' => true])
            @endforeach
        @endif

        <div class="golden-buzzer-prompt">
            <div class="inner"></div>
            <p><b>Don't forget:</b> you can support your favourite Acts with a <b>Golden Buzzer</b>,
                and reward them with <em>the same honours as the winners</em>.</p>
            @if(isset($stage['goldenBuzzerPerks']))
                <div class="golden-buzzer-perks">
                    {!! $stage['goldenBuzzerPerks'] !!}
                </div>
            @endif
        </div>
    </div>

    <dialog id="vote-dialog" class="dialog">
        <button class="dialog-close" command="close" commandfor="vote-dialog" aria-controls="vote-dialog"
                title="Close">
            <i class="fa-solid fa-close"></i>
        </button>
        <h2 class="dialog-title">
            Cast your Vote for <span class="text-primary">{{ $current_round['full_title'] }}</span>...
        </h2>
        <vote-dialog round="{{ json_encode($current_round) }}"></vote-dialog>
    </dialog>

@endsection
