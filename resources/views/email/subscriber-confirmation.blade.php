@extends('email.layout')
@section('title', 'You\'re In!')
@section('content')
    <p>Thanks for subscribing!</p>
    <p>You're now on the list to receive updates about <b>{{ config('app.name') }}</b>: voting launches, Song drops,
        finalist announcements, and more.</p>
    <p>Keep an eye on your inbox... the music is just getting started.</p>
@endsection
