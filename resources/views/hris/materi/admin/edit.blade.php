@extends('layouts.dashboard', [
'title' => 'Materi',
])

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-1">
                        <x-breadcrumb>
                            <x-breadcrumb-item label="Materi" route="materi.index" />
                        </x-breadcrumb>
                    </div>
                    <h1 class="page-title">
                        Edit Materi
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="col-12 col-md-10 col-lg-8">
            <form class="card card-mafindo" action="{{ route('materi.update', $materi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h2 class="card-title d-flex align-items-center gap-2">
                        <x-lucide-book-text class="icon" />
                        Materi
                    </h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Judul Materi</label>
                        <x-form.input name="title" type="text" value="{{ old('title', $materi->title) }}" />
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label required">Tautan Materi</label>
                        <x-form.input name="url" type="text" value="{{ old('url', $materi->url) }}" />
                    </div>
                </div>
                <div class="card-body btn-list">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('materi.index') }}" class="btn">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection