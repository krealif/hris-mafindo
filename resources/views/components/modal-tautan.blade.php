<div id="modal-tautan" class="modal fade" tabindex="-1" aria-hidden="true" x-data="{ linkUrl: null }" x-on:set-link.window="linkUrl = $event.detail.url">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tautan Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <div class="input-group">
            <input type="text" class="form-control" id="linkInput" x-bind:value="linkUrl" readonly>
            <div class="input-group-append">
              <button class="btn btn-main" onclick="copyLink()">Salin Tautan</button>
            </div>
          </div>
          <small id="copyMessage" class="form-text text-success" style="display: none;">Tautan berhasil
            disalin!</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  function copyLink() {
    const linkInput = document.getElementById('linkInput');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(linkInput.value)
      .then(() => {
        const copyMessage = document.getElementById('copyMessage');
        copyMessage.style.display = 'block';
        setTimeout(() => {
          copyMessage.style.display = 'none';
        }, 2000);
      })
      .catch(err => console.error('Failed to copy link:', err));
  }

  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('#modal-tautan');
    if (window.Alpine) {
      modal.addEventListener('hidden.bs.modal', () => {
        const data = Alpine.$data(modal);
        data.linkUrl = null;
      });
    }
  });
</script>
