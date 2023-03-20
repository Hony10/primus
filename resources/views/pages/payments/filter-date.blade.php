
<div class="col-12 col-md-5 col-lg-auto me-3">
  <div class="form-group">
    <label class="form-label">Date from</label>
    <input type="date" class="form-control" name="filter-date-from" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ date('Y-m-d', strtotime('-6 months')) }}" />
  </div>
</div>

<div class="col-12 col-md-5 col-lg-auto me-3">
  <div class="form-group">
    <label class="form-label">Date to</label>
    <input type="date" class="form-control" name="filter-date-to" data-flatpickr='{"dateFormat": "Y-m-d", "altInput": true, "altFormat": "d/m/Y"}' value="{{ date('Y-m-d') }}" />
  </div>
</div>
