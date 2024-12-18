@props(['data' => null, 'step' => null])

<ol class="stepper divide-x divide-y fw-bold">
  @foreach ($data as $value => $label)
    <li @class([
        'stepper-item',
        'active' => $value == $step || ($step == '' && $loop->first),
    ])>
      {{ $label }}
    </li>
  @endforeach
</ol>
