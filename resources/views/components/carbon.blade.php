@props([
  'datetime' => null,
])

{{ \Carbon\Carbon::parse($datetime)->translatedFormat('d F Y | H:i')." WIB" }}
