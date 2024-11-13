@props(['searchField' => 'name', 'isEmpty' => false])

@php
  $totalFilter = collect(request()->filter)->except($searchField)->count();
@endphp

<div class="col-12">
  <div class="d-flex flex-column flex-md-row justify-content-between">
    @if ($searchField)
      <form id="dtb-search" class="col-12 col-md-6 col-lg-4">
        <label for="search" class="visually-hidden">Pencarian</label>
        <div class="input-group">
          <input type="text" id="search" name="{{ $searchField }}" class="form-control" placeholder="Pencarian" value="{{ request()->filter[$searchField] ?? '' }}" autocomplete="off">
          @if (isset(request()->filter[$searchField]))
          <button type="button" id="dtb-form-clear" class="btn btn-icon">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
          @endif
          <button type="submit" class="btn">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            Cari
          </button>
        </div>
      </form>
    @endif
    @isset($filter)
      <div class="col-12 col-md-auto mt-3 mt-md-0">
        <button type="button" class="btn btn-filter" data-bs-toggle="collapse" data-bs-target="#{{ $collapseFilterId = uniqid() }}" aria-expanded="false" aria-controls="false">
          <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filter
          @if($totalFilter)<span class="badge bg-blue-lt ms-2">{{ $totalFilter }}</span>@endif
        </button>
      </div>
    @endisset
  </div>
  @isset($filter)
    <div class="collapse mt-2 mt-md-3" id="{{ $collapseFilterId }}">
      <form id="dtb-filter" class="card">
        <div class="card-body">
          {{ $filter }}
        </div>
        <div class="card-footer bg-white">
          <button type="submit" class="btn btn-primary me-2">
            Terapkan
          </button>
          @if ($totalFilter)
          <button type="button" id="dtb-form-clear" class="btn btn-icon">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
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
  </div>
  <script src="{{ asset('static/js/datatable.js') }}"></script>
</div>
