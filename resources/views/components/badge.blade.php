@props([
    'case' => null,
])

@php
  $badge = $case?->badge() ?? 'bg-gray text-dark';
  $text = $case?->label() ?? '-';
@endphp

<span {{ $attributes->class(['badge text-white text-uppercase', $badge]) }}>{{ $text }}</span>
