@extends('front.layout')

@section('page-title', 'Contact')
@section('page-description', 'Use our contact form to reach the team behind the CATAWOL Records Song Contest. We’d love to hear from you.')
@section('event-category', 'Contact')

@section('meta')
    <meta name="turnstile" content="{{ config('services.turnstile.site_key')}}"/>
@endsection

@section('content')
    <div class="site-container">
        <div class="page-banner">
            <img src="{{ asset('img/banners/contact-2.jpg') }}" alt="Minifigures reading and handling fan mail.">
        </div>

        <h1 class="page-heading">Contact Us</h1>

        <div class="contact-layout">
            <div class="content">
                <p>Have a question about the Contest? Want to know more about the voting process,
                    submissions, or the Golden Buzzer? We’d love to hear from you!</p>
                <p>Whether you're an artist, a voter, a supporter, or just curious, we’re all ears.</p>

                @include('front.advert')
            </div>
            <div class="contact-layout-form">
                <contact-form></contact-form>
            </div>
        </div>
    </div>
@endsection
