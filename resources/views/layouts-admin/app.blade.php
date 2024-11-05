
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('pageTitle')</title>
    <!-- CSS files -->
    <link href="{{ asset('static/tabler/css/tabler.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    @include('layouts-admin.header')
    @yield('content')
    <!-- JS files -->
    <script src="{{ asset('static/tabler/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('static/js/copylink.js') }}"></script>
  </body>
</html>
