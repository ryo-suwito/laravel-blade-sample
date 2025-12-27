@extends("forgot_password.master")

@section("content")
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login form -->
        <form class="login-form" method="post" action="{{ route("cms.forgot_password.post") }}">
            @csrf
            <div class="card mb-0" tyle="width: 356px ; heigth: auto ";>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="icon-2x p-3 mt-1" src="/assets/images/logo_reset_password.png">
                        {{-- <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-pill p-3 mb-3 mt-1"></i>--}}
                        <h5 class="mb-2">@lang("cms.Forgot Password")</h5>
                        <span style="font-size: 12px" class="d-block">@lang("cms.To change the password, we will send it through the email that you already registered")</span>
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

                    <div class="form-group form-group-feedback form-group-feedback-left" style="height: 40px;">
                        <input id="email" type="email" name="email" class="form-control" placeholder="@lang("cms.Your Email")" value="{{ old("email") }}" style="border-radius: 8px;">
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                    </div>

                    <button type="submit" disabled class="btn btn-secondary btn-block" style="border-radius: 8px;">@lang("cms.Send")</button>
                </div>
            </div>
        </form>
        <!-- /login form -->

    </div>
    <!-- /content area -->


    <script>
        const email = document.querySelector('input[name=email]')
        const val = document.getElementById('email').value;
        if (val){
            const x = document.querySelector('button[type=submit]');
            x.classList.remove('disabled','btn-secondary');
            x.classList.add('btn-primary')
            x.disabled = false
        }
        email.addEventListener('input', function (e) {
            if (e.target.value){
                const x = document.querySelector('button[type=submit]');
                x.classList.remove('disabled','btn-secondary');
                x.classList.add('btn-primary')
                x.disabled = false
            }
        });

        function dismissAlert(button) {
        const alertBox = button.closest('.alert');
        alertBox.style.display = 'none';
    }

    </script>
@endsection
