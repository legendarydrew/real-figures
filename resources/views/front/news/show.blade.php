@extends('front.layout')

@section('page-title', $post['title'])
@section('page-description', $post['excerpt'])
@section('page-image', asset('img/og/og-news.jpg'))
@section('page-type', 'article')
@section('meta')
    @if(isset($post['pages']['previous']))
        <link rel="prev" href="{{$post['pages']['previous']['url']}}"/>
    @endif
    @if(isset($post['pages']['next']))
        <link rel="prev" href="{{$post['pages']['next']['url']}}"/>
    @endif
@endsection

@section('open-graph')
    <meta property="article:section" content="Contest News">
    <meta property="article:published_time" content="{{ $post['published_at'] }}">
    <meta property="article:modified_time" content="{{ $post['updated_at'] }}">
    <meta property="article:tag" content="press release">
    <meta property="article:tag" content="song contest">
    <meta property="article:tag" content="catawol records">
    <meta property="article:tag" content="silentmode">
    <meta property="article:tag" content="ai music">
@endsection

@section('content')

    <article class="site-container news-post" itemscope itemtype="https://schema.org/BlogPosting">

        <div class="page-banner">
            <img src="{{ asset('img/banners/news-7.jpg') }}" alt="A news reporter at her desk.">
        </div>

        <header class="news-post-header">
            <h1 class="news-post-heading" itemprop="headline">{{$post['title']}}</h1>
            @if($post['published_at'])
                <p class="news-post-date">
                    <span itemprop="author">CATAWOL Records</span>,
                    <time itemprop="datePublished" datetime="{{$post['timestamp']}}">{{$post['published_at']}}</time>
                </p>
            @endif
        </header>

        <div class="content news-post-content" itemprop="articleBody">
            {!! $post['content'] !!}
        </div>

        @if($post['acts'])
            <aside class="news-post-mentions">
                <h2 class="page-subheading">Acts mentioned</h2>
                <div class="news-post-mentions-list">
                    @foreach($post['acts'] as $act)
                        @include('front.act-item', ['act' => $act])
                    @endforeach
                </div>
            </aside>
        @endif

        @include('front.advert')

        <div class="content news-enquiries">
            For enquiries, you are welcome to get in touch with us through our <a href="{{ route('contact') }}">Contact
                page</a>.
        </div>

        @if (isset($post['pages']['previous']) || isset($post['pages']['next']))
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

            <hr>
        @endif

        @if (isset($post['pages']['others']) && count($post['pages']['others']))
            <h2 class="page-subheading">More Contest News</h2>
            <menu class="news-more">
                @foreach($post['pages']['others'] as $row)
                    <a class="news-more-link" href={{$row['url']}}>
                        <div class="news-more-link-icon">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <span class="news-more-link-title">{{$row['title']}}</span>
                    </a>
                @endforeach
            </menu>
        @endif

        @include('front.news.about')
    </article>

@endsection
