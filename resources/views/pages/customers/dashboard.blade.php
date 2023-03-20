@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-customer-dashboard.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Customer Management
@endsection


@section('title')
All Customers
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <!-- Header -->
      <div class="header">
        <div class="header-body">
          <div class="row align-items-center">
            <div class="col">

              <!-- Navigation (button group) -->
              <div class="nav btn-group d-inline-flex" role="tablist">
                <button class="btn btn-white active" id="contactsListTab" data-bs-toggle="tab" data-bs-target="#contactsListPane" role="tab" aria-controls="contactsListPane" aria-selected="true">
                  <span class="fe fe-list"></span>
                </button>
                <button class="btn btn-white" id="contactsCardsTab" data-bs-toggle="tab" data-bs-target="#contactsCardsPane" role="tab" aria-controls="contactsCardsPane" aria-selected="false">
                  <span class="fe fe-grid"></span>
                </button>
              </div> <!-- / .nav -->

              <!-- Buttons -->
              <a href="/customers/create" class="btn btn-primary ms-2">
                Create account
              </a>

            </div>
          </div>
            <!-- Header -->
            <div class="row align-items-center mt-3">
              <div class="col-12">

                <!-- Form -->
                <div class="input-group input-group-lg input-group-merge input-group-reverse">
                  <input class="form-control list-search" type="search" placeholder="Search">
                  <span class="input-group-text">
                    <i class="fe fe-search"></i>
                  </span>
                </div>

              </div>
              <div class="col">
              </div>

              <div class="col-auto text-muted text-end my-3">
                Deal
              </div>
              <div class="col-auto me-n3">
                <form>

                  <select class="form-select form-select-sm form-control-flush filter-deal">
                    <option value="">All Deals</option>
                    @foreach (DB::table('deals')->orderBy('name', 'asc')->get() as $deal)
                      <option value="{{ $deal->id }}">{{ $deal->name}}</option>
                    @endforeach
                  </select>
                </form>
              </div>

              <div class="col-auto text-muted text-end my-3">
                Product
              </div>
              <div class="col-auto me-n3">
                <form>
                  <select class="form-select form-select-sm form-control-flush filter-products">
                    <option value="ALL" selected="selected">All &nbsp;</option>
                    <option value="MORTGAGE">Mortgage &nbsp;</option>
                    <option value="LIFE INSURANCE">Life Insurance &nbsp;</option>
                    <option value="B AND C">B &amp; C &nbsp;</option>
                </select>
                </form>
              </div>

              <div class="col-auto text-muted text-end my-3">
                Results per page
              </div>
              <div class="col-auto me-n3">

                <!-- Select -->
                <form>
                  <select class="form-select form-select-sm form-control-flush filter-results-per-page">
                    <option value="5">5 &nbsp;</option>
                    <option value="10" selected>10 &nbsp;</option>
                    <option value="25">25 &nbsp;</option>
                    <option value="50">50 &nbsp;</option>
                    <option value="100">100 &nbsp;</option>
                  </select>
                </form>

              </div>
              <div class="col-auto d-none">

                <!-- Dropdown -->
                <div class="dropdown">

                  <!-- Toggle -->
                  <button class="btn btn-sm btn-white" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fe fe-sliders me-1"></i> Filter <span class="badge bg-primary ms-1 d-none">0</span>
                  </button>

                  <!-- Menu -->
                  <form class="dropdown-menu dropdown-menu-end dropdown-menu-card">
                    <div class="card-header">

                      <!-- Title -->
                      <h4 class="card-header-title">
                        Filters
                      </h4>

                      <!-- Link -->
                      <button class="btn btn-sm btn-link text-reset d-none" type="reset">
                        <small>Clear filters</small>
                      </button>

                    </div>
                    <div class="card-body">

                      <!-- List group -->
                      <div class="list-group list-group-flush mt-n4 mb-4">
                        <div class="list-group-item">
                          <div class="row">
                            <div class="col">

                              <!-- Text -->
                              <small>Product</small>

                            </div>
                            <div class="col-auto">

                              <!-- Select -->
                              <select class="form-select form-select-sm" name="filter-product">
                                <option value="*" selected>Any &nbsp;</option>
                                <option value="LIFE INSURANCE">Life Insurance &nbsp;</option>
                                <option value="MORTGAGE">Mortgage &nbsp;</option>
                                <option value="B AND C">B and C &nbsp;</option>
                              </select>

                            </div>
                          </div> <!-- / .row -->
                        </div>
                        <div class="list-group-item">
                          <div class="row">
                            <div class="col">

                              <!-- Text -->
                              <small>Deal</small>

                            </div>
                            <div class="col-auto">

                              <!-- Select -->
                              <select class="form-select form-select-sm" name="filter-deal">
                                <option value="*" selected>Any &nbsp;</option>
                              </select>

                            </div>
                          </div> <!-- / .row -->
                        </div>
                        <div class="list-group-item">
                          <div class="row">
                            <div class="col">

                              <!-- Text -->
                              <small>Provider</small>

                            </div>
                            <div class="col-auto">

                              <!-- Select -->
                              <select class="form-select form-select-sm" name="filter-lender">
                                <option value="*" selected>Any &nbsp;</option>
                              </select>

                            </div>
                          </div> <!-- / .row -->
                        </div>
                      </div>

                      <!-- Button -->
                      <button class="btn w-100 btn-primary" type="submit">
                        Apply filter
                      </button>

                    </div>
                  </form>

                </div>

              </div>
            </div> <!-- / .row -->
        </div>
      </div>

      <!-- Tab content -->
      <div class="tab-content">
        <div class="tab-pane fade show active" id="contactsListPane" role="tabpanel" aria-labelledby="contactsListTab">

          <!-- Card -->
          <div class="card" id="contactsList">
            <div class="table-responsive">
              <table class="table table-sm table-hover table-nowrap card-table">
                <thead>
                  <tr>
                    <th>
                      <a class="list-sort text-muted" data-sort="item-name" href="#">Name</a>
                    </th>
                    <th>
                      <a class="list-sort text-muted" data-sort="item-title" href="#">Product</a>
                    </th>
                    <th>
                      <a class="list-sort text-muted" data-sort="item-email" href="#">Deal</a>
                    </th>
                    <th>
                      <a class="list-sort text-muted" data-sort="item-phone" href="#">Type</a>
                    </th>
                    <th>
                      <a class="list-sort text-muted" data-sort="item-score" href="#">Term</a>
                    </th>
                    <th colspan="2">
                      <a class="list-sort text-muted" data-sort="item-company" href="#">Provider</a>
                    </th>
                  </tr>
                </thead>
                <tbody class="font-size-base">
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">

              <!-- Pagination (prev) -->
              <ul class="list-pagination-prev pagination pagination-tabs card-pagination">
                <li class="page-item">
                  <a class="page-link ps-0 pe-4 border-end" href="#">
                    <i class="fe fe-arrow-left me-1"></i> Prev
                  </a>
                </li>
              </ul>

              <!-- Pagination -->
              <ul class="list-pagination pagination pagination-tabs card-pagination"></ul>

              <!-- Pagination (next) -->
              <ul class="list-pagination-next pagination pagination-tabs card-pagination">
                <li class="page-item">
                  <a class="page-link ps-4 pe-0 border-start" href="#">
                    Next <i class="fe fe-arrow-right ms-1"></i>
                  </a>
                </li>
              </ul>

              <!-- Alert -->
              <div class="list-alert alert alert-dark alert-dismissible border fade" role="alert">

                <!-- Content -->
                <div class="row align-items-center">
                  <div class="col">

                    <!-- Checkbox -->
                    <div class="form-check">
                      <input class="form-check-input" id="listAlertCheckbox" type="checkbox" checked disabled>
                      <label class="form-check-label text-white" for="listAlertCheckbox">
                        <span class="list-alert-count">0</span> deal(s)
                      </label>
                    </div>

                  </div>
                  <div class="col-auto me-n3">

                    <!-- Button -->
                    <button class="btn btn-sm btn-white-20">
                      Edit
                    </button>

                    <!-- Button -->
                    <button class="btn btn-sm btn-white-20">
                      Delete
                    </button>

                  </div>
                </div> <!-- / .row -->

                <!-- Close -->
                <button type="button" class="list-alert-close btn-close" aria-label="Close"></button>

              </div>

            </div>
          </div>

        </div>
        <div class="tab-pane fade" id="contactsCardsPane" role="tabpanel" aria-labelledby="contactsCardsTab">

          <!-- Cards -->
          <div id="contactsCards">

            <!-- Body -->
            <div class="list row">
            </div>

            <!-- Pagination -->
            <div class="row g-0">

              <!-- Pagination (prev) -->
              <ul class="col list-pagination-prev pagination pagination-tabs justify-content-start">
                <li class="page-item">
                  <a class="page-link" href="#">
                    <i class="fe fe-arrow-left me-1"></i> Prev
                  </a>
                </li>
              </ul>

              <!-- Pagination -->
              <ul class="col list-pagination pagination pagination-tabs justify-content-center"></ul>

              <!-- Pagination (next) -->
              <ul class="col list-pagination-next pagination pagination-tabs justify-content-end">
                <li class="page-item">
                  <a class="page-link" href="#">
                    Next <i class="fe fe-arrow-right ms-1"></i>
                  </a>
                </li>
              </ul>

            </div>

            <!-- Alert -->
            <div class="list-alert alert alert-dark alert-dismissible border fade" role="alert">

              <!-- Content -->
              <div class="row align-items-center">
                <div class="col">

                  <!-- Checkbox -->
                  <div class="form-check">
                    <input class="form-check-input" id="cardAlertCheckbox" type="checkbox" checked disabled>
                    <label class="form-check-label text-white" for="cardAlertCheckbox">
                      <span class="list-alert-count">0</span> deal(s)
                    </label>
                  </div>

                </div>
                <div class="col-auto me-n3">

                  <!-- Button -->
                  <button class="btn btn-sm btn-white-20">
                    Edit
                  </button>

                  <!-- Button -->
                  <button class="btn btn-sm btn-white-20">
                    Delete
                  </button>

                </div>
              </div> <!-- / .row -->

              <!-- Close -->
              <button type="button" class="list-alert-close btn-close" aria-label="Close">
                
              </button>

            </div>

          </div>

        </div>
      </div>

    </div>
  </div> <!-- / .row -->

@endsection
