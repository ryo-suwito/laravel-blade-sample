<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ env("APP_NAME") }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/base.css')}}" rel="stylesheet" type="text/css">

    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/base.js')}}"></script>

    <style>
        body {
            background-color: #181a1b;
            font-family: 'Roboto', sans-serif;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 150px;
            max-height: 57px;
        }
        .login-card {
            width: 410px;
            background-color: #2c2f33;
            border-radius: 20px;
            padding: 30px 40px;
        }
        .login-card h5 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
            font-size: 22px;
            font-weight: 400;
        }
        .login-card .form-group {
            margin-bottom: 15px;
        }
        .login-card input {
            width: 330px;
            height: 60px;
            background-color: #181717;
            border-radius: 10px;
        }
        .login-card input:focus {
            background-color: #181717;
        }
        .login-card input:-webkit-autofill,
        .login-card input:-webkit-autofill:hover,
        .login-card input:-webkit-autofill:focus,
        .login-card input:-webkit-autofill:active {
            box-shadow: 0 0 0px 1000px #181717 inset;
            -webkit-text-fill-color: #ffffff;
            border: 1px solid #2887FB;
        }
        .form-control-feedback {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #2887FB;
        }
        .login-btn {
            background-color: #2887FB;
            border: none;
            border-radius: 20px;
            padding: 10px;
            width: 330px;
        }
        .text-button {
            background: none;
            border: none;
            color: #2887FB;
            margin-top: 20px;
            font-size: small;
            font-weight: 500;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle .toggle-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #969699;
        }
        .forgot-password {
            color: #969699;
        }
        .register-text {
            color: #969699;
            font-size: small;
        }
        .alert-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ED272730;
            border: 1px solid;
            border-radius: 8px;
            border-color: #ED2727;
            padding: 10px 15px;
            margin-bottom: 20px;
            position: relative;
        }
        .close-custom {
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
    </style>
</head>

<body>
<div class="login-container">
    <div class="logo-container">
        <img src="{{ asset('assets/images/logo_yukk_pg.png') }}" alt="Yukk Logo">
    </div>
    <div class="login-card">
        <form class="login-form" method="post" action="{{ route('cms.login.post') }}">
            @csrf
            <div class="text-center mb-4 mt-3">
                <h5 class="">Log In</h5>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="alert alert-custom alert-dismissible">
                    <div style="display: flex;flex-direction: column;">
                        @foreach($errors->all() as $error)
                            <span class="d-block">{{ $error }}</span>
                        @endforeach
                    </div>
                    <button type="button" class="close-custom" id="dismiss-alert"><span>&times;</span></button>
                </div>
            @endif

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="email" name="username" class="form-control" placeholder="Email" value="{{ old('username', '') }}" autocomplete="off">
                <div class="form-control-feedback">
                    <i class="icon-user"></i>
                </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left password-toggle">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="off">
                <div class="form-control-feedback">
                    <i class="icon-lock2"></i>
                </div>
                <i class="icon-eye-blocked toggle-icon" id="toggle-password-visibility"></i>
                <a href="{{ route('cms.forgot_password.index') }}" class="float-right mt-2 mb-4 register-text">Forgot password?</a>
            </div>

            <div class="form-group">
                <button type="submit" class="btn login-btn">Log In</button>
            </div>

            <div class="text-center">
                <span class="register-text">Didn't have account?</span>
                <a href="https://yukk.co.id/id/register" class="text-button">Register</a>
            </div>
        </form>
    </div>
</div>

<!-- untuk popup announcement -->
<!-- @include('login.popup') -->

<!-- Footer -->
<div class="navbar navbar-expand-lg navbar-light border-bottom-0 border-top">
    <span class="navbar-text">
        &copy; {{date('Y')}} - {{ env("APP_NAME") }} by <a href="https://yukk.co.id/" target="_blank">YUKK Kreasi Indonesia</a>
    </span>

</div>
<!-- /footer -->

<script nonce="{{ csp_nonce() }}">
@if (\App\Helpers\S::getFlashFailed(true))
    $(document).ready(function() {
        @if (\App\Helpers\S::getFlashFailed(true))
            Swal.fire({
                text: '{{ \App\Helpers\S::getFlashFailed(true) }}',
                icon: 'error',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        @endif
        $('.login-card').css('height', 'auto');
    });
@endif

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.add('icon-eye');
        toggleIcon.classList.remove('icon-eye-blocked');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.add('icon-eye-blocked');
        toggleIcon.classList.remove('icon-eye');
    }
}

function dismissAlert(button) {
    const alertBox = button.closest('.alert');
    alertBox.style.display = 'none';
    $('.login-card').css('height', 'auto');
}

$(document).ready(function() {
    $('#popup').show();

    $("#dismiss-alert").click(function() {
        dismissAlert(this);
    });

    $("#toggle-password-visibility").on('click', function() {
        togglePasswordVisibility();
    });
});

$(document).on('click', '#closePopup', function() {
    $('#popup').hide();
});
</script>
</body>
</html>
