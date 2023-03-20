@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-list-deals.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Deal Management
@endsection


@section('title')
All Deals
@endsection


@section('content')

<div class="row">
    <div class="col-12">

      <!-- Total Deals Card -->
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col">

              <!-- Title -->
              <h6 class="text-uppercase text-muted mb-2">
                Total Deals
              </h6>

              <!-- Heading -->
              <span class="h2 mb-0 deals-total"></span>

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
        Deals
      </h4>

      <!-- Dropdown -->
      <div class="dropdown">
        <a href="/deals/create" class="btn btn-sm btn-primary">
          Create deal
        </a>
      </div>

    </div>
    <div class="card-body">

      <!-- List group -->
      <div class="list-group list-group-flush my-n3">
        <div class="list-group-item deals-list">
        </div>
      </div>

    </div>
  </div>

@endsection
