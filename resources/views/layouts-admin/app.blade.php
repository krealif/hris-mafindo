
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('pageTitle')</title>
    <!-- CSS files -->
    <link href="./tabler/dist/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="./tabler/dist/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="./tabler/dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="./tabler/dist/css/demo.min.css?1692870487" rel="stylesheet"/>
    <link href="./tabler/dist/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    
  </head>
  <body  class=" d-flex flex-column">
    <script src="./tabler/dist/js/demo-theme.min.js?1692870487"></script>
    
    @include('layouts-admin.header')

    @yield('content')
    
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./tabler/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="{{asset('js/copylink.js')}}"></script>
    <script src="./tabler/dist/js/demo.min.js?1692870487" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  </body>
</html>