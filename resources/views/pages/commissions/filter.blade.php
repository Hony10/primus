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
      <option value="ALL" selected>All &nbsp;</option>
      <option value="MORTGAGE">Mortgage &nbsp;</option>
      <option value="LIFE INSURANCE">Life Insurance &nbsp;</option>
      <option value="B AND C">B &amp; C &nbsp;</option>
    </select>
  </div>
</div>

<div class="col-12 col-md-5 col-lg-auto me-3">
  <div class="form-group">
    <label class="form-label">Agent</label>
    <div class="dropdown">
      <div>
        <input type="hidden" value="" name="filter-agent" />
        <div class="agent-selected-preview">
          <span class="text-muted me-2 d-inline-block">No Agent Selected</span>
          <button class="btn btn-rounded-circle btn-primary dropdown-toggle" type="button" id="agentDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
          <div class="dropdown-menu agent-dropdown-menu" aria-labelledby="agentDropdown">
            @foreach (DB::table('users')->where('roles', 'LIKE', '%Agent%')->orderBy('first_name', 'asc')->get() as $user)

              @if (!isset(request()->user))
                @continue
              @endif

              @if (strpos(request()->user->roles, 'Global Administrator') !== false)
                <a class="dropdown-item" href="#" data-agent-id="{{ $user->id }}">
                  <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">{{ trim($user->first_name . ' ' . $user->last_name) }}</h4>
                </a>
                @continue
              @endif

              @if (request()->user->id === $user->id)
                <a class="dropdown-item" href="#" data-agent-id="{{ $user->id }}">
                  <h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">{{ trim($user->first_name . ' ' . $user->last_name) }}</h4>
                </a>
                @continue
              @endif

            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>