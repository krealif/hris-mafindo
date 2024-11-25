@props([
  'search' => null,
  'total' => null,
])

@php
  $totalFilter = collect(request()->filter)->except($search)->count();
@endphp

<div id="dt-datatable" class="col-12">
  <div class="d-flex flex-column flex-md-row justify-content-between">
    @if($search)
      <form id="dt-search" class="col-12 col-md-6 col-lg-4">
        <label for="search" class="visually-hidden">Pencarian</label>
        <div class="row g-2">
          <div class="col">
            <div class="input-group">
              <input type="text" id="search" name="{{ $search }}" class="form-control" placeholder="Pencarian" value="{{ request()->filter[$search] ?? '' }}" autocomplete="off">
              @if(isset(request()->filter[$search]))
                <button type="button" id="dt-btn-clear" class="btn btn-icon">
                  <svg class="icon text-red" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
              @endif
            </div>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn">
              <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              Cari
            </button>
          </div>
        </div>
      </form>
    @endif
    @if(isset($filterForm) || isset($actionBtn))
      <div @class(['col-12 col-md-auto', 'mt-md-0 mt-3' => $search])>
        <div class="btn-list">
          @isset($filterForm)
            <button type="button" class="btn btn-filter" data-bs-toggle="collapse" data-bs-target="#{{ $collapseFilterId = uniqid() }}" aria-expanded="false" aria-controls="false">
              <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
              Filter
              @if($totalFilter)<span class="badge bg-blue-lt ms-2">{{ $totalFilter }}</span>@endif
            </button>
          @endisset
          {{ $actionBtn ?? '' }}
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
          @if($totalFilter)
            <button type="button" id="dt-form-clear" class="btn btn-icon">
              <svg class="icon text-red" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
          @endif
        </div>
      </form>
    </div>
  @endisset
  <div class="card mt-3">
    <div class="table-responsive">
      {{ $slot }}
    </div>
    @isset ($pagination)
      <div class="card-footer">
        {{ $pagination }}
      </div>
    @endisset
    @if(isset($total) && $total == 0)
      <div class="empty">
        <div class="empty-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 2 20 20"/><path d="M8.35 2.69A10 10 0 0 1 21.3 15.65"/><path d="M19.08 19.08A10 10 0 1 1 4.92 4.92"/></svg>
        </div>
        <h3 class="empty-title m-0">Tidak ada data untuk ditampilkan</h3>
      </div>
    @endif
  </div>
  <script src="{{ asset('static/js/datatable.js') }}"></script>
</div>
