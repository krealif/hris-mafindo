@extends('layouts.dashboard', [
'title' => 'Materi',
])

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <h1 class="page-title">
                    Kumpulan Materi
                </h1>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <x-dt.datatable search="title" searchPlaceholder="Cari judul materi" :collection="$materis">
                <table class="table table-vcenter card-table table-striped datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Materi Ajaran</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materis as $materi)
                        <tr x-data="{ id: {{ $materi->id }} }">
                            <td>{{$no++}}</td>
                            <td><b>{{ $materi->title }}</b></td>
                            <td>
                                <div class="btn-list flex-nowrap justify-content-end">
                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#modal-tautan"
                                        x-on:click="$dispatch('set-link', { url: '{{ $materi->url }}'  })">
                                        <x-lucide-link class="icon text-blue" />
                                        Tautan
                                    </button>
                                    <button class="btn" onclick="window.open('{{ $materi->url }}', '_blank')">
                                        <x-lucide-arrow-right class="icon text-primary" />
                                        Buka Materi
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($materis->hasPages())
                <!-- Pagination -->
                <x-slot:pagination>
                    {{ $materis->links() }}
                    </x-slot>
                    @endif
            </x-dt.datatable>
        </div>
    </div>
</div>
<x-modal-tautan baseUrl="{{ url('/materi') }}" />
@endsection