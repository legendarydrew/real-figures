<div class="act-image size-{{$size ?? 10}} {{ $act['image'] ? 'bg-act-image' : ''}}">
    @if($act['image'])
        <div class="act-image-bg" style="background-image: url({{$act['image'] }})"></div>
    @else
        <div class="act-image-ph">
            <img src="{{ asset('img/catawol-icon.svg') }}" alt="">
        </div>
    @endif
</div>
