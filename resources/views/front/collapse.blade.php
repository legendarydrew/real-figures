<section class="content-collapse">
    <label class="content-collapse-title">
        <input type="checkbox" name="collapse"/>
        {{ $title }}
    </label>
    <div class="content-collapse-body content flex gap-8">
        @include($content)
    </div>
</section>
