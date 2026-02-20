@extends('front.layout')

@section('page-title', 'Donations')
@section('page-description', "Support the CATAWOL Records Song Contest and help us amplify creativity and raise awareness of bullying. Learn how to donate and make a difference.")

@section('content')

    <div class="site-container my-8">

        <div class="page-banner">
            page banner
        </div>

        <h1 class="page-heading">Your Donations</h1>

        <!-- Donation buttons. -->
        <div class="my-8 flex justify-center items-center gap-16">

            {{-- https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dialog#modal_dialogs_using_invoker_commands  --}}
            <button type="button" class="button primary"
                    command="show-modal" commandfor="donate-dialog" aria-controls="donate-dialog">Donate to us (PayPal)
            </button>

            <a class="button secondary" href="https://www.justgiving.com/kidscape/donate" rel="external"
               target="_blank">Donate to Kidscape</a>
        </div>

        <div class="content mb-8">
            <p class="text-lg">
                If you're enjoying the Contest, please consider <b>making a donation:</b> either to us,
                or to <a class="font-semibold hover:underline" href="https://kidscape.org.uk" rel="external"
                         target="_blank">Kidscape</a>, our designated charity.
            </p>
            <p>Donations made to us will go toward the costs associated with building and maintaining the site,
                supporting our Acts and their music production, and aiding the MODE Family in their time of need.
            </p>
            <p><a class="font-semibold hover:underline" href="https://kidscape.org.uk" rel="external" target="_blank">Kidscape</a>
                provides telephone advice and works directly with parents, children and carers experiencing bullying,
                across the United Kingdom.</p>
            <p>You can also <a class="font-semibold hover:underline" href="{{route('contact')}}">contact
                    us</a> directly with other suggestions for supporting the contest.</p>
        </div>

        <!-- Donations -->
        @if (isset($donations) && count($donations))
            <h2 class="page-subheading">Generous Donations</h2>
            <p>
                <b>A huge thank you</b> for these generous donations:
            </p>
            <ul class="donations-list">
                @foreach($donations as $donation)
                    <li class="select-none">
                <span
                    class="flex-grow truncate {{ $donation['is_anonymous'] ? "italic" : "font-semibold"}}">{{ $donation['name']}}</span>
                        <span class="text-xs text-right">{{ $donation['created_at'] }}</span>
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
                    <li class="select-none">
                <span class="{{ $donation['is_anonymous'] ? "anonymous" : "named" }}">
                    {{ $donation['name'] }}
                </span>
                        <span class="date">{{$donation['created_at']}}</span>
                    </li>
                @endforeach
            </ul>
        @endif

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
