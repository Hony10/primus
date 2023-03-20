@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-lock-months.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Admin
@endsection


@section('title')
Lock Months
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <input type="hidden" name="selected-year" />
      <div class="months-list container"></div>

    </div>
  </div>

  <!--Modals-->
  <div class="modal" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Lock Month</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to lock this month? This cannot be reversed.</p>
          <p style="font-size: 120%;">
            <span class="modal-month"></span>
            <span class="modal-year"></span>
          </p>
          <p>You will not be able to add or change any payments made within this month.</p>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="modal-date" value="" />
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary modal-confirm">Lock Month</button>
        </div>
      </div>
    </div>
  </div>

  <!--Modals-->

@endsection