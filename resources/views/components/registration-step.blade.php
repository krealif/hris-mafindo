@props(['data' => null, 'step' => null])

<ol class="stepper fw-bold">
  @foreach ($data as $value => $label)
    <li @class(['stepper-item', 'active' => $value == $step])>
      {{ $label }}
    </li>
  @endforeach
</ol>
