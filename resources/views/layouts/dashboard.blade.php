@extends('layouts.base')

@section('body')
<div class="d-flex flex-column">
  <header>
    @include('components.navbar-top')
    @include('components.navbar-menu')
  </header>
  @yield('content')
</div>
@endsection
