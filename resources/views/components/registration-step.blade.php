@props(['steps' => null, 'current' => null])

<div class="card-body p-0">
  <div class="stepper-wrap">
    <ol class="stepper">
      @foreach ($steps as $case => $name)
        <li @class([
            'stepper-item',
            'active' => $case == $current || (empty($current) && $loop->first),
        ])>
          {{ $name }}
        </li>
      @endforeach
    </ol>  
  </div>
</div>