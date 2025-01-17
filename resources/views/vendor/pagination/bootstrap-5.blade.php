@if ($paginator->hasPages())
  <nav class="d-flex flex-column gap-3 flex-md-row align-items-center justify-content-between">
    <div>
      @if ($paginator->firstItem())
        <p class="m-0 text-secondary">
          Menampilkan
          <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
          hingga
          <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
          dari
          <span class="fw-semibold">{{ $paginator->total() }}</span>
          baris
        </p>
      @else
        <p class="m-0">Silakan kembali ke Page 1</p>
      @endif
    </div>
    <div>
      <ul class="pagination m-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page-link h-100" aria-hidden="true">
              <svg class="icon h-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
              </svg>
            </span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link h-100" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
              <svg class="icon h-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
              </svg>
            </a>
          </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page"><span class="page-link fs-3">{{ $page }}</span></li>
              @else
                <li class="page-item"><a class="page-link fs-3" href="{{ $url }}">{{ $page }}</a></li>
              @endif
            @endforeach
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <li class="page-item">
            <a class="page-link h-100" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
              <svg class="icon h-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6" />
              </svg>
            </a>
          </li>
        @else
          <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page-link h-100" aria-hidden="true">
              <svg class="icon h-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6" />
              </svg>
            </span>
          </li>
        @endif
      </ul>
    </div>
  </nav>
@endif
