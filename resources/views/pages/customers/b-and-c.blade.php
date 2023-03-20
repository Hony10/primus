    <!-- begin::B and C -->
    <div class="row product-b-and-c" style="{{ isset($customer) && $customer->product === 'B AND C' ? '' : 'display: none;' }}">

        <h3>B &amp; C</h3>

        @php
          // Get all B & C lender products
          $lenderProductsObjects = DB::table('customers')->groupBy('bandc_provider')->orderBy('bandc_provider', 'asc')->select('bandc_provider')->get();
          $lenderProducts = [];
          foreach ($lenderProductsObjects as $lenderObject) {
            if ($lenderObject->bandc_provider === '') {
              continue;
            }
            $lenderProducts[] = $lenderObject->bandc_provider;
          }
        @endphp


        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Provider</label>
            <select class="form-select mb-3" name="bandc-provider" data-choices='{"addItems": true, "searchEnabled": true}' value="{{ isset($customer) ? $customer->bandc_provider : '' }}">
              <option value="">&nbsp;</option>

              @foreach ($lenderProducts as $lenderProduct)
                <option value="{{ $lenderProduct }}" {{ isset($customer) && $customer->bandc_provider === $lenderProduct ? 'selected' : ''}}>{{ $lenderProduct }}</option>
              @endforeach

              <option value="other">Other</opion>
            </select>
          </div>
        </div>
  
        <div class="col-12 bandc-provider-other-container" style="display: none;">
          <div class="form-group">
            <label class="form-label">Other Provider</label>
            <input type="text" class="form-control" name="bandc-provider-other" value="" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Property Postcode</label>
            <input type="text" class="form-control" name="bandc-postcode" value="{{ isset($customer) ? $customer->bandc_property_postcode : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Policy Number</label>
            <input type="text" class="form-control" name="bandc-policy-number" value="{{ isset($customer) ? $customer->bandc_policy_number : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">RESI / LET</label>
            <select class="form-control form-select" name="bandc-resi-let" data-choices='{"searchEnabled": false}'>
              <option value="RESI" {{ isset($customer) && $customer->bandc_resit_let === 'RESI' ? 'selected' : '' }}>RESI</option>
              <option value="LET" {{ isset($customer) && $customer->bandc_resit_let === 'LET' ? 'selected' : '' }}>LET</option>
            </select>
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Current</label>
            <input type="text" class="form-control" name="bandc-current" value="{{ isset($customer) ? $customer->bandc_current : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">DD / ANN</label>
            <select class="form-control form-select" name="bandc-dd-ann" data-choices='{"searchEnabled": false}'>
              <option value="DD" {{ isset($customer) && $customer->bandc_dd_ann === 'DD' ? 'selected' : '' }}>DD</option>
              <option value="ANN" {{ isset($customer) && $customer->bandc_dd_ann === 'ANN' ? 'selected' : '' }}>ANN</option>
            </select>
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="bandc-start-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->bandc_start_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Taken Up</label>
            <select class="form-control" name="bandc-taken-up" value="{{ isset($customer) ? $customer->bandc_taken_up : '' }}">
              <option value=""></option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <div class="col-12">
          <div class="alert alert-info">
            Every deal is 12 months. After 10 months you will be notified of the upcoming renewal.
          </div>
        </div>
  
      </div>
      <!-- end::B and C -->
  