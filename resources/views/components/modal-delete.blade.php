@props([
    'baseRoute' => null,
])

<div id="modal-delete" class="modal fade" tabindex="-1" aria-hidden="true" x-data="{ deleteId: null }" x-on:set-id.window="deleteId = $event.detail.id">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-danger"></div>
      <div class="modal-body py-4">
        <x-lucide-octagon-alert class="icon mb-2 text-danger icon-lg" />
        <h3>Konfirmasi Penghapusan</h3>
        <div>Apakah Anda yakin ingin menghapus data ini? Data yang dihapus akan <b>hilang secara permanen dan tidak dapat dikembalikan.</b></div>
      </div>
      <div class="modal-footer">
        <div class="hstack gap-2 w-100">
          <form id="form-delete" class="w-100" method="POST" x-bind:action="`{{ $baseRoute }}/${deleteId}`">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">Hapus</button>
          </form>
          <button type="button" class="btn me-auto w-100" data-bs-dismiss="modal">Batal</button>
        </div>
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
