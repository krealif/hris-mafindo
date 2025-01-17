@extends('layouts.dashboard', [
    'title' => "Edit {$event->name} | Kegiatan",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <div class="mb-1">
              @if (url()->previous() == route('kegiatan.show', $event->id))
                <a href="{{ url()->previous() }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              @else
                <a href="{{ route('kegiatan.index') }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              @endif
            </div>
            <h1 class="page-title">
              Edit Kegiatan
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div>
        @if ($errors->any())
          <x-alert class="alert-danger">
            <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
            <ul class="mt-2 mb-0" style="margin-left: -1rem">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </x-alert>
        @endif
      </div>
      <form method="POST" action="{{ route('kegiatan.update', $event->id) }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="col-6">
          <div class="card card-mafindo" x-data="@js(['eventType' => old('type', $event->type)])">
            <div class="card-body">
              <div class="mb-3">
                <label for="name" class="form-label required">Nama</label>
                <x-form.input name="name" type="text" value="{{ old('name', $event->name) }}" required />
              </div>
              <div class="mb-3">
                <label for="description" class="form-label required">Deskripsi</label>
                <x-form.trix-editor name="description" value="{!! old('description', $event->description) !!}" required />
              </div>
              <div class="mb-3">
                <label for="date-time" class="form-label required">Tanggal dan Waktu</label>
                <x-form.flatpickr name="start_date" value="{{ old('start_date', $event->start_date) }}" :disabled="$event->has_started">
                  <x-slot:script>
                    <script>
                      document.addEventListener("DOMContentLoaded", function() {
                        const options = {
                          locale: 'id',
                          altInput: true,
                          altFormat: "d/m/Y H:i",
                          dateFormat: "Y-m-d H:i:S",
                          enableTime: true,
                          time_24hr: true,
                        };

                        @if (!$event->has_started)
                          options['minDate'] = 'today';
                        @endif

                        if (window.flatpickr) {
                          flatpickr('#start-date', options);
                        }
                      });
                    </script>
                  </x-slot:script>
                </x-form.flatpickr>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="type" class="form-label required">Tipe</label>
                  <x-form.select name="type" x-model="eventType" :options="App\Enums\EventTypeEnum::labels()" required :disabled="$event->has_started" />
                </div>
                <div class="col" x-show="eventType == 'terbatas'">
                  <label for="quota" class="form-label required">Kuota</label>
                  <div class="input-group mb-2">
                    <x-form.input name="quota" type="number" min="1" value="{{ old('quota', $event->quota) }}" x-bind:required="eventType == 'terbatas'"
                      :disabled="$event->has_started" />
                    <span class="input-group-text">
                      peserta
                    </span>
                  </div>
                </div>
              </div>
              <div class="{{ $event->status->value == 'selesai' ? 'mb-3' : 'mb-2' }}">
                <label for="meeting_url" class="form-label">Link Meeting</label>
                <x-form.input name="meeting_url" type="text" value="{{ old('meeting_url', $event->meeting_url) }}" />
              </div>
              @if ($event->status->value == 'selesai')
                <div class="mb-2">
                  <label for="recording_url" class="form-label">Link Rekaman</label>
                  <x-form.input name="recording_url" type="text" value="{{ old('recording_url', $event->recording_url) }}" />
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card card-mafindo">
            <div class="card-body" x-data="imgPreview(@js($event->cover ? Storage::url($event->cover) : asset('static/img/event-cover-placeholder.png')))">
              <div class="mb-3">
                <label for="cover" class="form-label">Ganti Cover?</label>
                <x-form.input name="cover" type="file" x-ref="imgInput" x-on:change="handleFileUpload" accept=".jpg,.jpeg,.png" value="{{ old('cover') }}" />
              </div>
              <div class="mb-2">
                <div class="row g-3">
                  <div class="col-4">
                    <img class="ratio ratio-1x1 rounded" x-bind:src="newImg || img" />
                  </div>
                  <div class="col">
                    <div>
                      <p class="m-0">Pastikan foto yang Anda upload memenuhi ketentuan berikut:</p>
                      <ul class="mt-1">
                        <li>Dimensi: <strong>1000x1000 pixel</strong> atau memiliki <strong>rasio 1:1</strong></li>
                        <li>Ukuran File: <strong>Maksimal 2 MB</strong></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card bg-primary-lt">
            <div class="card-body btn-list">
              <button type="submit" class="btn btn-primary">Simpan</button>
              @if (url()->previous() == route('kegiatan.show', $event->id))
                <a href="{{ route('kegiatan.show', $event->id) }}" class="btn">Batal</a>
              @else
                <a href="{{ route('kegiatan.index') }}" class="btn">Batal</a>
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('imgPreview', (img) => ({
        img,
        newImg: null,
        cancelUpload() {
          this.newImg = null;
          this.$refs.imgInput.value = '';
        },
        handleFileUpload(event) {
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = () => {
              this.newImg = reader.result;
            };
            reader.readAsDataURL(file);
          }
        }
      }));
    })
  </script>
@endsection
