@props([
    'id' => null,
    'name' => null,
    'value' => null,
])

@php
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<div x-data="datefilter('{{ htmlspecialchars_decode($value) }}')" class="input-group">
  <select x-model="operator" class="form-select" style="max-width: 120px">
    <option value="">=</option>
    <option value="<">Sebelum</option>
    <option value=">">Setelah</option>
  </select>
  <input type="hidden" name="{{ $name }}" x-model="modifiedDate">
  <x-form.flatpickr id="updated_at" x-model="updated_at" x-model="date" :showIcon=false />
</div>

@once
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('datefilter', (value) => ({
        date: '',
        operator: '',
        modifiedDate: value || null,
        init() {
          const matches = /^(<=|>=|<|>|=)?(.+)$/.exec(value);
          this.operator = matches && matches[1] ? matches[1] : '';
          this.date = matches && matches[2] ? matches[2].trim() : '';

          this.$watch('date, operator', function(newDate) {
            this.modifiedDate = this.date ? `${this.operator}${this.date}` : null;
          }.bind(this));
        },
      }))
    })
  </script>
@endonce
