@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-entry.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Payments
@endsection


@section('title')
Entry
@endsection


@section('content')

  <style>
    thead th { position: sticky !important; top: 0 !important; }
    #paymentsList { overflow-x: scroll; max-height: calc( 100vh - 28rem ); min-height: 30rem; }
  </style>

  <div class="row justify-content-center">
    <div class="col-12">

      <div class="header">
        <div class="header-body">
          <div class="row align-items-center mt-3">

            @include('pages.payments.filter-date')

            <div class="col"></div>

            @include('pages.payments.filter')

            <div class="input-group input-group-lg input-group-merge input-group-reverse">
              <input class="form-control" type="search" placeholder="Search" name="filter-search">
              <span class="input-group-text">
                <i class="fe fe-search"></i>
              </span>
            </div>
            
          </div>
        </div>
      </div>

      <div class="card" id="paymentsList"></div>

    </div>
  </div>

@endsection