@extends('front.contest')

@section('page-title', $stage['title'])
@section('page-description', "Voting is underway for {$stage['title']}.")

@section('contest-header')
    <h1>{{ $stage['title'] }}</h1>
    <p>{!! $stage['description'] !!}</p>
@endsection

@section('contest-content')
@endsection
