@extends("forgot_password.master")

@section("content")
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <div class="login-form">
            <div class="card mb-0" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="icon-2x p-3 mt-1" src="/assets/images/logo_success_forgot_password.png">
                        <h5 class="mb-2" style="font-weight: 400; font-size: 20px;">@lang("cms.Success")</h5>
                        <span style="font-size: 12px; font-weight: 300; color: #B9B9B9;" class="d-block ml-4 mr-4">Your password has been changed successfully. Please login with your new password in login page</span>
                    </div>

                    <div class="text-center mb-3">
                        <a href="{{ route("cms.login") }}" class="btn btn-primary px-5" style="border-radius: 8px;">Back To Login</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /content area -->
@endsection
