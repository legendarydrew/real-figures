<span class="act-image size-{{$size ?? 10}} {{ $act['image'] ? 'bg-act-image' : ''}}">
@if($act['image'])
    <span class="act-image-bg" style="background-image: url({{ $act['image'] }})"></span>
@else
    <span class="act-image-ph">
        <svg>
            <use href="{{ asset('img/catawol-icon.svg') }}" height="100%" width="100%"></use>
        </svg>
    </span>
@endif
</span>
