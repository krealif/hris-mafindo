<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($title)? "$title - HRIS Mafindo" : "HRIS Mafindo" }}</title>
  <link rel="preconnect" href="https://rsms.me/">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <link href="{{ asset('static/vendor/tabler.min.css') }}" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
  @stack('style')
</head>
<body>
  @yield('body')

  <!-- Scripts -->
  <script src="{{ asset('static/vendor/tabler.min.js') }}" defer></script>
  @stack('script')
</body>
</html>
