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
    
    <!-- Title -->
    <title>Primus Dashboard</title>
  </head>
  <body class="d-flex align-items-center bg-auth border-top border-top-2 border-primary">

    <!-- CONTENT
    ================================================== -->
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6 my-5 align-self-center">

          <!-- Logo -->
          <img src="/assets/media/logo.png" class="d-block mx-auto mb-5" style="max-width: 8rem;" />

          <!-- Heading -->
          <h1 class="display-4 text-center mb-3">
            Sign in
          </h1>

          <!-- Subheading -->
          <p class="text-muted text-center mb-5">
            Primus Financial Solutions Ltd
          </p>

          <!-- Form -->
          <form>

            @csrf

            <!-- Email address -->
            <div class="form-group">

              <!-- Label -->
              <label class="form-label">
                Email Address
              </label>

              <!-- Input -->
              <input type="email" class="form-control" placeholder="name@address.com" name="username">

            </div>

            <!-- Password -->
            <div class="form-group">
              <div class="row">
                <div class="col">

                  <!-- Label -->
                  <label class="form-label">
                    Password
                  </label>

                </div>
                <div class="col-auto">

                </div>
              </div> <!-- / .row -->

              <!-- Input group -->
              <div class="input-group input-group-merge">

                <!-- Input -->
                <input class="form-control" type="password" placeholder="Enter your password" name="password">

                <!-- Icon -->
                <span class="input-group-text">
                  <i class="fe fe-eye password-toggle"></i>
                </span>

              </div>
            </div>

            <!-- Submit -->
            <button type="button" class="btn btn-lg w-100 btn-primary mb-3" name="sign-in-submit">
              Sign in
            </button>

            <!-- Errors -->
            <div class="alert alert-danger fade" role="alert" style="display: none;">
              <span class="fe fe-alert-triangle me-1"></span>
              <span class="alert-text">
                We couldn't sign you in, please check your username and password and try again.
              </span>
            </div>

          </form>

        </div>
        <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">

          <!-- Image -->
          <div class="bg-cover h-100 min-vh-100 mt-n1 me-n3" style="background-image: url(/assets/media/images/covers/fabian-blank-pElSkGRA2NU-unsplash.jpg);"></div>

        </div>
      </div> <!-- / .row -->
    </div>

    <!-- JAVASCRIPT -->
    
    <!-- Vendor JS -->
    <script src="/theme/assets/js/vendor.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>
    
    <!-- Theme JS -->
    <script src="/theme/assets/js/theme.bundle.js?v={{ config('app.version', '1.0.0') }}"></script>

    <!-- Page JS -->
    <script src="/assets/js/common.min.js?v={{ config('app.version', '1.0.0') }}"></script>
    <script src="/assets/js/page-sign-in.min.js?v={{ config('app.version', '1.0.0') }}"></script>


  </body>
</html>
