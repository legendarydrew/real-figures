@extends('front.contest')

@section('page-title', "Voting ended for {$stage['title']}")
@section('page-description', "The results for {$stage['title']} are being tallied...")

@section('contest-header')
    <h1>{{ $stage['title'] }}</h1>
    <p>{!! $stage['description'] !!}</p>
@endsection

@section('contest-content')
@endsection
