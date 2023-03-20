{{-- <div class="col-12 col-md-5 col-lg-auto me-3 {{ strpos(request()->user->roles, 'Global Administrator') !== false ? '' : 'd-none' }}">
  <div class="form-group">
    <label class="form-label">Customers</label>
    <select class="form-control form-select" name="filter-customer-type" data-choices='{"searchEnabled": false}'>
      <option value="">&nbsp;</option>
      <option value="all">All &nbsp;</option>
      <option value="mine">Mine &nbsp;</option>
    </select>
  </div>
</div> --}}

<div class="col-12 col-md-5 col-lg-auto me-3">
  <div class="form-group">
    <label class="form-label">Product</label>
    <select class="form-control form-select" name="filter-product" data-choices='{"searchEnabled": false}'>
      <option value="">&nbsp;</option>
      <option value="ALL" selected="selected">All &nbsp;</option>
      <option value="MORTGAGE">Mortgage &nbsp;</option>
      <option value="LIFE INSURANCE">Life Insurance &nbsp;</option>
      <option value="B AND C">B &amp; C &nbsp;</option>
    </select>
  </div>
</div>

<div class="col-12 col-md-5 col-lg-auto me-3">
  <div class="form-group">
    <label class="form-label">Deal</label>
    <div class="dropdown">
      <div>
        <input type="hidden" value="*" name="filter-deal" />
        <div class="deal-selected-preview">
          <span class="text-muted me-2 d-inline-block">
            <h2 class="m-0"><span class="badge border text-dark" style="background-color: #fff;">
              All Deals
            </span></h2>
          </span>
          <button class="btn btn-rounded-circle btn-primary dropdown-toggle" type="button" id="dealDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
          <div class="dropdown-menu deal-dropdown-menu" aria-labelledby="dealDropdown">
            @if (isset(request()->user) && (strpos(request()->user->roles, 'Global Administrator') !== false))
              <a class="dropdown-item" href="#" data-deal-id="*">
                <div class="me-3 rounded-circle d-inline-block border" style="background-color: #fff; min-height: 32px; min-width: 32px; overflow: hidden;"></div>
                <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">All Deals</h4>
              </a>
            @endif

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
        </div>
      </div>
    </div>
  </div>
</div>