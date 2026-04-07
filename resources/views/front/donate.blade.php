@extends('front.layout')

@section('page-title', 'Donations')
@section('page-description', "Support the CATAWOL Records Song Contest and help us amplify creativity and raise awareness of bullying. Learn how to donate and make a difference.")
@section('page-image', asset('img/og/og-donate.jpg'))
@section('event-category', 'Donate')

@section('content')
    <div class="site-container">

        <div class="page-banner">
            <img src="{{ asset('img/banners/donate-2.jpg') }}"
                 alt="Minifigures being presented with a large cheque in a public setting.">
        </div>

        <h1 class="page-heading">Your Donations</h1>

        <!-- Donation buttons. -->
        <div class="donate-buttons">

            {{-- https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dialog#modal_dialogs_using_invoker_commands  --}}
            <button type="button" class="button primary"
                    command="show-modal" commandfor="donate-dialog" aria-controls="donate-dialog"
                    onclick="trackEvent('dialog_open', { type: 'donate' })">Donate to us (PayPal)
            </button>

            <a class="button secondary" href="https://www.justgiving.com/team/real-figures?utm_medium=TE&utm_source=CL" rel="external"
               target="_blank">Donate to charity</a>
        </div>

        <div class="content">
            <p class="content-intro">
                If you're enjoying the Contest, please consider <b>making a donation:</b> either to us,
                or to one of our selected charities.
            </p>
        </div>

        <div class="my-8">
            @include('front.collapse', ['title' => 'Supporting us', 'content' => 'front.donate.us'])
            @include('front.collapse', ['title' => 'Supporting mental health and anti-bullying initiatives', 'content' => 'front.donate.charities'])
        </div>

        <!-- Donations -->
        @if (isset($donations) && count($donations))
            <h2 class="page-subheading">Generous Donations</h2>
            <p>
                <b>A huge thank you</b> for these generous donations:
            </p>
            <ul class="donations-list">
                @foreach($donations as $donation)
                    <li>
                        <span class="donor-name {{ $donation['is_anonymous'] ? "anonymous" : "named"}}">
                            {{ $donation['name']}}
                        </span>
                        <span class="donor-date">{{ $donation['created_at'] }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Golden Buzzers -->
        @if (isset($buzzers) && count($buzzers))
            <h2 class="page-subheading">Golden Buzzers</h2>
            <p>These donations were in support of specific Acts:</p>
            <ul class="golden-buzzers-list">
                @foreach($buzzers as $donation)
                    <li>
                <span class="donor-name {{ $donation['is_anonymous'] ? "anonymous" : "named" }}">
                    {{ $donation['name'] }}
                </span>
                        <span class="donor-date">{{$donation['created_at']}}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        @include('front.advert')

    </div>

    <dialog id="donate-dialog" class="dialog donate-dialog">
        <button class="dialog-close" command="close" commandfor="donate-dialog" aria-controls="donate-dialog"
                title="Close">
            <i class="fa-solid fa-close"></i>
        </button>
        <h2 class="dialog-title">Make a Donation</h2>
        <donate-dialog></donate-dialog>
    </dialog>
@endsection
