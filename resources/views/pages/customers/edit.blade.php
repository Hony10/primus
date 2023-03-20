@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-create-customer.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Customers
@endsection


@section('title')
{{ $customer->name }}
@endsection


@section('content')

<form>

    @csrf

    <!-- Divider -->
    <div class="row">

      <h3>Customer Details</h3>

      <input type="hidden" name="slug" value="{{ $customer->slug }}" />

      <div class="col-12">
        <div class="form-group">
          <label class="form-label">Deal</label>
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dealDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Select Deal
            </button>
            <div class="dropdown-menu deal-dropdown-menu" aria-labelledby="dealDropdown">
              @foreach (DB::table('deals')->orderBy('name', 'asc')->get() as $deal)

                @if (!isset(request()->user))
                  @continue
                @endif

                @if (strpos(request()->user->roles, 'Global Administrator') !== false)
                  <a class="dropdown-item" href="#" data-deal-id="{{ $deal->id }}">
                    <div class="me-3 rounded-circle d-inline-block" style="background-color: {{ $deal->colour }}; min-height: 32px; min-width: 32px; overflow: hidden;"></div>
                    <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">{{ $deal->name }}</h4>
                  </a>
                  @continue
                @endif

                @foreach (json_decode($deal->assignments, true) as $assignmentKey => $assignment)
                  @if ((request()->user->id !== intval($assignmentKey)) || ($assignment <= 0))
                    @continue
                  @endif

                  <a class="dropdown-item" href="#" data-deal-id="{{ $deal->id }}">
                    <div class="me-3 rounded-circle d-inline-block" style="background-color: {{ $deal->colour }}; min-height: 32px; min-width: 32px; overflow: hidden;"></div>
                    <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">{{ $deal->name }}</h4>
                  </a>
                @endforeach

              @endforeach
            </div>
            <div>
              <input type="hidden" class="deal-loaded" value="{{ $customer->deal }}" />
              <div class="deal-selected-preview my-4">
                @php
                  $selectedDeal = DB::table('deals')->where('id', $customer->deal)->first();
                @endphp

                @if (!$customer->deal || ($customer->deal === 0))
                  <span class="text-danger">No Deal Selected</span>
                @elseif (!$selectedDeal)
                  <span class="alert alert-danger">
                    <i class="fe fe-alert-triangle me-3"></i>
                    Unknown Deal Selected
                  </span>
                @else
                  <div class="me-3 rounded-circle d-inline-block" style="background-color: {{ $selectedDeal->colour }}; min-height: 32px; min-width: 32px; overflow: hidden;"></div>
                  <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">{{ $selectedDeal->name }}</h4>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <!-- Name -->
        <div class="form-group">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" name="name" placeholder="John Smith" value="{{ $customer->name }}" />
        </div>
      </div>

      <div class="col-12">
        <div class="form-group">
          <label class="form-label">MK</label>
          <input type="text" class="form-control" name="mk" placeholder="MK" value="{{ $customer->mk }}" />
        </div>
      </div>

      <div class="col-12">
        <div class="form-group">
          <label class="form-label">Product</label>
          <div class="col-12">

            <div class="form-check">
              <input class="form-check-input" type="radio" name="product" value="MORTGAGE" id="product-mortgage" {{ $customer->product === 'MORTGAGE' ? ' checked="checked"' : '' }} disabled="disabled" />
              <label class="form-check-label" for="product-mortgage">
                Mortgage
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio" name="product" value="LIFE INSURANCE" id="product-life-insurance" {{ $customer->product === 'LIFE INSURANCE' ? ' checked="checked"' : '' }} disabled="disabled" />
              <label class="form-check-label" for="product-life-insurance">
                Life Insurance
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio" name="product" value="B AND C" id="product-b-and-c" {{ $customer->product === 'B AND C' ? ' checked="checked"' : '' }} disabled="disabled" />
              <label class="form-check-label" for="product-b-and-c">
                B & C
              </label>
            </div>

          </div>
        </div>
      </div>
    </div> <!-- / .row -->

    <!-- Divider -->
    <hr class="my-4">

    @include('pages.customers.mortgage')

    @include('pages.customers.life-insurance')

    @include('pages.customers.b-and-c')

    <!-- Button -->
    <div class="mt-5">
        <button type="button" class="btn btn-primary button-update-customer me-3">Update Customer Account</button>
        <a href="/customers" class="btn btn-danger button-cancel">Cancel</a>
    </div>

    <div class="py-5 create-customer-notifications"></div>

</form>

@endsection