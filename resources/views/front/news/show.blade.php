@extends('front.layout')

@section('page-title', $post['title'])
@section('page-description', $post['excerpt'])
@section('meta')
    @if(isset($post['pages']['previous']))
        <link rel="prev" href="{{$post['pages']['previous']['url']}}"/>
    @endif
    @if(isset($post['pages']['next']))
        <link rel="prev" href="{{$post['pages']['next']['url']}}"/>
    @endif
@endsection

@section('content')

    <article class="site-container news-post">

        <div class="page-banner">Page banner</div>

        <header class="news-post-header">
            <h1 class="news-post-heading">{{$post['title']}}</h1>
            @if($post['published_at'])
                <p class="news-post-date">CATAWOL Records,
                    <time>{{$post['published_at']}}</time>
                </p>
            @endif
        </header>

        <div class="content news-post-content">
            {!! $post['content'] !!}
        </div>

        <div class="content news-enquiries">
            For enquiries, you are welcome to get in touch with us through our <a href="{{ route('contact') }}">Contact
                page</a>.
        </div>

        @if (isset($post['pages']['previous']) || isset($post['pages']['next']))
            <hr>
            <nav class="news-nav">
                @if ($post['pages']['previous'])
                    <a class="news-nav-prev" href="{{$post['pages']['previous']['url']}}" rel="prev">
                        <span class="news-nav-title">{{$post['pages']['previous']['title']}}</span>
                        <b class="news-nav-label">
                            <i class="fa-solid fa-chevron-left"></i>
                            Previous post
                        </b>
                    </a>
                @endif
                @if ($post['pages']['next'])
                    <a class="news-nav-next" href="{{$post['pages']['next']['url']}}" rel="next">
                        <span class="news-nav-title">{{$post['pages']['next']['title']}}</span>
                        <b class="news-nav-label">
                            Next post
                            <i class="fa-solid fa-chevron-right"></i>
                        </b>
                    </a>
                @endif
            </nav>
        @endif

        @if (isset($post['pages']['others']) && count($post['pages']['others']))
            <h2 class="page-subheading">More Contest News</h2>
            <menu class="news-more">
                @foreach($post['pages']['others'] as $row)
                    <a class="news-more-link" href={{$row['url']}}>
                        {{$row['title']}}
                    </a>
                @endforeach
            </menu>
        @endif

        @include('front.news.about')
    </article>

@endsection
