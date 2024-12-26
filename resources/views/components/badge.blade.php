@props([
    'case' => null,
])

@php
  $badge = $case?->badge() ?? 'bg-blue-lt';
  $text = $case?->label() ?? 'Badge';
@endphp

<span {{ $attributes->class(['badge text-white text-uppercase', $badge]) }}>{{ $text }}</span>
