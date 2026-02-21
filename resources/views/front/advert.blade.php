<div class="{{ $class ?? 'advert' }}">
    <ins class="adsbygoogle"
         style="display: block"
         data-ad-client="{{ config('services.adsense.client_id') }}"
         data-ad-slot="{{ config('services.adsense.slot_id') }}"
         {{--         data-ad-layout={layout}--}}
         {{--         data-ad-layout-key={layoutKey}--}}
         {{--         data-ad-format={format}--}}
         @if (config('services.adsense.testing'))
             data-adtest="on"
         data-ad-test="on"
         @endif
         data-full-width-responsive=""></ins>
</div>
