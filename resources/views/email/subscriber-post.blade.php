@extends('email.layout')
@section('title', $post->title)
@section('content')
    <p>
        <small>Posted on {{ $post->created_at->format(config('contest.format.full-date')) }}</small>
    </p>
    <p>{!! \Illuminate\Support\Str::markdown($post->body) !!}</p>
@endsection
