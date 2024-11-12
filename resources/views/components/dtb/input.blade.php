@props([
  'value' => null
])

<x-form.input {{ $attributes }} :withError=false value="{{ request()->filter[$attributes->get('name')] ?? '' }}" />
