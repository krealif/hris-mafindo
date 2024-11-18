@extends('layouts.dashboard', [
  'title' => 'Pendaftaran'
])

@section('content')
<div class="page-wrapper">
  <!-- Judul Halaman -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Pendaftaran
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        @if($errors->any())
          <!-- Validation error -->
          <x-alert class="alert-danger">
            <ul class="m-0" style="margin-left: -1rem">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </x-alert>
        @endif
        @if (flash()->message)
          <x-alert class="{{ flash()->class }}">
            <div>{{ flash()->message }}</div>
          </x-alert>
        @endif
        <x-dtb.datatable searchField="name">
          <x-slot:filter>
            <!-- Table filter -->
            <div class="row g-4">
              <div class="col-12 col-md-6 col-lg-4">
                <label for="email" class="form-label">Email</label>
                <x-dtb.input name="email" type="text" />
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <label for="member-number" class="form-label">Nomor Induk</label>
                <x-dtb.input name="member_number" type="text" />
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <label for="branch" class="form-label">Wilayah</label>
                <x-dtb.tom-select id="branch" name="branch_id" :options=$branches>
                  <option value="">Pilih wilayah</option>
                </x-dtb.tom-select>
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-striped datatable">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor Induk</th>
                <th>Wilayah</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr data-id="{{ $user->id }}">
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->member_number }}</td>
                  <td>{{ $user->branch?->name }}</td>
                  <td>
                    <div class="btn-list flex-nowrap">
                      <button type="button" href="#" id="btn-approve" class="btn btn-md">
                        <svg class="icon text-success" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#check" /></svg>
                        Terima
                      </button>
                      <button type="button" href="#" id="btn-reject" class="btn btn-icon text-danger">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#x" /></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @if ($users->hasPages())
            <!-- Pagination -->
            <x-slot:pagination>
              {{ $users->links() }}
            </x-slot>
          @endif
        </x-dtb.datatable>
      </div>
    </div>
</div>
<!-- Modal Approve -->
<div id="modal-approve" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <form class="modal-content" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Terima Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre class="fs-3" id="summary"></pre>
        <div>
          <label for="role" class="form-label">Role</label>
          <div class="btn-group w-100">
            <input type="radio" id="btn-radio-1" class="btn-check" name="role" value="relawan" checked>
            <label for="btn-radio-1" type="button" class="btn">Relawan</label>
            <input type="radio" id="btn-radio-2" class="btn-check" name="role" value="pengurus">
            <label for="btn-radio-2" type="button" class="btn">Pengurus</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="submit" class="btn btn-primary">
          Terima
        </button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Reject -->
<div id="modal-reject" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <form class="modal-content" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tolak Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre class="fs-3" id="summary"></pre>
        <div>
          <label for="role" class="form-label">Alasan</label>
          <x-form.input name="message" placeholder="Tuliskan alasan penolakan" :showError=false required />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="submit" class="btn btn-danger">
          Tolak
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('icons')
<svg class="d-none">
  <symbol id="check" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></symbol>
  <symbol id="x" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></symbol>
</svg>
@endpush

@push('scripts')
<script>
  function setupModal(btnSelector, modalSelector, action, summarySelector) {
    const buttons = document.querySelectorAll(btnSelector);
    const modal = document.querySelector(modalSelector);
    const modalInstance = new bootstrap.Modal(modal);

    buttons.forEach(button => {
      button.addEventListener('click', function () {
        const row = this.closest('tr');
        const cells = Array.from(row.cells);

        const id = row.getAttribute("data-id");
        const data = [];

        // Get all data except last cell
        cells.pop();
        cells.forEach(cell => {
          const content = cell.textContent.trim();
          if (content) {
            data.push(content);
          }
        });

        const form = modal.querySelector('form');

        let currentUrl = new URL(window.location.href);
        currentUrl.pathname = currentUrl.pathname.replace(/\/$/, '');
        currentUrl.pathname += `/${id}/${action}`;
        currentUrl.search = '';
        form.action = currentUrl.href;

        modal.querySelector(summarySelector).textContent = data.join('\n');
        modalInstance.show();
      });

      // Clear modal when hidden
      modal.addEventListener('hidden.bs.modal', () => {
        const form = modal.querySelector('form');
        form.reset();
        modal.querySelector(summarySelector).textContent = '';
        form.action = '';
      });
    });

  }

  setupModal('#btn-approve', '#modal-approve', 'approve', '#summary');
  setupModal('#btn-reject', '#modal-reject', 'reject', '#summary');
</script>
@endpush
