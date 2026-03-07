<div class="{{ $class ?? 'advert' }}">
    <ins class="adsbygoogle"
         style="display: block"
         data-ad-client="{{ config('services.adsense.client_id') }}"
         data-ad-slot="{{ config('services.adsense.slot_id') }}"
         data-ad-format="auto"
         @if (config('services.adsense.testing'))
             data-adtest="on"
         @endif
         data-full-width-responsive=""></ins>
</div>
