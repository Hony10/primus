      <!-- begin::Mortgage Form -->
      <div class="row product-form-mortgage" style="{{ isset($customer) && $customer->product === 'MORTGAGE' ? '' : 'display: none;' }}">

        <h3>Mortgage Details</h3>

        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Property</label>
            <input type="text" class="form-control" name="mortgage-property" value="{{ isset($customer) ? $customer->mortgage_property : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Type</label>
            <select class="form-control form-select" name="mortgage-type" data-choices='{"searchEnabled": false}'>
              <option value="">&nbsp;</option>

              @php
                $mortgageTypes = [
                  'Product Transfer - BTL',
                  'Product Transfer – Ltd Co. BTL',
                  'Product Transfer – Residential',
                  'Resi Purchase',
                  'BTL Purchase',
                  'Let to Buy',
                  'Ltd Company BTL Purchase',
                  'Homemover',
                  'Porting',
                  'Help to Buy',
                  'Right to Buy',
                  'Re-mortgage – Resi Like-for-Like',
                  'Re-mortgage – Resi Cap Raise',
                  'Re-mortgage – BTL Like-for-Like',
                  'Re-mortgage – BTL Cap Raise',
                  'Packager Referral',
                ];
              @endphp

              @foreach ($mortgageTypes as $mortgageType)
                <option value="{{ $mortgageType }}" {{ isset($customer) && $customer->mortgage_type === $mortgageType ? 'selected' : ''}}>{{ $mortgageType }}</option>
              @endforeach
              <option value="Other" {{ isset($customer) && !in_array($customer->mortgage_type, $mortgageTypes) ? 'selected' : ''}}>Other</option>

            </select>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Type (Other)</label>
            <input type="text" class="form-control" name="mortgage-type-other" value="{{ isset($customer) && !in_array($customer->mortgage_type, $mortgageTypes) ? $customer->mortgage_type : ''}}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Property Price</label>
            <div class="input-group input-group-merge input-group-reverse">
              <input type="number" class="form-control" name="mortgage-property-price" value="{{ isset($customer) ? $customer->mortgage_property_price : '' }}" />
              <div class="input-group-text">&pound;</div>
            </div>
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Mortgage Required</label>
            <div class="input-group input-group-merge input-group-reverse">
              <input type="number" class="form-control" name="mortgage-mortgage-required" value="{{ isset($customer) ? $customer->mortgage_mortgage_required : '' }}" />
              <div class="input-group-text">&pound;</div>
            </div>
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">LTV</label>
            <div class="input-group input-group-merge">
              <input type="text" class="form-control text-end" name="mortgage-ltv" disabled="disabled" value="{{ isset($customer) ? $customer->mortgage_ltv : '' }}" />
              <div class="input-group-text">&percnt;</div>
            </div>
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label"> Mortgage Term</label>
            <input type="number" class="form-control" name="mortgage-term" value="{{ isset($customer) ? $customer->mortgage_term : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Type of Mortgage</label>
            <select class="form-control form-select" name="mortgage-type-of-mortgage" data-choices='{"searchEnabled": false}'>
              <option value="">&nbsp;</option>
              <option value="IO" {{ isset($customer) && $customer->mortgage_type_of_mortgage === 'IO' ? 'selected' : ''}}>IO - Interest Only</option>
              <option value="RPYT" {{ isset($customer) && $customer->mortgage_type_of_mortgage === 'RPYT' ? 'selected' : ''}}>RPYT - Repayment Only</option>
              <option value="PART AND PART" {{ isset($customer) && $customer->mortgage_type_of_mortgage === 'PART AND PART' ? 'selected' : ''}}>PART AND PART - Part Interest, Part Repayment</option>
            </select>
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Lender</label>
            <input type="text" class="form-control" name="mortgage-lender" value="{{ isset($customer) ? $customer->mortgage_lender : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Application Date</label>
            <input type="date" class="form-control" name="mortgage-application-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->mortgage_application_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">Offer Date</label>
            <input type="date" class="form-control" name="mortgage-offer-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->mortgage_offer_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Completion Date</label>
            <input type="date" class="form-control" name="mortgage-completion-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->mortgage_completion_date : '' }}" />
          </div>
        </div>

        <div class="col-12">
          <div class="alert alert-info">
            Notifications will be generated four months before the completion date fo inform you a customer is coming to the end of a deal
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">L&amp;G Recon Date</label>
            <input type="date" class="form-control" name="mortgage-lg-recon-date" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ isset($customer) ? $customer->mortgage_lg_recon_date : '' }}" />
          </div>
        </div>
  
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label">L&amp;G Reference</label>
            <input type="text" class="form-control" name="mortgage-lg-reference" value="{{ isset($customer) ? $customer->mortgage_lg_reference : '' }}" />
          </div>
        </div>
  
      </div>
      <!-- end::Mortgage Form -->
  