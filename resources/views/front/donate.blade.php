@extends('front.layout')

@section('page-title', 'Donations')
@section('page-description', "Support the CATAWOL Records Song Contest and help us amplify creativity and raise awareness of bullying. Learn how to donate and make a difference.")

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
                    command="show-modal" commandfor="donate-dialog" aria-controls="donate-dialog">Donate to us (PayPal)
            </button>

            <a class="button secondary" href="https://www.justgiving.com/kidscape/donate" rel="external"
               target="_blank">Donate to Kidscape</a>
        </div>

        <div class="content">
            <p class="content-intro">
                If you're enjoying the Contest, please consider <b>making a donation:</b> either to us,
                or to <a href="https://kidscape.org.uk" rel="external" target="_blank">Kidscape</a>, our chosen
                charity.
            </p>
            <p>Donations made to us will go toward the costs associated with building and maintaining the site,
                supporting our Acts and their music production, and aiding the MODE Family in their time of need.
            </p>
            <p><a href="https://kidscape.org.uk" rel="external" target="_blank">Kidscape</a>
                provides telephone advice and works directly with parents, children and carers experiencing bullying,
                across the United Kingdom.</p>
            <p>You can also <a href="{{route('contact')}}">contact us</a> directly with other suggestions for supporting
                the Contest.</p>
        </div>

        <!-- Donations -->
        @if (isset($donations) && count($donations))
            <hr>
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

    <dialog id="donate-dialog" class="dialog">
        <button class="dialog-close" command="close" commandfor="donate-dialog" aria-controls="donate-dialog"
                title="Close">
            <i class="fa-solid fa-close"></i>
        </button>
        <h2 class="dialog-title">Make a Donation</h2>
        <donate-dialog></donate-dialog>
    </dialog>
@endsection
