@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="pagination-mobile">

            @if ($paginator->onFirstPage())
                <span class="pagination-link disabled">{!! __('pagination.previous') !!}</span>
            @else
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="fa-solid fa-chevron-left"></i>
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="pagination-link disabled">
                    {!! __('pagination.next') !!}
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif

        </div>

        <div class="pagination-non-mobile">

            <p class="pagination-showing">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="pagination-showing-n">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="pagination-showing-n">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="pagination-showing-n">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>

            <div class="pagination-links">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="pagination-link disabled" aria-hidden="true" aria-disabled="true"
                          aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                @else
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       aria-label="{{ __('pagination.previous') }}">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-link-sep" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page === $paginator->currentPage())
                                <span class="pagination-link active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pagination-link" href="{{ $url }}"
                                   aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                       aria-label="{{ __('pagination.next') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-link disabled" aria-disabled="true"
                          aria-label="{{ __('pagination.next') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
