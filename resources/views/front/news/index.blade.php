@extends('front.layout')

@section('page-title', 'Contest News')
@section('page-description', 'Stay updated with the latest news from the CATAWOL Records Song Contest — announcements, round results, artist highlights, and behind-the-scenes stories.')

@section('content')

    <div class="site-container my-8">

        <div class="page-banner">Page banner</div>

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

            {{--            <Pagination results={posts} onPageChange={pageHandler}/>--}}
        </div>

        <aside class="news-aside">
            <h2 class="page-subheading">About CATAWOL Records</h2>
            <p>CATAWOL Records is a music label championing bold voices and meaningful messages. We
                support artists who use creativity to challenge norms, raise awareness, and connect
                through sound.</p>
        </aside>

@endsection
