@props(['steps' => null, 'current' => null])

<ol class="stepper divide-x divide-y fw-bold">
  @foreach ($steps as $case => $name)
    <li @class([
        'stepper-item',
        'active' => $case == $current || (empty($current) && $loop->first),
    ])>
      {{ $name }}
    </li>
  @endforeach
</ol>
