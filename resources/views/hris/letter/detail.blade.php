@extends('layouts.dashboard', [
  'title' => 'Detail Surat'
])

@section('content')
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="d-flex gap-2 justify-content-between align-items-center">
        <div>
          <div class="mb-1">
            <x-breadcrumb>
              <x-breadcrumb-item label="Persuratan" route="letter.index" />
            </x-breadcrumb>
          </div>
          <h1 class="page-title">
            Detail Surat
          </h1>
        </div>
        @can('update', $letter)
        <div class="btn-list">
          <a href="{{ route('letter.edit', $letter->id) }}" class="btn">
            <svg class="icon text-blue" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/></svg>
            Edit
          </a>
          <a class="btn btn-icon">
            <svg class="icon text-red" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
          </a>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title badge bg-dark text-white me-2">{{ $letter->letterTemplate->name }}</h2>
            <x-letter-status class="card-title" status="{{ $letter->status }}" />
          </div>
          <div class="card-body">
            <h4 class="fs-3">Isi Surat</h4>
            <div class="datagrid">
              @foreach($letter->contents as $label => $value)
              <div class="datagrid-item">
                <div class="datagrid-title fs-4">{{ $label }}</div>
                <div class="datagrid-content">{{ $value }}</div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @if($letter->message)
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Alasan Penolakan</h2>
          </div>
          <div class="card-body">
            <p>{{ $letter->message }}</p>
          </div>
        </div>
      </div>
      @endif
      @canany(['review-letter', 'view-letter'])
        <!-- File Download -->
        @if($letter->file && (auth()->user()->can('review-letter') || $letter->status === 'selesai'))
        <div class="col-12 col-md-6">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">File</h2>
            </div>
            <div class="card-body">
              <div class="mb-4">
                <a href="{{ route('letter.download', $letter->id) }}" class="d-flex flex-row fs-3" target="_blank">
                  <svg class="icon me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                  {{ explode('/', $letter->file)[1] }}
                </a>
                <div class="datagrid mt-3">
                  <div class="datagrid-item">
                    <div class="datagrid-title fs-4">Diupload Oleh</div>
                    <div class="datagrid-content">{{ $letter->admin }}</div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title fs-4">Tanggal & Waktu</div>
                    <div class="datagrid-content">{{ $letter->updated_at }}</div>
                  </div>
                </div>
              </div>
              <a href="{{ route('letter.download', $letter->id) }}" class="btn btn-primary" target="_blank">Download</a>
            </div>
          </div>
        </div>
        @endif
      @endcanany
    </div>
  </div>
</div>
@endsection
