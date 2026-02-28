<div class="song-item">
    <button class="song-item-image" type="button" onclick="openSongPlayer(this)" data-song="{{ json_encode($song) }}">
        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
    </button>
    <div class="song-item-act">
        <span class="song-item-flag flag:{{ strtoupper($song['language']['flag']) }}"
              title="{{ $song['language']['name'] }}"></span>
        <div class="song-item-act-name">
            {{ $song['act']['name'] }}
            @if ($song['act']['subtitle'])
                <span class="song-item-act-subtitle">{{ $song['act']['subtitle'] }}</span>
            @endif
        </div>
        @if($golden_buzzer)
            <button class="song-item-golden-buzzer button gold icon" type="button"
                    title="Award a Golden Buzzer"
                    command="show-modal" commandfor="golden-buzzer-dialog-{{ $song['act_id'] }}"
                    aria-controls="golden-buzzer-dialog-{{ $song['act_id'] }}"
                    onclick="trackEvent({ action: 'Begin Golden Buzzer', label: '{{$song['act']['slug']}}', nonInteraction: false })">
                <i class="fa-solid fa-star"></i>
            </button>
        @endif
    </div>
    @if($golden_buzzer)
        @include('front.contest.golden-buzzer', ['stage' => $stage, 'song' => $song, 'round' => $round])
    @endif
</div>
