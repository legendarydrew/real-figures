<div
    class="relative flex b-2 aspect-square w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 items-center flex-col justify-end overflow-hidden select-none">
    @include('front.act-image', ['act' => $act, 'size' => 'full'])

    <div class="absolute bottom w-full flex justify-between items-center px-3 py-2">
        <span class="display-text flex-grow pr-3 text-lg leading-tight text-left">{{$act['name']}}</span>
    </div>
</div>
