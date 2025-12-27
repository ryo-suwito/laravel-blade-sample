@extends("forgot_password.master")

@section("content")
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login form -->
        <form class="login-form" style="width:380px;" method="post" action="{{ route("cms.forgot_password.reset") }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="card mb-0" style="width:380px;">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="icon-2x p-3 mt-1" src="/assets/images/logo_reset_password.png">
                        {{-- <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-pill p-3 mb-3 mt-1"></i>--}}
                        <h5 class="mb-0">@lang("cms.Reset your password")</h5>
                        <span class="d-block text-muted">@lang("cms.Enter your new password for")</span>
                        <span class="d-block">{{ $email }}</span>
                    </div>

                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-custom alert-dismissible">
                            <div style="display: flex;flex-direction: column;">
                                @foreach($errors->all() as $error)
                                    <span class="d-block">{{ $error }}</span>
                                @endforeach
                            </div>
                            <button type="button" class="close-custom" onclick="dismissAlert(this)"><span>&times;</span></button>
                        </div>
                    @endif

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" name="new_password" id="password" class="form-control" placeholder="@lang("cms.New Password")" value="{{ old("new_password", "") }}" style="border-radius: 8px;">
                        <div class="form-control-feedback">
                            <i class="icon-lock2" style="color: #2887FB;"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" name="new_password_confirmation" id="confirmPassword" class="form-control" placeholder="@lang("cms.Confirm Password")" value="{{ old("confirm_password", "") }}" style="border-radius: 8px;">
                        <div class="form-control-feedback">
                            <i class="icon-lock2" style="color: #2887FB;"></i>
                        </div>
                    </div>

                    <div class="alert alert-custom d-none" id="rulePasswordRegex">
                        <p>@lang("cms.reset_password_rule")</p>
                    </div>

                    <div class="alert alert-custom d-none" id="rulePasswordConfirm">
                        <p>@lang("cms.confirm_password_not_match")</p>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-block" style="border-radius: 10px;">@lang("cms.Reset Password")</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- /login form -->

    </div>
    <!-- /content area -->
@endsection

@section("script")
    <script>
        $(document).ready(function() {
            function validatePasswordRegex() {
                let password = $('#password').val();

                // Combined regex for all rules
                let regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{10,}$/;

                if (! regex.test(password)) {
                    return false;
                }

                return true;
            }

            function validatePasswordConfirm() {
                let password = $('#password').val();
                let confirmPassword = $('#confirmPassword').val();

                if (password !== confirmPassword) {
                    return false;
                }

                return true;
            }

            $("#password").keyup(function() {
                if (validatePasswordRegex()) {
                    $("#rulePasswordRegex").addClass("d-none");
                    $("#btn-submit").removeAttr("disabled");
                } else {
                    $("#rulePasswordRegex").removeClass("d-none");
                    $("#btn-submit").attr("disabled", "disabled");
                }
            });

            $("#btn-submit").click(function (e) {
                if (! validatePasswordConfirm()) {
                    $("#rulePasswordConfirm").removeClass("d-none");
                    e.preventDefault();
                } else {
                    $("#rulePasswordConfirm").addClass("d-none");
                }
            });
            function dismissAlert(button) {
                const alertBox = button.closest('.alert');
                alertBox.style.display = 'none';
            }
        });
    </script>
@endsection
