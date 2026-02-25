<div class="song-item">
    <button class="song-item-image" type="button">
        @include('front.act-image', ['act' => $song['act'], 'size' => 'full'])
    </button>
    <div class="song-item-act">
        <span class="song-item-flag flag:{{ strtoupper($song['language']['flag']) }}" title="{{ $song['language']['name'] }}"></span>
        <div class="song-item-act-name">
            {{ $song['act']['name'] }}
        </div>
        <button class="song-item-golden-buzzer button gold icon" type="button"
                title="Award a Golden Buzzer"
                command="show-modal" commandfor="golden-buzzer-dialog-{{ $song['act_id'] }}"
                aria-controls="golden-buzzer-dialog-{{ $song['act_id'] }}">
            <i class="fa-solid fa-star"></i>
        </button>
    </div>
    @include('front.contest.golden-buzzer', ['stage' => $stage, 'song' => $song, 'round' => $current_round])
</div>
