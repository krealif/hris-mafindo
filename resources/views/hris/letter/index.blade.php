@extends('layouts.dashboard', [
  'title' => 'Persuratan'
])

@section('content')
<div class="page-wrapper">
  <!-- Judul Halaman -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex gap-2 justify-content-between align-items-center">
          <h1 class="page-title">
            Persuratan
          </h1>
          @can('create-letter')
          <a href="{{ route('letter.template') }}" class="btn btn-primary">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Buat
          </a>
          @endcan
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dtb.datatable searchField="name" total="{{ $letters->count() }}">
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-striped datatable">
            <thead>
              <tr>
                <th>Surat</th>
                <th>Status</th>
                <th>Tanggal & Waktu</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($letters as $letter)
                <tr>
                  <td>{{ $letter->letterTemplate->name }}</td>
                  <td><x-letter-status status="{{ $letter->status }}" /></td>
                  <td><x-carbon datetime="{{ $letter->created_at }}" /></td>
                  <td>
                    <div class="btn-list flex-nowrap justify-content-end">
                      @if($letter->status == 'selesai')
                      <a href="{{ route('letter.download', $letter->id) }}" class="btn" target="_blank">
                        <svg class="icon text-green" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#download"/></svg>
                        Download
                      </a>
                      @endif
                      @if($letter->status == 'menunggu')
                      <a href="{{ route('letter.edit', $letter->id) }}" class="btn">
                        <svg class="icon text-blue" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#pen-line"/></svg>
                        Edit
                      </a>
                      @endif
                      <a href="{{ route('letter.show', $letter->id) }}" @class(['btn', 'btn-icon' => !in_array($letter->status, ['diproses', 'ditolak'])])>
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#eye"/></svg>
                        {{ in_array($letter->status, ['diproses', 'ditolak']) ? 'Detail' : '' }}
                      </a>
                      @if($letter->status == 'menunggu')
                      <button class="btn btn-icon">
                        <svg class="icon text-red" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#trash-2"/></svg>
                      </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </x-dtb.datatable>
      </div>
    </div>
</div>
@endsection

@push('icons')
<svg class="d-none">
  <symbol id="download" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></symbol>
  <symbol id="eye" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></symbol>
  <symbol id="pen-line" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/></symbol>
  <symbol id="text-search" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 6H3"/><path d="M10 12H3"/><path d="M10 18H3"/><circle cx="17" cy="15" r="3"/><path d="m21 19-1.9-1.9"/></symbol>
  <symbol id="trash-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></symbol>
  <symbol id="lock" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
</svg>
@endpush
