@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-commissions.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Commissions
@endsection


@section('title')
Commissions
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <div class="header">
        <div class="header-body">
          <div class="row align-items-center mt-3">

            @include('pages.payments.filter-date')

            <div class="col"></div>

            @include('pages.commissions.filter')
            
          </div>
          <div>
            <button class="btn btn-primary commissions-export">Export</button>
          </div>
        </div>
      </div>

      <div class="card" id="commissionsList"></div>

    </div>
  </div>

@endsection