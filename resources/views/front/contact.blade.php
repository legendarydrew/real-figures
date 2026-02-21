@extends('front.layout')

@section('page-title', 'Contact')
@section('page-description', 'Use our contact form to reach the team behind the CATAWOL Records Song Contest. We’d love to hear from you.')

@section('meta')
    <meta name="turnstile" content="{{ config('services.turnstile.site_key')}}"/>
@endsection

@section('content')
    <div class="site-container my-8">
        <div class="page-banner">Page banner</div>

        <h1 class="page-heading">Contact Us</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="content">
                <p>Have a question about the contest? Want to know more about the voting process,
                    submissions, or the Golden Buzzer? We’d love to hear from you!</p>
                <p>Whether you're an artist, a voter, a supporter, or just curious, we’re all ears.</p>

                @include('front.advert')
            </div>

            <div>
                <contact-form></contact-form>
            </div>
        </div>
    </div>
@endsection
