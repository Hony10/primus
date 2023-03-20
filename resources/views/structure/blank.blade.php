<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Primus financial solutions staff dashboard." />
    
    
    <!-- Libs CSS -->
    <link rel="stylesheet" href="/theme/assets/css/libs.bundle.css?v={{ config('app.version', '1.0.0') }}" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="/theme/assets/css/theme.bundle.css?v={{ config('app.version', '1.0.0') }}" />

    @yield('stylesheet')
    
    <!-- Title -->
    <title>Primus Dashboard</title>
  </head>
  <body class="d-flex">

    <!-- CONTENT
    ================================================== -->
    <div class="container-fluid">
      @yield('content')
    </div>

    <!-- JAVASCRIPT -->
    
    <!-- Vendor JS -->
    <script src="/theme/assets/js/vendor.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>
    
    <!-- Theme JS -->
    <script src="/theme/assets/js/theme.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>

    <!-- Page JS -->
    <script src="/assets/js/common.min.js?v={{ config('app.version', '1.0.0') }}"></script>

    @yield('script')

  </body>
</html>