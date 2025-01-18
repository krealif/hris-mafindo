@props([
    'search' => null,
    'searchPlaceholder' => null,
    'collection' => null,
])

@php
  $totalFilter = collect(request()->filter)
      ->except($search)
      ->count();
@endphp

<div id="dt-datatable">
  @if (isset($search) || isset($filterForm) || isset($actions))
    <div class="d-flex flex-column flex-md-row justify-content-between">
      @if ($search)
        <form id="dt-search" class="col-12 col-md-6 col-lg-4">
          <label for="search" class="visually-hidden">Pencarian</label>
          <div class="row g-2">
            <div class="col">
              <div class="input-group">
                <x-form.input id="search" name="{{ $search }}" type="text" :showError=false value="{{ request()->filter[$search] ?? '' }}"
                  placeholder="{{ $searchPlaceholder ?? 'Pencarian' }}" />
                @if (isset(request()->filter[$search]))
                  <button type="button" id="dt-btn-clear" class="btn btn-icon">
                    <x-lucide-x class="icon text-red" defer />
                  </button>
                @endif
              </div>
            </div>
            <div class="col-auto">
              <button type="submit" class="btn">
                <x-lucide-search class="icon" />
                Cari
              </button>
            </div>
          </div>
        </form>
      @endif
      @if (isset($filterForm) || isset($actions))
        <div @class(['col-12 col-md-auto', 'mt-md-0 mt-3' => $search])>
          <div class="btn-list">
            @isset($filterForm)
              <button type="button" class="btn btn-filter collapsed" data-bs-toggle="collapse" data-bs-target="#{{ $collapseFilterId = uniqid() }}" aria-expanded="false"
                aria-controls="false">
                <x-lucide-filter class="icon" />
                Filter
                @if ($totalFilter)
                  <span class="badge bg-blue-lt ms-2">{{ $totalFilter }}</span>
                @endif
              </button>
            @endisset
            {{ $actions ?? '' }}
          </div>
        </div>
      @endif
    </div>
  @endif
  @isset($filterForm)
    <div class="collapse mt-2 mt-md-3" id="{{ $collapseFilterId }}">
      <form id="dt-filter" class="card card-mafindo">
        <div class="card-body">
          {{ $filterForm }}
          <div class="btn-list mt-4">
            <button type="submit" class="btn btn-primary me-2">
              Terapkan
            </button>
            @if ($totalFilter)
              <button type="button" id="dt-btn-clear" class="btn btn-icon">
                <x-lucide-x class="icon text-red" defer />
              </button>
            @endif
          </div>
        </div>
      </form>
    </div>
  @endisset
  <div @class(['card', 'mt-3' => $search || isset($filterForm)])>
    @if (!empty(request()->filter))
      <div class="card-body bg-azure-lt fs-4 px-3 py-2 text-blue">
        <span class="fw-bold text-uppercase">
          [{{ count(request()->filter) }}] Filter Diterapkan:
        </span>
        @foreach (request()->filter as $filter)
          @if ($loop->last)
            {{ $filter }}
          @else
            {{ $filter }},
          @endif
        @endforeach
      </div>
    @endif
    <div class="table-responsive">
      {{ $slot }}
    </div>
    @if (method_exists($collection, 'hasPages') && $collection->hasPages())
      <div class="card-footer">
        {{ $collection->links() }}
      </div>
    @endif
    @if ($collection->count() == 0)
      <div class="empty">
        <div class="empty-icon">
          <x-lucide-circle-slash-2 class="icon" />
        </div>
        <h3 class="empty-title m-0">Tidak ada data untuk ditampilkan</h3>
      </div>
    @endif
  </div>
  <script src="{{ asset('static/js/datatable.js') }}"></script>
</div>
