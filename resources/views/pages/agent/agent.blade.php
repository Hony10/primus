@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-agent.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Agents
@endsection


@section('title')
Agent Area
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <div class="header">
        <div class="header-body">
          <div class="row align-items-center mt-3">

            @include('pages.payments.filter-date')

            <div class="col"></div>

            @include('pages.agent.filter')
            
          </div>
          <div>
            <button class="btn btn-primary activity-export">Export</button>
          </div>
        </div>
      </div>

      <div class="card" id="agentActivityList"></div>

    </div>
  </div>

  
  <!--Modals-->
  <div class="modal" id="addCost" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Cost</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Add a cost for this agent</p>
          <p style="font-size: 120%;">
            <span class="modal-month"></span>
            <span class="modal-year"></span>
          </p>

          <div class="form-group">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="add-cost-date" value="{{ date('Y-m-d') }}" />
          </div>

          <div class="form-group">
            <label class="form-label">Value</label>
            <input type="number" class="form-control" name="add-cost-value" value="0.00" step="0.01" />
          </div>

          <div class="form-group">
            <label class="form-label">Details</label>
            <input type="text" class="form-control" name="add-cost-details" />
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary modal-add-cost-confirm">Add Cost</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="addCommissionDrawdown" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Cost</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Add a cost for this agent</p>
          <p style="font-size: 120%;">
            <span class="modal-month"></span>
            <span class="modal-year"></span>
          </p>

          <div class="form-group">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="add-commission-date" value="{{ date('Y-m-d') }}" />
          </div>

          <div class="form-group">
            <label class="form-label">Value</label>
            <input type="number" class="form-control" name="add-commission-value" value="0.00" step="0.01" />
          </div>

          <div class="form-group">
            <label class="form-label">Details</label>
            <input type="text" class="form-control" name="add-commission-details" />
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary modal-add-commission-confirm">Add Commissions</button>
        </div>
      </div>
    </div>
  </div>


@endsection