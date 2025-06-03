@extends('email.layout')
@section('title', 'Thank you!')
@section('content')
    <p>Hello {{ $donation->name }},</p>
    <p>Just a quick email to acknowledge you hitting the Golden Buzzer
        for <strong>{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</strong>,
        supporting <strong>{{ $donation->song->act->name }}'s entry</strong>
        in <strong>{{ $donation->round->full_title }}</strong>.</p>
    <p>On behalf of "CATAWOL Records", thank you for your support!</p>
    <p>&mdash; Drew (SilentMode)</p>
@endsection
