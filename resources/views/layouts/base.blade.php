<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($title) ? "$title - HRIS Mafindo" : 'HRIS Mafindo' }}</title>

  <link rel="icon" type="image/png" href="{{ asset('static/favicon/favicon-96x96.png') }}" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('static/favicon/favicon.svg') }}" />
  <link rel="shortcut icon" href="{{ asset('static/favicon/favicon.ico') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('static/favicon/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ asset('static/favicon/site.webmanifest') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="{{ asset('static/vendor/tabler.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  <script src="{{ asset('static/vendor/alpine.min.js') }}" defer></script>
  @stack('styles')
</head>

<body>
  @yield('body')

  <!-- Scripts -->
  <script src="{{ asset('static/vendor/tabler.min.js') }}"></script>
  <script src="{{ asset('static/vendor/alpine-mask.min.js') }}"></script>
  @stack('scripts')
  <!-- SVG sprites -->
  <svg hidden class="d-none">
    @stack('bladeicons')
  </svg>
</body>

</html>
