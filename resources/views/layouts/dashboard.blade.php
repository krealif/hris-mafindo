@extends('layouts.base')

@section('body')
  <div class="d-flex flex-column">
    <header>
      @include('partials.navbar-top')
      @include('partials.navbar-menu')
    </header>
    @yield('content')
  </div>
@endsection
