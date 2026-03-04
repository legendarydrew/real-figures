@extends('front.contest')

@section('page-title', 'Voting Breakdown')
@section('page-description', 'Find out how each of the Acts scored in previous Stages! See how the votes were counted, which songs advanced, and how the winners were decided.')
@section('event-category', 'Votes')

@section('contest-header')
    <h1>Voting Breakdown</h1>
@endsection

@section('contest-content')

    <div class="site-container">

        @foreach ($stages as $stage)
            <div class="votes-stage">
                <h2 class="votes-stage-name">{{$stage['title']}}</h2>

                @foreach($stage['breakdowns'] as $breakdown)
                    {{-- each Round in the Stage. --}}
                    <div class="votes-round">
                        @if (count($stage['breakdowns']) > 1)
                            <h3>{{$breakdown['title']}}</h3>
                        @endif

                        <table class="votes-breakdown">
                            <caption>
                                @if($breakdown['manual_vote'])
                                    Winners were determined by an independent panel.
                                @else
                                    <b>{{ $breakdown['vote_count'] }}</b> total votes cast.
                                @endif

                            </caption>
                            <thead>
                            <tr>
                                <th scope="col" aria-label="Golden Buzzer"></th>
                                <th scope="col" aria-label="Winner/Runner-up"></th>
                                <th scope="col" aria-label="Act"></th>
                                <th scope="col">Score</th>
                                <th scope="col">1st</th>
                                <th scope="col">2nd</th>
                                <th scope="col">3rd</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($breakdown['songs'] as $song)
                                <tr>
                                    <td>
                                        @if($song['has_buzzer'])
                                            <i class="fa-solid fa-star" title="Awarded a Golden Buzzer."></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($song['win_status'] === \App\Enums\RoundWinState::WINNER)
                                            <i class="fa-solid fa-trophy" title="Winner"></i>
                                        @elseif($song['win_status'] === \App\Enums\RoundWinState::RUNNER_UP)
                                            <i class="fa-solid fa-ribbon" title="Runner-Up"></i>
                                        @endif
                                    </td>
                                    <th scope="row">
                                        <div class="votes-breakdown-act">
                                            @include('front.act-image', ['act' => $song['song']['act'], 'size' => 8])
                                            <span class="flag flag:{{ $song['song']['language']['flag'] }}"></span>
                                            <div>
                                            {{ $song['song']['act']['name'] }}
                                            @if ($song['song']['act']['subtitle'])
                                                <small>{{ $song['song']['act']['subtitle'] }}</small>
                                            @endif</div>
                                        </div>
                                    </th>
                                    <td>{{$song['score']}}</td>
                                    <td>{{$song['first_choice_votes']}}</td>
                                    <td>{{$song['second_choice_votes']}}</td>
                                    <td>{{$song['third_choice_votes']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>

@endsection
