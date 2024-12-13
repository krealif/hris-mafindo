@props([
    'route' => null,
])

<div id="modal-delete" class="modal fade" tabindex="-1" aria-hidden="true" x-data="{ deleteId: null }" x-on:set-id.window="deleteId = $event.detail.id">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div>Jika Anda melanjutkan</div>
      </div>
      <div class="modal-footer">
        <form id="form-delete" method="POST" x-bind:action="`{{ $route }}/${deleteId}`">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
<script>
  const modal = document.querySelector('#modal-delete');
  document.addEventListener("DOMContentLoaded", function() {
    if (window.Alpine) {
      modal.addEventListener('hidden.bs.modal', () => {
        const data = Alpine.$data(modal);
        data.deleteId = null;
      });
    }
  });
</script>
