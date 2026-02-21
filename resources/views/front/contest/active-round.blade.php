@extends('front.contest')

@section('page-title', $stage['title'])
@section('page-description', "Voting is underway for {$stage['title']}!")

@section('contest-header')
    <h1>{{ $stage['title'] }}</h1>
    <p>{!! $stage['description'] !!}</p>
    @if(isset($stage['goldenBuzzerPerks']))
        <div class="px-8 py-2 text-sm bg-gold/80">
            <p><b>Awarding a Golden Buzzer:</b> {{ $stage['goldenBuzzerPerks'] }}</p>
        </div>
    @endif
@endsection

@section('contest-content')

    <div class="site-container">

        @if($current_round)
            {{-- The current round. --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-y-0 my-8">
                <div class="col-span-1 md:col-span-3 col-start-1 row-start-1 text-center md:text-left">
                    <h2 class="page-subheading">{{ $current_round['full_title'] }}
                </div>
                <div class="col-span-1 text-center col-start-1 md:col-start-4 row-start-2 md:row-start-1 md:row-span-2">
                    <span class="text-sm">Voting ends in</span>
                    <countdown timestamp="{{$countdown}}"></countdown>
                </div>
            </div>
        @endif

        {{-- The Acts and their songs. --}}
        <ul class="grid gap-4 grid-cols-2 md:grid-cols-4 select-none mb-8">
            @foreach($current_round['songs'] as $song)
                <li class="leading-none">
                    <button type="button"
                            class="aspect-square w-full text-left bg-secondary/30 leading-none relative hover:bg-secondary/50 cursor-pointer">
                        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
                        <div class="p-3 lg:p-5 absolute bottom-0 w-full">
                            <div class="text-lg font-semibold leading-tight">{{ $song['act']['name'] }}</div>
                            <div class="flex items-center truncate gap-2 text-sm font-semibold leading-tight">
                                {{--                            <LanguageFlag languageCode={song.language}/>--}}
                                {{ $song['title'] }}
                            </div>
                        </div>
                    </button>
                    <button class="button gold w-full rounded-none" type="button">
                        <i class="fa-solid fa-star"></i> Golden Buzzer
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- A big button for casting a vote. --}}
        <button type="button" class="button large w-full my-4">Cast your Vote...</button>

        @if(count($previous_rounds))
            @include('front.advert')

            {{-- Previous rounds. --}}
            @foreach ($previous_rounds as $round)
                @include('front.contest.previous-round', ['round' => $round, 'show_title' => true])
            @endforeach
        @endif

        <div class="px-8 py-4 bg-gold/80">
            <p><b>Don't forget:</b> you can support your favourite Acts with a <b>Golden Buzzer</b>,
                and reward them with <em>the same honours as the winners</em>.</p>
        </div>
    </div>

@endsection
