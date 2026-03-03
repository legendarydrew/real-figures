<dialog id="golden-buzzer-dialog-{{ $song['act_id'] }}" class="dialog golden-buzzer-dialog">
    <button class="dialog-close" command="close" commandfor="golden-buzzer-dialog-{{ $song['act_id'] }}"
            aria-controls="golden-buzzer-dialog-{{ $song['act_id'] }}"
            title="Close">
        <i class="fa-solid fa-close"></i>
    </button>
    <h2 class="dialog-title">
        Award a Golden Buzzer to...
    </h2>
    <golden-buzzer-dialog stage="{{ json_encode($stage) }}" round="{{ json_encode($round) }}" song="{{ json_encode($song) }}"></golden-buzzer-dialog>
</dialog>
