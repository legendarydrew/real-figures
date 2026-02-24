<div class="act-image size-{{$size ?? 10}} {{ $act['image'] ? 'bg-act-image' : ''}}">
    @if($act['image'])
        <div class="act-image-bg" style="background-image: url({{$act['image'] }})"></div>
    @else
        <div class="act-image-ph">
            <i class="fa-solid fa-person text-2xl"></i>
        </div>
    @endif
</div>
