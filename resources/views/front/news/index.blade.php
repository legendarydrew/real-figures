@extends('front.layout')

@section('page-title', 'Contest News')
@section('page-description', 'Stay updated with the latest news from the CATAWOL Records Song Contest — announcements, round results, artist highlights, and behind-the-scenes stories.')
@section('page-url', route('news'))
@section('content')

    <div class="site-container my-8">

        <div class="page-banner">
            <img src="{{ asset('img/banners/news-7.jpg') }}" alt="A news reporter at her desk.">
        </div>

        <h1 class="page-heading">Contest News</h1>

        <div class="news-index">
            @foreach ($posts['data'] as $post)
                <article class="news-index-item">
                    <a href="{{ $post['url'] }}">
                        <div class="news-index-item-icon">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <div class="news-index-item-details">
                            <h2 class="news-index-item-title">{{$post['title']}}</h2>
                            <p class="news-index-item-excerpt">{{$post['excerpt']}}</p>
                            <p class="news-index-item-date">Posted on {{$post['published_at']}}</p>
                        </div>
                    </a>
                </article>
            @endforeach

                {{ $paginator->links() }}
        </div>

    @include('front.advert')

    @include('front.news.about')
@endsection
