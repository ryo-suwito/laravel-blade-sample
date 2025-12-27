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
    <link href="{{ asset('assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/base.js') }}"></script>
    <style>
        body {
            background-color: #181a1b;
            font-family: 'Roboto', sans-serif;
        }
        .otp-form .card {
            width: 368px;
            height: auto;
            margin: 0 auto;
            border-radius: 5px;
        }
        .otp-form .form-group {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .otp-form .otp-input {
            align-items: center;
            justify-content: center;
            width: 37.64px;
            height: 3rem;
            margin: 0.2rem;
            font-size: 1.5rem;
            text-align: center;
            border: 1px solid black;
            border-radius: 4px;
            background-color: black;
            color: white;
        }   
        .otp-input:disabled {
            background-color: #1A1B1F;
        }
        .otp-input:focus {
            outline: 1px solid #2887FB;
            color: white;
        }
        .otp-timer {
            font-size: 0.9rem;
            color: #B9B9B9;
            text-align: center;
            display: none;
        }
        .small-text-centered {
            font-size: 12px;
            display: flex;
            align-items: center;
            text-align: center;
        }
        .small-text-centered input[type="checkbox"] {
            margin-right: 8px;
        }
        .text-muted {
            color: #969699;
        }
        .resend-link {
            cursor: pointer;
            color: #2887FB;
            text-decoration: none;
        }
        .back-arrow {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .alert-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ED272730;
            border: 1px solid;
            border-radius: 10px;
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

        #loadingOverlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-content img {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Adjust as needed */
        }
    </style>
</head>

<body>
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content d-flex justify-content-center align-items-center">
                    <form class="otp-form" id="otpForm" method="POST">
                        @csrf
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="back-arrow">
                                    <a href="{{ route('cms.index') }}" class="text-muted">
                                        <i class="icon-arrow-left8"></i>
                                    </a>
                                </div>
                                <div class="text-center mb-3">
                                    <img src="{{ asset('assets/images/otpmail.png') }}" alt="OTP Mail" style="width: 68px; height: 68px;">
                                    <h5 class="mt-2" style="font-weight: 500; font-size: 20px;">Enter OTP</h5>
                                    <span class="d-block text-muted" style="font-weight: 300;">The OTP has been sent to {{ $email }}.</span>
                                    <span class="d-block text-muted" style="font-weight: 300;">Please check your inbox or spam folder for the code.</span>
                                </div>
                                
                                
                                <div class="alert-custom" id="alertBox" style="display: none;">
                                    <span class="d-block" id="alertMessage"></span>
                                    <button type="button" class="close-custom" onclick="dismissAlert(this)"><span>&times;</span></button>
                                </div>
                                

                                <div class="mb-2 form-group">
                                    @for ($i = 1; $i <= 6; $i++) <input type="password" name="otp{{ $i }}" maxlength="1" class="otp-input" autofocus aria-label="OTP Digit {{ $i }}">
                                        @endfor
                                        <input type="hidden" id="otp_value" name="otp_value">
                                </div>

                                <div class="mb-3 mt-3 text-center">
                                    <span class="text-muted" style="font-weight: 300; font-size: 14px;">Didn't receive OTP?</span>
                                    <a id="resendButton" class="resend-link">Resend</a>
                                    <div class="mb-3 text-center otp-timer" id="otp-timer">
                                        <span id="timer">00:00</span>
                                    </div>
                                </div>

                                <hr class="mr-4 ml-4">

                                <div class="mb-3 ml-4">
                                    <label class="small-text-centered">
                                        <input type="checkbox" name="remember_me" checked>Remember this device
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="loadingOverlay" style="display: none;">
                        <div class="loading-content">
                            <img src="{{ asset('assets/images/ic_loading.gif') }}" alt="Loading...">
                        </div>
                    </div>

                    <form id="resendForm" action="{{ route('otp.send') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>

                <div class="navbar navbar-expand-lg navbar-light border-bottom-0 border-top">
                    <span class="navbar-text">
                        &copy; {{ date('Y') }} - {{ env("APP_NAME") }} by <a href="https://yukk.co.id/" target="_blank">YUKK Kreasi Indonesia</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('otpForm');
    const inputs = document.querySelectorAll('.otp-input');
    const otp_valueInput = document.getElementById('otp_value');
    const alertBox = document.getElementById('alertBox');
    const alertMessage = document.getElementById('alertMessage');
    const resendButton = document.getElementById('resendButton');
    const resendForm = document.getElementById('resendForm');
    const timerElement = document.getElementById('timer');
    const timerContainer = document.getElementById('otp-timer');
    let interval;

    function showTimer(disabled) {
        if (disabled) {
            resendButton.style.display = 'none';
            timerContainer.style.display = 'inline';
        } else {
            resendButton.style.display = 'inline';
            timerContainer.style.display = 'none';
        }
    }

    function showAlert(message, type) {
        alertMessage.textContent = message;
        alertBox.style.display = 'flex';
        alertBox.style.backgroundColor = type === 'error' ? 'rgba(92, 13, 18, 0.3)' : 'rgba(0, 128, 0, 0.3)';
        alertBox.style.borderColor = type === 'error' ? '#5c0d12' : '#008000';
    }

    window.dismissAlert = function(button) {
        const alertBox = button.closest('.alert-custom');
        alertBox.style.display = 'none';
        $('.login-card').css('height', 'auto');
    };

    function dismissAlert() {
        alertBox.style.display = 'none';
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', (event) => {
            const currentInput = event.target;
            if (currentInput.value.length > 0) {
                currentInput.value = '●';
                currentInput.dataset.realValue = event.data;
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                } else {
                    updateHiddenOTP();
                    verifyForm();
                }
            }
        });

        input.addEventListener('paste', (event) => {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text');
            const digits = paste.split('');
            inputs.forEach((input, i) => {
                input.value = digits[i] ? '●' : '';
                if (digits[i]) {
                    input.dataset.realValue = digits[i];
                } else {
                    delete input.dataset.realValue;
                }
            });
            if (digits.length === inputs.length) {
                updateHiddenOTP();
                verifyForm();
            }
        });

        input.addEventListener('keydown', (event) => {
            const currentInput = event.target;
            if (event.key === 'Backspace') {
                if (currentInput.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                } else {
                    currentInput.value = '';
                    delete currentInput.dataset.realValue;
                }
            }
        });
    });

    function updateHiddenOTP() {
        let otp_value = '';
        inputs.forEach(input => {
            otp_value += input.dataset.realValue || '';
        });
        otp_valueInput.value = otp_value;
    }

    function verifyForm() {
        dismissAlert();
        $.ajax({
            url: '{{ route('otp.verify') }}',
            type: 'POST',
            data: $('#otpForm').serialize(),
            success: function(response, xhr) {
                $('#loadingOverlay').show();
                disableBackButton(); // Disable the back button
                setTimeout(function() {
                    window.location.href = '{{ route('cms.dashboard') }}';
                }, 500);
            },
            error: function(xhr) {
                if (xhr.status === 429) {
                    showAlert('You have reached maximum attempt of OTP input. Please request OTP again.', 'error');
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.value = '';
                    });
                } else if (xhr.status === 404) {
                    showAlert('Invalid OTP. Please try again.', 'error');
                } else {
                    showAlert(xhr.responseText.message, 'error');
                }
                inputs.forEach(input => {
                    input.value = '';
                });
                inputs[0].focus();
            }
        });
    }

    function startTimer(duration) {
        var timer = duration, minutes, seconds;
        clearInterval(interval);
        interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timerElement.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                showTimer(false);
            }
        }, 1000);
    }

    function initializeTimer() {
        $.ajax({
            url: '{{ route('otp.timer') }}',
            method: 'GET',
            success: function(response) {
                if (response.expired_at) {
                    var remainingTime = Math.floor((new Date(response.expired_at).getTime() - new Date().getTime()) / 1000);
                    if (response.resend_count > 3) {
                        remainingTime += (response.resend_count - 3) * 180;
                    }
                    if (remainingTime > 0) {
                        showTimer(true);
                        startTimer(remainingTime);
                    } else {
                        showTimer(false);
                    }
                } else {
                    showTimer(false);
                }

                if(response.disable_input){
                    inputs.forEach(input => {
                        input.disabled = true;
                    });
                }else{
                    inputs.forEach(input => {
                        input.disabled = false;
                    });
                }

            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showTimer(false);
            }
        });
    }

    initializeTimer();

    function handleResendClick() {
        resendButton.disabled = true; // Disable the button
        dismissAlert(); // Dismiss the alert

        $.ajax({
            url: '{{ route('otp.send') }}',
            method: 'POST',
            data: $(resendForm).serialize(),
            success: function(response) {
                var timerDuration = Math.floor((new Date(response.expired_at).getTime() - new Date().getTime()) / 1000);
                if (response.resend_count > 3) {
                    timerDuration += (response.resend_count - 3) * 180;
                }
                showTimer(true);
                startTimer(timerDuration);
                inputs.forEach(input => {
                    input.disabled = false;
                });
                setTimeout(function() {
                    resendButton.disabled = false;
                }, 500);
            },
            error: function(xhr) {
                showAlert(xhr.responseText.message, 'error');
            },
        });
    }

    resendButton.addEventListener('click', handleResendClick);

    function disableBackButton() {
        if (typeof history.pushState === "function") {
            history.pushState(null, null, null);
            window.onpopstate = function() {
                history.pushState(null, null, null);
                alert("Back button is disabled on this page.");
            };
        } else {
            alert("Your browser does not support this functionality.");
        }
    }
});
</script>
</body>
</html>
