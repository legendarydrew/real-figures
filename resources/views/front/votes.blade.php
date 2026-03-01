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
                        @if($breakdown['manual_vote'])
                            <p class="text-sm">Winners were determined by an independent panel.</p>
                            @else
                                <p class="text-sm"><b>{{ $breakdown['vote_count'] }}</b> total votes cast.</p>
                        @endif

                        <table class="votes-breakdown">
                            <thead>
                            <tr>
                                <th scope="col"></th>{{-- Golden Buzzer --}}
                                <th scope="col"></th>{{-- Winner/Runner-up --}}
                                <th scope="col"></th>{{-- Act --}}
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
                                            <i class="fa-solid fa-star text-gold" title="Awarded a Golden Buzzer."></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($song['win_status'] === \App\Enums\RoundWinState::WINNER)
                                            <i class="fa-solid fa-trophy text-gold" title="Winner"></i>
                                        @elseif($song['win_status'] === \App\Enums\RoundWinState::RUNNER_UP)
                                            <i class="fa-solid fa-ribbon text-primary" title="Runner-Up"></i>
                                        @endif
                                    </td>
                                    <th scope="row">
                                        <div class="flex gap-2 items-center">
                                            @include('front.act-image', ['act' => $song['song']['act'], 'size' => 8])
                                            <span class="flag flag:{{ $song['song']['language']['flag'] }}"></span>
                                            <div>
                                            {{ $song['song']['act']['name'] }}
                                            @if ($song['song']['act']['subtitle'])
                                                <span class="text-sm">{{ $song['song']['act']['subtitle'] }}</span>
                                            @endif</div>
                                        </div>
                                    </th>
                                    <td class="text-right font-semibold">{{$song['score']}}</td>
                                    <td class="text-sm text-right">{{$song['first_choice_votes']}}</td>
                                    <td class="text-sm text-right">{{$song['second_choice_votes']}}</td>
                                    <td class="text-sm text-right">{{$song['third_choice_votes']}}</td>
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
