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
                <a href="{{ route('materi.create') }}" class="btn btn-primary">
                    <x-lucide-plus class="icon" />
                    Buat
                </a>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if (flash()->message)
            <x-alert type="{{ flash()->class }}">
                {{ flash()->message }}
            </x-alert>
            @endif

            <x-dt.datatable search="title" searchPlaceholder="Cari judul materi" :collection="$materis">
                <table class="table table-vcenter card-table table-striped datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Judul Materi</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materis as $materi)
                        <tr x-data="{ id: {{ $materi->id }} }">
                            <td>{{$no++}}</td>
                            <td>{{ $materi->title }}</td>
                            <td>
                                <div class="btn-list flex-nowrap justify-content-end">
                                    <a href="{{ route('materi.edit', $materi->id) }}" class="btn">
                                        <x-lucide-pen-line class="icon text-blue" defer />
                                        Edit
                                    </a>
                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#modal-tautan"
                                        x-on:click="$dispatch('set-link', { url: '{{ $materi->url }}'  })">
                                        <x-lucide-link class="icon text-blue" />
                                        Tautan
                                    </button>
                                    <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete"
                                        x-on:click="$dispatch('set-id', { id: {{ $materi->id }} })">
                                        <x-lucide-trash-2 class="icon text-red" defer />
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
<x-modal-delete baseUrl="{{ url('/materi') }}" />
<x-modal-tautan baseUrl="{{ url('/materi') }}" />
@endsection