@extends('email.layout')
@section('title', $post->title)
@section('content')
    <p>
        <small>Posted on {{ $post->created_at->format(config('contest.date_format')) }}</small>
    </p>
    <p>{!! \Illuminate\Support\Str::markdown($post->body) !!}</p>
@endsection
