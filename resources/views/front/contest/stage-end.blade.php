@extends('front.contest')

@section('page-title', "Voting ended for {$stage['title']}")
@section('page-description', "The results for {$stage['title']} are being tallied...")

@section('contest-header')
    @if ($last_stage)
        <h1>The Finals have ended.</h1>
        <p>The stage has gone quiet. The last notes have been sung.</p>
        <p>Now, it’s up to the numbers, and the anticipation is <i>electric</i>.</p>
        <p>All the finalists are legends in their own right,
            but <em>only one Song</em> will be immortalised as the official anthem.</p>
    @else
        <h1>{{ $stage['title'] }}</h1>
        <p><b>{{$stage['title']}} has ended.</b>
            Thank you to everybody who took part in the voting!</p>
        <p><b>Who will make it to the next Round? Who will just miss the cut?</b>
            All will be revealed soon, so stay tuned.</p>
    @endif
@endsection

@section('contest-content')

    <div class="site-container">
        @foreach ($previous_rounds as $round)
            @include('front.contest.previous-round', ['round' => $round, 'show_title' => count($previous_rounds) > 1])
        @endforeach

            <div class="golden-buzzer-prompt">
                <div class="inner"></div>
            <p>It's not too late to support your favourite Acts with a <b>Golden Buzzer!</b></p>
                @if(isset($stage['goldenBuzzerPerks']))
                    <div class="golden-buzzer-perks">
                        {!! $stage['goldenBuzzerPerks'] !!}
                    </div>
                @endif
        </div>
    </div>

@endsection
