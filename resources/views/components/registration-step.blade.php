@php
  use App\Enums\RegistrationStepEnum;
@endphp

<ol class="stepper fw-bold">
  @foreach (RegistrationStepEnum::labels() as $value => $label)
    <li @class(['stepper-item', 'active' => $value == $step])>
      {{ $label }}
    </li>
  @endforeach
</ol>
