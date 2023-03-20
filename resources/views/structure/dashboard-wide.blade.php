<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
        
    <!-- Libs CSS -->
    <link rel="stylesheet" href="/theme/assets/css/libs.bundle.css?v={{ config('app.version', '1.0.0') }}" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="/theme/assets/css/theme.bundle.css?v={{ config('app.version', '1.0.0') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="/assets/css/common.min.css?v={{ config('app.version', '1.0.0') }}" />

    @yield('stylesheet')

    <!-- Title -->
    <title>Primus Dashboard</title>
  </head>
  <body>

    @include('structure.nav')

    <!-- MAIN CONTENT -->
    <div class="main-content">

      <!-- HEADER -->
      <div class="header mt-md-5">
        <div class="container-fluid">

          <!-- Body -->
          <div class="header-body">
            <div class="row align-items-end">
              <div class="col">

                <!-- Pretitle -->
                <h6 class="header-pretitle text-secondary">
                  @yield('pretitle')
                </h6>

                <!-- Title -->
                <h1 class="header-title">
                  @yield('title')
                </h1>

              </div>
            </div> <!-- / .row -->
          </div> <!-- / .header-body -->
        </div>
      </div> <!-- / .header -->

      <!-- CARDS -->
      <div class="container-fluid">
        @yield('content')
      </div>

    </div> <!-- / .main-content -->

    <!-- JAVASCRIPT -->
    
    <!-- Vendor JS -->
    <!--<script src="/theme/assets/js/vendor.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Theme JS -->
    <script src="/theme/assets/js/theme.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>

    <!-- Page JS -->
    <script src="/assets/js/common.min.js?v={{ config('app.version', '1.0.0') }}"></script>

    @yield('script')

  </body>
</html>
