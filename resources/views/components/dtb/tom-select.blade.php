@props([
  'selected' => null
])

<x-form.tom-select {{ $attributes }} :withError=false selected="{{ request()->filter[$attributes->get('name')] ?? '' }}">
  {{ $slot }}
</x-form.tom-select>
