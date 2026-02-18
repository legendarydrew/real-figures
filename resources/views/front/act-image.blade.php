<div class="aspect-square w-{{$size ?? 10}} h-{{$size ?? 10}} {{ $act['image'] ? 'bg-act-image' : ''}}">
    @if($act['image'])
        <div class="w-full h-full bg-cover bg-center z-0"
             style="background-image: url({{$act['image'] }})"></div>
    @else
        <div class="w-full h-full z-0 flex items-center justify-center text-gray-500 select-none">
            <i class="fa-solid fa-person text-2xl"></i>
        </div>
    @endif
</div>
