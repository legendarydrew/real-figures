@extends('email.layout')
@section('title', 'Confirm your subscription...')
@section('content')
    <h1>{{ $post->title }}</h1>
    <p>
        <small>Posted on {{ $post->created_at->format(config('contest.date_format')) }}</small>
    </p>
    <p>{{ \Illuminate\Support\Str::markdown($post->body) }}</p>
@endsection
