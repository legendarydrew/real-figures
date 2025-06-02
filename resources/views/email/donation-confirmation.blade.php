@extends('email.layout')
@section('title', 'Thank you!')
@section('content')
    <p>Hello {{ $donation->name }},</p>
    <p>Just a quick email to acknowledge that your donation of <strong>{{ $donation->currency }}
            {{ number_format($donation->amount, 2) }}</strong> has been received.</p>
    <p>On behalf of "CATAWOL Records", thank you for your support!</p>
    <p>&mdash; Drew (SilentMode)</p>
@endsection
