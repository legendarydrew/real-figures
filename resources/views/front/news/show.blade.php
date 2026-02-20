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
    </article>

    {{--
        <aside className="lg:w-1/5 flex flex-col gap-5">

            <Advert height={200}/>

                {post.pages?.others?.length && (
                <menu className="flex flex-col gap-1">
                    <HeadingSmall title="More press releases"/>
                    {post.pages?.others.map((row) => (
                    <Link key={row.url}
                          className="flex gap-3 hover-bg p-2 text-sm font-display leading-tight"
                          href={row.url}>
                    <NewspaperIcon className="w-4 flex-shrink-0"/>
                    {row.title}
                    </Link>
                    ))}
                </menu>
                )}

                <div className="text-sm leading-tight">
                    <HeadingSmall title="About CATAWOL Records"/>
                    <p>CATAWOL Records is a music label championing bold voices and meaningful messages. We
                        support artists who use creativity to challenge norms, raise awareness, and connect
                        through sound.</p>
                </div>

                <div className="flex flex-row max-h-[8rem] lg:max-h-none lg:flex-col gap-3">
                    <PlaceholderPattern className="w-[8rem] lg:w-auto flex-shrink-0 stroke-zinc-300"
                                        title="SilentMode banner"/>
                    <BrickTherapyLink className="max-h-[8rem] lg:max-h-none lg:flex-shrink-0"/>
                </div>

        </aside>
--}}
    </article>
@endsection
