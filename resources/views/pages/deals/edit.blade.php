@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-edit-deal.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Deals
@endsection


@section('title')
{{ $deal }}
@endsection


@section('content')
<form>

    @csrf

    <input type="hidden" name="loaded-deal" value="{{ $id }}" />
    <input type="hidden" name="loaded-deal-slug" value="{{ $slug }}" />

    <div class="row">
        <div class="col-12">
          <!-- Name -->
          <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name">
          </div>
        </div>
  
        <div class="col-12">
          <!-- Colour -->
          <div class="form-group">
            <label class="form-label">Colour</label>
            <input type="hidden" name="colour" value="hsla(0,80%,50%,1)" />
            <div class="d-flex flex-wrap justify-content-center colour-selection-list"></div>
          </div>
        </div>
    </div> <!-- / .row -->
  
    <hr class="my-4">

    <div class="row">
      <h3 class="mb-5 mt-3">Deductions</h3>
    </div>

    <div class="row">
      <div class="col-12 col-md-6">
        <!-- Name -->
        <div class="form-group">
          <label class="form-label">JLM</label>
          <input type="number" class="form-control" name="deduction">
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="form-group">
          <label class="form-label">Ivan</label>
          <input type="number" class="form-control" name="deduction-ivan">
        </div>
      </div>
    </div>

    <!-- Divider -->
    <hr class="my-4">

    <div class="row">
        <h3 class="mb-5 mt-3">Agent Percentages</h3>
        <div class="agent-list my-3 col-12 col-md-10 col-lg-8 col-xl-6 mx-auto"></div>
    </div> <!-- / .row -->

    <!-- Button -->
    <div class="mt-5">
        <button type="button" class="btn btn-primary button-update-deal me-3">Update Deal</button>
        <a href="/deals" class="btn btn-danger">Cancel</a>
    </div>

    <div class="py-5 create-deal-notifications"></div>

</form>
@endsection
