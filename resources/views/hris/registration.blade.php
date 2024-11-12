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
        <x-dtb.datatable searchField="name">
          <!-- Table filter -->
          <x-slot:filter>
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
                <x-dtb.select-searchable id="branch" name="branch_id" :options=$branches>
                  <x-slot:placeholder>
                    <option value="">Pilih wilayah</option>
                  </x-slot>
                </x-dtb.select-searchable>
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
                      <btn href="#" id="btn-approve" class="btn btn-md">
                        <svg class="icon text-success" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#check" /></svg>
                        Terima
                      </btn>
                      <btn href="#" class="btn btn-icon text-danger"  data-bs-toggle="modal" data-bs-target="#modal-approve">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#x" /></svg>
                      </btn>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <!-- Pagination -->
          <x-slot:pagination>
            {{ $users->links() }}
          </x-slot>
        </x-dtb.datatable>
      </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-approve" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <form class="modal-content" action="" method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Terima Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre class="bg-transparent text-dark fs-3 p-0" id="approve-summary"></pre>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" name="example-text-input" placeholder="Your report name" />
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-link" data-bs-dismiss="modal">
          Batal
        </a>
        <button class="btn btn-primary" type="submit">
          Terima
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
  const approveButtons = document.querySelectorAll('btn#btn-approve');
  const approveModal = document.querySelector('#modal-approve');
  const approveModalBs = new bootstrap.Modal(approveModal);

  approveButtons.forEach(button => {
    button.addEventListener('click', function () {
      const row = this.closest('tr');
      const cells = Array.from(row.cells);

      const id = row.getAttribute("data-id");
      const data = [];

      // Get all data
      cells.pop();
      cells.forEach(cell => {
          const content = cell.textContent.trim();
          if (content) {
              data.push(content);
          }
      })

      const form = approveModal.querySelector('form')
      const postURL =  window.location.href + `/${id}`;
      form.action = postURL;

      // Populate modal before show
      approveModal.querySelector('#approve-summary').textContent = data.join('\n');
      approveModalBs.show();
    });
  });

  // Clear model
  approveModal.addEventListener('hidden.bs.modal', () => {
    const form = approveModal.querySelector('form');
    form.reset();
    approveModal.querySelector('#approve-summary').textContent = ''
    form.action = '';
  });
</script>
@endpush
