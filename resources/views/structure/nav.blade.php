    <!-- NAVIGATION -->
    <nav class="navbar navbar-vertical fixed-start navbar-expand-md navbar-light" id="sidebar">
        <div class="container-fluid">
      
          <!-- Toggler -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
      
          <!-- Brand -->
          <a class="navbar-brand" href="/">
            <img src="/assets/media/logo.png" class="navbar-brand-img mx-auto" alt="...">
          </a>
      
          <!-- User (xs) -->
          <div class="navbar-user d-md-none">
      
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" id="sidebarIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-sm avatar-online">
                  <i class="fe fe-user" style="font-size: 200%;"></i>
                </div>
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarIcon">
                <a href="#" class="dropdown-item sign-out">Logout</a>
                @csrf
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidebarCollapse">
            
            <!-- Navigation -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/">
                  <i class="fe fe-home"></i> Home
                </a>
              </li>
            </ul>

            <!-- Divider -->
            <hr class="navbar-divider my-3">

            <h6 class="navbar-heading">
              Primus
            </h6>

            @if (isset(request()->user) && ((strpos(request()->user->roles, 'Global Administrator') !== false) || (strpos(request()->user->roles, 'Agent') !== false)))

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/agent" class="nav-link"><i class="fe fe-trending-up"></i> Agent Balance</a>
              </li>
            </ul>

            @endif

            @if (isset(request()->user) && (strpos(request()->user->roles, 'Global Administrator') !== false))

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/payments" class="nav-link"><i class="fe fe-dollar-sign"></i> Enter Payments</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/statements" class="nav-link"><i class="fe fe-file-text"></i> Statements</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/end-of-month" class="nav-link"><i class="fe fe-calendar"></i> End of Month</a>
              </li>
            </ul>

            @endif

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/commissions" class="nav-link"><i class="fe fe-award"></i> Commissions</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/customers/create" class="nav-link"><i class="fe fe-user-plus"></i> Create Customer</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/customers" class="nav-link"><i class="fe fe-user"></i> Customers</a>
              </li>
            </ul>

            @if (isset(request()->user) && (strpos(request()->user->roles, 'Global Administrator') !== false))

            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="/deals" class="nav-link"><i class="fe fe-percent"></i> Deals</a>
              </li>
            </ul>

            @endif

            @if (isset(request()->user) && (strpos(request()->user->roles, 'Global Administrator') !== false))
      
            <!-- Divider -->
            <hr class="navbar-divider my-3">
      
            <!-- Heading -->
            <h6 class="navbar-heading">
              Administration
            </h6>
      
            <!-- Navigation -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/users"><i class="fe fe-users"></i> Users / Agents</a>
              </li>
            </ul>

            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/admin/lock-months"><i class="fe fe-calendar"></i> Lock Months</a>
              </li>
            </ul>

            @endif
      
            <!-- Push content down -->
            <div class="mt-auto"></div>
      
      
              <!-- User (md) -->
              <div class="navbar-user d-none d-md-flex" id="sidebarUser">

                <!-- Dropup -->
                <div class="dropup">
      
                  <!-- Toggle -->
                  <a href="#" id="sidebarIconCopy" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-sm avatar-online">
                      <i class="fe fe-user" style="font-size: 200%;"></i>
                    </div>
                  </a>
      
                  <!-- Menu -->
                  <div class="dropdown-menu" aria-labelledby="sidebarIconCopy">
                    <a href="#" class="dropdown-item sign-out">Logout</a>
                  </div>
      
                </div>
      
              </div>

              <div class="row">
                <div class="col-12 text-muted text-start">
                  v {{ config('app.version', '1.0.0') }}
                </div>
              </div>
      
          </div> <!-- / .navbar-collapse -->
      
        </div>
      </nav>
  