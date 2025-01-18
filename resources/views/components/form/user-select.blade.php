@props([
    'id' => null,
    'name' => null,
    'max' => 10,
    'selected' => [],
    'apiRoute' => null,
])

@php
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $apiRoute = $apiRoute ?? route('userApi.getAll');
@endphp

<x-form.tom-select id="{{ $id }}" name="{{ $name }}" {{ $attributes }}>
  <x-slot:script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const options = {
          valueField: 'id',
          labelField: 'nama',
          searchField: 'nama',
          onItemAdd: function() {
            this.setTextboxValue('');
          },
          load: function(query, callback) {
            if (query.length < 3) return callback([]);

            fetch(@js($apiRoute) + '?q=' + encodeURIComponent(query))
              .then(response => response.json())
              .then(data => callback(data.data || []))
              .catch(() => callback([]));
          }
        };

        @if ($attributes['multiple'])
          options['maxItems'] = {{ $max }};
          options['plugins'] = ['remove_button'];
        @endif

        if (window.TomSelect) {
          const selectInput = new TomSelect(@js('#' . $id), options);
          @if ($selected)
            const selected = @json($selected);
            selectInput.addOptions(selected);
            selected.map(recipient => {
              selectInput.addItem(recipient.id);
            });
          @endif
        }
      })
    </script>
  </x-slot>
</x-form.tom-select>
