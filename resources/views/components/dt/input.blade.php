@props([
  'value' => null
])

<x-form.input {{ $attributes }} :showError=false value="{{ request()->filter[$attributes->get('name')] ?? '' }}" />
