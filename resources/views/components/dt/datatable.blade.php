@props([
    'search' => null,
    'searchPlaceholder' => null,
    'total' => null,
])

@php
  $totalFilter = collect(request()->filter)
      ->except($search)
      ->count();
@endphp

<div id="dt-datatable" class="col-12">
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
                  <x-lucide-x class="icon text-red" />
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
  @isset($filterForm)
    <div class="collapse mt-2 mt-md-3" id="{{ $collapseFilterId }}">
      <form id="dt-filter" class="card">
        <div class="card-body">
          {{ $filterForm }}
        </div>
        <div class="card-footer bg-white">
          <button type="submit" class="btn btn-primary me-2">
            Terapkan
          </button>
          @if ($totalFilter)
            <button type="button" id="dt-btn-clear" class="btn btn-icon">
              <x-lucide-x class="icon text-red" />
            </button>
          @endif
        </div>
      </form>
    </div>
  @endisset
  <div @class(['card', 'mt-3' => $search || isset($filterForm)])>
    <div class="table-responsive">
      {{ $slot }}
    </div>
    @isset($pagination)
      <div class="card-footer">
        {{ $pagination }}
      </div>
    @endisset
    @if (isset($total) && $total == 0)
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
