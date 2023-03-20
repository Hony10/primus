@extends('structure.dashboard-wide')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-end-of-month.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
End of Month
@endsection


@section('title')
<span class="end-of-month-title">
    @php
        echo strtoupper(date('M Y'));
    @endphp
</span>
@endsection


@section('content')

  <div class="row justify-content-center">
    <div class="col-12">

      <div class="header">
        <div class="header-body">
          <div class="row align-items-center mt-0">

            <div class="col-12 col-md-6 col-lg-3 me-3">
                <div class="form-group">
                    <label class="form-label">Month</label>
                    <select class="form-control form-select" name="filter-month" data-choices="{&quot;searchEnabled&quot;: false}">
                        @for ($date = strtotime(date('Y-m-01')); $date >= strtotime('2020-10-01'); $date = strtotime(date('Y-m-01', strtotime('-1 month', $date))))
                            <option value="@php
                                echo date('M Y', $date);
                            @endphp">
                                @php
                                    echo strtoupper(date('M Y', $date));
                                @endphp
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="col"></div>

          </div>
        </div>
      </div>

      <div class="card"><div class="card-body" id="endOfMonthDisplay" style="overflow-x: auto;"></div></div>

    </div>
  </div>

@endsection