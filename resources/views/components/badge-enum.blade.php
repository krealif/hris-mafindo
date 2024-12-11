@props([
    'case' => null,
    'enumClass' => null,
])

@php
  if ($enumClass && $case) {
      $statusEnum = $enumClass::tryFrom($case);
      $badgeClass = $statusEnum ? $statusEnum->badge() : 'bg-blue-lt';
      $statusLabel = $statusEnum ? $statusEnum->label() : '';
  } else {
      $badgeClass = 'bg-blue-lt';
      $statusLabel = '';
  }
@endphp

<span {{ $attributes->class(['badge text-white', $badgeClass]) }}>{{ $statusLabel }}</span>
