@if ($paginator->hasPages())
    <div class="mt-4">
            @if ($paginator->hasMorePages())
                    <a class="float-left" href="{{ $paginator->nextPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">< Previous week</a>
            @endif

            @if ($paginator->onFirstPage() == false)
                    <a class="float-right" href="{{ $paginator->previousPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next week ></a>
            @endif
    </div>
    <div class="clearfix"></div>
@endif
