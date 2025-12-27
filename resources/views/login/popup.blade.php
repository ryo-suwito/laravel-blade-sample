<!DOCTYPE html>
<html lang="en">
    <head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" type="text/css">
        <style> 
            body {
                font-family: "Roboto", sans-serif !important;
            }
            .card {
                display: flex;
                justify-content: center;
                align-items: center;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }

            .card-content {
                background-color: #2c2f33;
                padding: 10px;
                max-width: 400px;
                max-height: 80%;
                box-shadow: 0 5px 15px rgba(0,0,0,.5);
                border-radius: 10px;
                text-align: center;
                color: white;
                font-family: 'Roboto', sans-serif;
            }

            .card-header {
                margin: 15px;
            }

            .card-header img {
                font-size: 48px;
                margin-bottom: 20px;
            }

            .card-header h2 {
                font-size: 16px;
                font-weight: 500;
                margin: 10px 0;
            }
            
            .card-body p { 
                font-family: 'Roboto', sans-serif;
                font-weight: 300 !important;
                font-size: 14px;
                color: #FFFFFF;
                margin: 20px;
                line-height: 25px;
                text-align: center;
            }

            .card-footer {
                margin:20px;
            }

            .close-button {
                background-color: #2c2f33;
                width: 291px;
                height: 46px;
                color: white;
                border-color: #2887FB;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 8px;
                font-size: 14px;
                font-family: 'Roboto', sans-serif;
            }

            .close-button:hover {
                background-color:#2887FB;
            }
        </style>
    </head>
    <body>
        <div id="popup" class="card">
            <div class="card-content">
                <div class="card-header">
                <img src="{{ asset('assets/images/infoicon.png') }}">
                <h2>Announcement: OTP SMS Available in Jun 4</h2>
            </div>
                <div class="card-body">
                    <p>Effective Jun 4, 2025, all user will be able to use SMS as a way to send One-Time Password (OTP).</p>
                    <p>To ensure your phone number is VALID, please follow the instruction below:<br>
                        <span>1.</span> Login to dashboard<br>  
                        <span>2.</span> Go to Account &gt; Profile<br>
                        <span>3.</span> Update your phone number on "Phone" field<br>
                        <span>4.</span> Click "Save"
                    </p>
                    <br>
                    <button id="closePopup" class="close-button">Close</button>
                </div>
            </div>
        </div>
    </body>
</html>