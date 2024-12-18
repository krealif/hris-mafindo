@extends('layouts.base')

@section('body')
<div class="d-flex flex-column">
  <header>
    @include('partials.navbar-top')
  </header>
  @yield('content')
</div>
@endsection
