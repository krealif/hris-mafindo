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
            <x-dt.datatable :collection="$materis">
                <x-slot:actions>
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                        <form id="search-form" method="GET" action="{{ route('materi.index') }}"
                            class="d-flex align-items-center">
                            <input type="text" name="search" class="form-control" placeholder="Cari Materi..."
                                value="{{ request('search') }}" />
                            @if (request('search'))
                            <button type="button" class="btn btn-filter collapsed ms-2" onclick="clearSearch()">
                                <x-lucide-x class="icon text-danger" />
                            </button>
                            @endif
                            <button type="submit" class="btn btn-filter collapsed ms-2">
                                <x-lucide-search class="icon" />
                            </button>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-filter collapsed  dropdown-toggle" type="button" id="sortingDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Urutkan
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortingDropdown">
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('materi.index', array_merge(request()->query(), ['sort' => 'newest'])) }}">Terbaru</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('materi.index', array_merge(request()->query(), ['sort' => 'oldest'])) }}">Terlama</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('materi.index', array_merge(request()->query(), ['sort' => 'alphabet'])) }}">Abjad</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </x-slot:actions>
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
<script>
    function clearSearch() {
        const form = document.getElementById('search-form');
        const input = form.querySelector('input[name="search"]');
        input.value = '';
        form.submit();
    }
</script>
<x-modal-tautan baseUrl="{{ url('/materi') }}" />
@endsection