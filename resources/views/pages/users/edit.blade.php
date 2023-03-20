@extends('structure.dashboard')


@section('stylesheet')
@endsection


@section('script')
<script src="/assets/js/page-edit-user.min.js?v={{ config('app.version', '1.0.0') }}"></script>
@endsection


@section('pretitle')
Users
@endsection


@section('title')
{{ $username }}
@endsection


@section('content')
<form>

    @csrf

    <input type="hidden" name="loaded-username" value="{{ $username }}" />

    <div class="row">
      <div class="col-12 col-md-6">
        <!-- First name -->
        <div class="form-group">
          <label class="form-label">First name</label>
          <input type="text" class="form-control" name="first-name">
        </div>
      </div>

      <div class="col-12 col-md-6">
        <!-- Last name -->
        <div class="form-group">
          <label class="form-label">Last name</label>
          <input type="text" class="form-control" name="last-name">
        </div>
      </div>

      <div class="col-12">
        <!-- Email address -->
        <div class="form-group">
          <label class="mb-1">Email address</label>
          <small class="form-text text-muted">This will be the username used to login and notifications for this user will also be sent to this address</small>
          <input type="email" class="form-control" name="email-address">
        </div>
      </div>

      <div class="col-12 col-md-6">
          <!-- Password -->
          <div class="form-group">
              <label class="mb-1">Password</label>
              <input type="password" class="form-control" name="password1" />
          </div>
      </div>

      <div class="col-12 col-md-6">
          <!-- Retype password -->
          <div class="form-group">
              <label class="mb-1">Retype password</label>
              <input type="password" class="form-control" name="password2" />
          </div>
      </div>
    </div> <!-- / .row -->

    <div class="list-group my-n3">

        <div class="list-group-item">
            <div class="row align-items-center">
            <div class="col">
                <h4 class="font-weight-base mb-1">Enabled</h4>
                <small class="text-muted">If this user is not enabled they will not be able to log into this dashboard.</small>
            </div>
            <div class="col-auto">
                <!-- Switch -->
                <div class="form-check form-switch">
                <input class="form-check-input" id="enabledSwitch" type="checkbox" name="account-enabled">
                <label class="form-check-label" for="enabledSwitch"></label>
                </div>
            </div>
            </div> <!-- / .row -->
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />

    <div class="row">
        <h3 class="mb-5 mt-3">User Permissions</h3>

        <div class="list-group my-n3">

            <div class="list-group-item">
                <div class="row align-items-center">
                <div class="col">
                    <h4 class="font-weight-base mb-1">Global Administrator</h4>
                    <small class="text-muted">Can access and modify all parts of the system. Can view and manage all other users.</small>
                </div>
                <div class="col-auto">
                    <!-- Switch -->
                    <div class="form-check form-switch">
                    <input class="form-check-input" id="subscriptionsSwitchOne" type="checkbox" name="permissions-global-admin">
                    <label class="form-check-label" for="subscriptionsSwitchOne"></label>
                    </div>
                </div>
                </div> <!-- / .row -->
            </div>

            <div class="list-group-item">
                <div class="row align-items-center">
                <div class="col">
                    <h4 class="font-weight-base mb-1">Agent</h4>
                    <small class="text-muted">Can be assigned to a customer and project. Can also view their own statements and add expenses.</small>

                </div>
                <div class="col-auto">

                    <!-- Switch -->
                    <div class="form-check form-switch">
                    <input class="form-check-input" id="subscriptionsSwitchTwo" type="checkbox" name="permissions-agent">
                    <label class="form-check-label" for="subscriptionsSwitchTwo"></label>
                    </div>

                </div>
                </div> <!-- / .row -->
            </div>
        </div>
    </div> <!-- / .row -->

    <!-- Button -->
    <div class="mt-5">
        <button type="button" class="btn btn-primary button-save-user me-3">Save Changes</button>
        <a href="/users" class="btn btn-danger button-cancel">Cancel</a>
    </div>

    <div class="py-5">
        <div class="alert alert-secondary my-3 create-user-loading fade" style="display: none;">
            <div class="spinner-grow spinner-grow-sm me-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Saving user details</span>
        </div>

        <div class="alert alert-danger my-3 create-user-error fade" style="display: none;">
            <span class="fe fe-alert-triangle me-3"></span>
            <span>Failed to save user, a server error occured</span>
        </div>
    </div>

</form>
@endsection
