<div class="mb-2 bg-black/50 p-4 rounded-sm">
    @if($show_title)
        <h2 class="page-subheading">{{$round['title']}}</h2>
    @endif
    <div class="grid gap-x-4 gap-y-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 select-none">
        @foreach($round['songs'] as $song)
            @include('front.song-item')
        @endforeach
    </div>
</div>
