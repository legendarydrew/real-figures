<section class="content-collapse">
    <label class="content-collapse-title">
        <input type="checkbox" name="collapse"/>
        @if (isset($icon))
            <i class="{{ $icon }} mr-4"></i>
        @endif
        <a id="{{ Str::slug($title) }}">{{ $title }}</a>
    </label>
    <div class="content-collapse-body content">
        @include($content)
    </div>
</section>
