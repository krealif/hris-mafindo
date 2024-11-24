@props([
  'label' => 'label',
  'route' => null,
])

<li class="breadcrumb-item"><a href="{{ $route ? route($route) : '/' }}">{{ $label }}</a></li>
