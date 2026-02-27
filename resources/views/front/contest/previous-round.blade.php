<div class="contest-previous-round">
    @if($show_title)
        <h2 class="page-subheading">{{$round['title']}}</h2>
    @endif
    <div class="contest-round">
        @foreach($round['songs'] as $song)
            @include('front.song-item', ['golden_buzzer' => true])
        @endforeach
    </div>
</div>
