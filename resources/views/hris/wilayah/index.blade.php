@extends('layouts.dashboard', [
'title' => 'Wilayah',
])

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <h1 class="page-title">
                    Manajemen Wilayah
                </h1>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div>
                @if (flash()->message)
                <x-alert type="{{ flash()->class }}">
                    {{ flash()->message }}
                </x-alert>
                @endif
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <x-dt.datatable search="name" searchPlaceholder="Cari wilayah" :collection="$cities">
                        <table class="table table-vcenter card-table table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No</th>
                                    <th>Daftar Wilayah</th>
                                    <th class="w-1">Aksi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cities as $city)
                                <tr x-data="{ id: {{ $city->id }} }">
                                    <td>{{$no++}}</td>
                                    <td><b>{{ $city->name }}</b></td>
                                    <td>
                                        <div class="btn-list flex-nowrap justify-content-end">
                                            <ul class="nav nav-pills gap-2" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a href="#tab-edit-{{ $city->id }}" class="btn" data-bs-toggle="tab"
                                                        aria-selected="true" role="tab"
                                                        id="tab-edit-button-{{ $city->id }}">
                                                        <x-lucide-pen-line class="icon text-blue" /> Edit
                                                    </a>
                                                </li>
                                            </ul>
                                            <button class="btn btn-icon" data-bs-toggle="modal"
                                                data-bs-target="#modal-delete"
                                                x-on:click="$dispatch('set-id', { id: {{ $city->id }} })">
                                                <x-lucide-trash-2 class="icon text-red" defer />
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tab-content">
                                            <div id="tab-edit-{{ $city->id }}" class="tab-pane tab-pane-edit">
                                                <form action="{{ route('wilayah.update', $city->id) }}"
                                                    class="card-body" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="card-body mb-3">
                                                        <label for="name" class="form-label required">Nama
                                                            Wilayah</label>
                                                        <div class="btn-list">
                                                            <x-form.input name="name" type="text"
                                                                value="{{ old('name', $city->name) }}" />
                                                            <button class="btn btn-primary" type="submit">Simpan
                                                            </button>
                                                            <button type="button" class="btn me-auto close-tab"
                                                                data-target="#tab-edit-{{ $city->id }}"
                                                                data-toggle-target="#tab-edit-button-{{ $city->id }}">
                                                                Tutup
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($cities->hasPages())
                        <!-- Pagination -->
                        <x-slot:pagination>
                            {{ $cities->links() }}
                            </x-slot>
                            @endif
                    </x-dt.datatable>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card-body ">
                        <!-- Admin panel -->
                        <ul class="nav nav-pills gap-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-add" class="btn active" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    <x-lucide-badge-plus class="icon text-indigo me-2" />
                                    Tambah Wilayah
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-add" class="tab-pane active show">
                                <form method="POST" action="{{ route('wilayah.store')}}" class="card-body border-top">
                                    @csrf
                                    <div class="card card-body mb-3">
                                        <div class="mb-4">
                                            <label for="name" class="form-label required">Nama Wilayah</label>
                                            <x-form.input name="name" type="text" placeholder="Tuliskan nama wilayah"
                                                required />
                                        </div>
                                        <div class="btn-list">
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.close-tab').forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = document.querySelector(this.getAttribute('data-target'));
            const targetTabButton = document.querySelector(this.getAttribute('data-toggle-target'));

            if (targetTab) {
                targetTab.classList.remove('active', 'show');
            }
            if (targetTabButton) {
                targetTabButton.classList.remove('active');
            }
            const allTabs = document.querySelectorAll('.tab-pane-edit');
            allTabs.forEach(tab => tab.classList.remove('active', 'show'));
        });
    });
</script>

<x-modal-delete baseUrl="{{ url('/wilayah') }}" />
@endsection