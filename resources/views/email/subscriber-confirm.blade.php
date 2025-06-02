@extends('email.layout')
@section('title', 'Confirm your subscription...')
@section('content')
    <p>Someone &ndash; hopefully you &ndash; has subscribed this email address to updates to the
        <b>Real Figures Don't F.O.L.D</b> song contest.</p>
    <p>If it <i>was</i> you, or you want stay updated with the contest progress, please <a
            href="{{ $confirm_url }}">confirm your subscription</a> by visiting the following link:</p>
    <p>{{ $confirm_url }}</p>
    <p>If it <i>wasn't</i> you, you can safely ignore this email.</p>
    <p>Either way, your details will not be used for any other purpose than keeping you informed about the contest.</p>
@endsection
