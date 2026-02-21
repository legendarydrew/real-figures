<div class="mb-2 bg-black/50 p-4 rounded-sm">
    @if($show_title)
        <h2 class="page-subheading">{{$round['title']}}</h2>
    @endif
    <ul class="grid gap-x-4 gap-y-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 select-none">
        @foreach($round['songs'] as $song)
            <li class="flex flex-col">

                @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])

                <div class="flex">
                    <button class="p-2 font-display flex-grow text-left">
                        {{-- open the player --}}
                        {{ $song['act']['name'] }}
                    </button>
                    <button class="button gold rounded-none hidden md:block" size="lg" type="button"
                            title="Award a Golden Buzzer">
                        <i class="fa-solid fa-star"></i>
                    </button>
                </div>

            </li>
        @endforeach
    </ul>
</div>
