    <!-- begin::Life Insurance -->
    <div class="row product-form-life-insurance" style="{{ isset($customer) && $customer->product === 'LIFE INSURANCE' ? '' : 'display: none;' }}">

        <h3>Life Insurance</h3>

        @php
          // Get all life insurance lender products
          $lenderProductsObjects = DB::table('customers')->groupBy('life_lender_product')->orderBy('life_lender_product', 'asc')->select('life_lender_product')->get();
          $lenderProducts = [];
          foreach ($lenderProductsObjects as $lenderObject) {
            if ($lenderObject->life_lender_product === '') {
              continue;
            }
            $lenderProducts[] = $lenderObject->life_lender_product;
          }
        @endphp
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Lender</label>
            <select class="form-select mb-3" name="life-lender-product" data-choices='{"searchEnabled": true}' value="{{ isset($customer) ? $customer->life_lender_product : '' }}">
              <option value="">&nbsp;</option>

              @foreach ($lenderProducts as $lenderProduct)
                <option value="{{ $lenderProduct }}" {{ isset($customer) && $customer->life_lender_product === $lenderProduct ? 'selected' : ''}}>{{ $lenderProduct }}</option>
              @endforeach

              <option value="other">Other</option>
            </select>
          </div>
        </div>
  
        <div class="col-12 life-lender-other-container" style="display: none;">
          <div class="form-group">
            <label class="form-label">Other Lender Product</label>
            <input type="text" class="form-control" name="life-lender-other" value="" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-check-label">Type</label>
            <select class="form-control form-select" name="life-type" data-choices='{"searchEnabled": false}'>
                <option value="">&nbsp;</option>
                <option value="DECREASING" {{ isset($customer) && $customer->life_type === 'DECREASING' }}>DECREASING</option>
                <option value="DECREASING WITH CI" {{ isset($customer) && $customer->life_type === 'DECREASING WITH CI' }}>DECREASING WITH CI</option>
                <option value="LEVEL" {{ isset($customer) && $customer->life_type === 'LEVEL' }}>LEVEL</option>
                <option value="LEVEL WITH CI" {{ isset($customer) && $customer->life_type === 'LEVEL WITH CI' }}>LEVEL WITH CI</option>
                <option value="WHOLE LIFE" {{ isset($customer) && $customer->life_type === 'WHOLE LIFE' }}>WHOLE LIFE</option>
                <option value="FIB" {{ isset($customer) && $customer->life_type === 'FIB' }}>FIB</option>
                <option value="FIB WITH CI" {{ isset($customer) && $customer->life_type === 'FIB WITH CI' }}>FIB WITH CI</option>
            </select>
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-check-label">Single / Joint</label>
            <select class="form-control form-select" name="life-single-joint" data-choices='{"searchEnabled": false}'>
              <option value="SINGLE" {{ isset($customer) && $customer->life_single_joint === 'SINGLE' }}>SINGLE</option>
              <option value="JOINT" {{ isset($customer) && $customer->life_single_joint === 'JOINT' }}>JOINT</option>
            </select>
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">CI</label>
            <input type="text" class="form-control" name="life-ci" value="{{ isset($customer) ? $customer->life_ci : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Waiver</label>
            <input type="text" class="form-control" name="life-waiver" value="{{ isset($customer) ? $customer->life_waiver : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Life Cover</label>
            <input type="text" class="form-control" name="life-cover" value="{{ isset($customer) ? $customer->life_life_cover : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">CI Cover</label>
            <input type="text" class="form-control" name="life-ci-cover" value="{{ isset($customer) ? $customer->life_ci_cover : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Term</label>
            <input type="number" class="form-control" name="life-term" value="{{ isset($customer) ? $customer->life_term : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Application Date</label>
            <input type="date" class="form-control" name="life-application-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->life_application_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="life-start-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->life_start_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" name="life-end-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->life_end_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Premium</label>
            <input type="number" class="form-control" name="life-premium" value="{{ isset($customer) ? $customer->life_premium : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Lapsed Date</label>
            <input type="date" class="form-control" name="life-lapsed-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->life_lapsed_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Policy Number</label>
            <input type="text" class="form-control" name="life-policy-number" value="{{ isset($customer) ? $customer->life_policy_number : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Indem Comm</label>
            <input type="text" class="form-control" name="life-indem-comm" value="{{ isset($customer) ? $customer->life_indem_comm : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Indem Paid Date</label>
            <input type="text" class="form-control" name="life-indem-paid-date" value="{{ isset($customer) ? $customer->life_indem_paid_date : '' }}" />
          </div>
        </div>

        <div class="col-12">
          <div class="alert alert-info">
            All deals last 48 months, at 40 months you will get notifications to renew the deal
          </div>
        </div>
  
      </div>
      <!-- end::Life Insurance -->
  