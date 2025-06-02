@extends('email.layout')
@section('title', 'Responding to your message...')
@section('content')
    <p>{{ $response }}</p>
    <p>&mdash; Drew (SilentMode)</p>

    <p>This is in response to your message, sent
        on {{ $original_message->created_at->format(config('contest.date_format')) }}:</p>
    <blockquote>{{ $original_message->body }}</blockquote>
@endsection
