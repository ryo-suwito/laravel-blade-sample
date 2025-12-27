@extends("forgot_password.master")

@section("content")
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <div class="login-form">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-checkmark icon-2x text-success border-success border-3 rounded-pill p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">@lang("cms.Forgot Password")</h5>
                        <span class="d-block text-muted">@lang("cms.Your password has been changed successfully. You can login with your new password in login page.")</span>
                    </div>

                    <a href="{{ route("cms.login") }}" class="btn btn-primary btn-block"><i class="icon-arrow-left8 mr-2"></i> @lang("cms.Back to Login")</a>
                </div>
            </div>
        </div>

    </div>
    <!-- /content area -->
@endsection