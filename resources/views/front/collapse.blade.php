<details class="content-collapse" id="{{ Str::slug($title) }}">
    <summary class="content-collapse-title">
        @if (isset($icon))
            <i class="{{ $icon }} mr-4"></i>
        @endif
        {{ $title }}
    </summary>
    <div class="content-collapse-body content">
        @include($content)
    </div>
</details>
