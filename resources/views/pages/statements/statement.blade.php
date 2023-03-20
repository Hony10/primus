@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-statements.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Statements
@endsection


@section('title')
Statements
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <div class="sticky-top card">
        <div class="card-body">
          <div class="row align-items-center mt-3">

            @include('pages.payments.filter-date')

            <div class="col"></div>

            @include('pages.payments.filter')
            
          </div>
        </div>
      </div>

      <div class="payments-list"></div>

    </div>
  </div>

@endsection