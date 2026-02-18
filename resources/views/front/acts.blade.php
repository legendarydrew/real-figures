@extends('front.layout')

@section('page-title', 'Competing Acts')
@section('page-description')
    Meet the 32 Acts competing in the CATAWOL Records Song Contest.
@endsection

@section('content')

    <div class="site-container">
        <h1 class="page-heading">Competing Acts</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A assumenda cupiditate debitis dignissimos enim
            quisquam, reprehenderit voluptates. Animi corporis hic illo itaque odio officiis quasi reiciendis sunt
            voluptatem. Quas, ratione.</p>

        @if(count($acts))
            <div class="grid auto-rows-min gap-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($acts as $act)
                    @include('front.act-item', ['act' => $act])
                @endforeach
            </div>
        @else
            <div class="nothing">
                No Acts have entered the contest - yet!
            </div>
        @endif
    </div>

@endsection
