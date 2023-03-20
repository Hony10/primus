@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-users.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Users
@endsection


@section('title')
All Users / Agents
@endsection


@section('content')

<div class="row">
    <div class="col-12 col-md-6">

      <!-- Total Users Card -->
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">

              <!-- Title -->
              <h6 class="text-uppercase text-muted mb-2">
                Total Users
              </h6>

              <!-- Heading -->
              <span class="h2 mb-0 users-total"></span>

            </div>
            <div class="col-auto">

            </div>
          </div> <!-- / .row -->

        </div>
      </div>

    </div>
    <div class="col-12 col-md-6">

      <!-- Total Agents Card -->
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">

              <!-- Title -->
              <h6 class="text-uppercase text-muted mb-2">
                Total Agents
              </h6>

              <!-- Heading -->
              <span class="h2 mb-0 users-total-agents"></span>

            </div>
            <div class="col-auto">

            </div>
          </div> <!-- / .row -->

        </div>
      </div>

    </div>
</div>

<div class="card">
    <div class="card-header">

      <!-- Title -->
      <h4 class="card-header-title">
        Users
      </h4>

      <!-- Dropdown -->
      <div class="dropdown">
        <a href="/users/create" class="btn btn-sm btn-primary">
          Create user
        </a>
      </div>

    </div>
    <div class="card-body">

      <!-- List group -->
      <div class="list-group list-group-flush my-n3">
        <div class="list-group-item users-list">
        </div>
      </div>

    </div>
  </div>

@endsection
