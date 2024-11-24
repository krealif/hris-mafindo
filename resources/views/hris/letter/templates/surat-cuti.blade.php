@php
  $letter = $letter ?? null;
@endphp

<div class="card-body">
  <div class="mb-3">
    <label for="alasan" class="form-label required">Alasan</label>
    <textarea class="form-control" id="alasan" name="alasan" rows="4" required>{{ $letter?->contents['alasan'] }}</textarea>
  </div>
  <div>
    <label for="tanggal" class="form-label required">Tanggal</label>
    <input type="date" class="form-control" id="tanggal" name="tanggal" required value="{{ $letter?->contents['tanggal'] }}">
  </div>
</div>
