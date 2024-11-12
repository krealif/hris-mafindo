<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($title)? "$title - HRIS Mafindo" : "HRIS Mafindo" }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link href="{{ asset('static/vendor/tabler.min.css') }}" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
  @stack('styles')
  <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}"></script>
</head>
<body>
  @yield('body')

  <!-- Scripts -->
  <script src="{{ asset('static/vendor/tabler.min.js') }}" defer></script>
  @stack('scripts')
  <!-- SVG sprites -->
  @stack('icons')
</body>
</html>
