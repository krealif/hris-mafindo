<div class="card-body">
  <div class="mb-3">
    <label for="text" class="form-label required">Text</label>
    <textarea class="form-control" id="text" name="text" rows="4" required>{{ $letter?->content?->text }}</textarea>
  </div>
</div>
