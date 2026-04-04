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
            <div class="contest-current-round">

                <div class="contest-current-heading">
                    <h2 class="page-subheading">{{ $current_round['full_title'] }}</h2>
                    <div class="contest-round-countdown">
                        <span class="text-sm">Voting ends in</span>
                        <countdown timestamp="{{$countdown}}"></countdown>
                    </div>
                </div>

                {{-- The Acts and their songs. --}}
                <div class="contest-round">
                    {{-- Build a playlist for the song player. --}}
                    <script>
                        globalThis.playlist[{{ $current_round['id'] }}] = {!! json_encode($current_round['playlist']) !!};
                    </script>
                    @foreach($current_round['songs'] as $song)
                        @include('front.song-item', ['round' => $current_round, 'golden_buzzer' => true])
                    @endforeach
                </div>

                {{-- A big button for casting a vote. --}}
                <button type="button" class="button xl primary contest-vote-button"
                        onclick="trackEvent('dialog_open', { type: 'vote', round: '{{ $current_round['full_title'] }}' })"
                        command="show-modal" commandfor="vote-dialog" aria-controls="vote-dialog">Cast your Vote...
                </button>
            </div>
        @endif

        @if(count($previous_rounds))
            @include('front.advert')

            <h2 class="page-subheading contest-previous-heading">Previous Rounds</h2>

            {{-- Previous rounds. --}}
            @foreach ($previous_rounds as $round)
                @include('front.contest.previous-round', ['round' => $round, 'show_title' => true])
            @endforeach
        @endif

        <div class="golden-buzzer-prompt">
            <div class="inner"></div>
            <p><b>Don't forget:</b> you can support your favourite Acts with a <b>Golden Buzzer</b>.</p>
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
