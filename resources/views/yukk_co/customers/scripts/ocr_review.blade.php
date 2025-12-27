<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script>
    var kymMessage = "";
    var kymCode = "";
    var fileKTP = null;
    var blob;
    var filename;
    var fileSelfie = null;
    var selfieBlob;
    var selfieFilename;
    var selfieImageData;
    var imageData;
    var fileKTPChanged = false;
    var mainForm = null;
    async function fetchImage(imageData, filename = null, imageType = 'image') {
        // Send a request to fetch the image asynchronously
        try {
            const urlImage = "{{ route('yukk_co.customers.image')}}";
            const encodedImageData = encodeURIComponent(imageData);
            const finalUrl = `${urlImage}?url=${encodedImageData}`;

            const response = await fetch(finalUrl);

            if (response.ok) {
                const contentType = response.headers.get('Content-Type');

                if (contentType.startsWith('image/')) {
                    // It's an image
                    console.log(`${imageType.charAt(0).toUpperCase() + imageType.slice(1)} received.`);
                    
                    const blob = await response.blob();
                    
                    if (!filename) {
                        const decodedImageData = decodeURIComponent(imageData);
                        filename = decodedImageData.split('/').pop().split('?')[0].split('_');
                        // Remove the first element which is the base64 prefix
                        filename.shift();
                    }

                    
                    if(blob){
                        let fileKTPNew = new File([blob], filename,{type:"image/png", lastModified:new Date().getTime()});
                        let container = new DataTransfer();
                        container.items.add(fileKTPNew);
                        document.getElementById(imageType).files = container.files;
                        console.log(imageType, document.getElementById(imageType).files);
                    }

                    return { blob, filename };
                } else {
                    console.error('The response is not an image.');
                    return null;
                }
            } else {
                console.error(`Request failed with status: ${response.status}`);
                return null;
            }
        } catch (error) {
            console.error(`Request error occurred: ${error}`);
            return null;
        }
    }

    async function fetchKTP(imageData, filename = null) {
        return fetchImage(imageData, filename, 'file_ktp');
    }

    async function fetchSelfie(selfieImageData, selfieFilename = null) {
        return fetchImage(selfieImageData, selfieFilename, 'file_selfie');
    }


    async function hitOcr(keepDownloadButton=false){
        if ($('#is_whitelist').val() == 1){
            return;
        }
        blob = null;
        filename = null;
        selfieBlob = null;
        selfieFilename = null;

        // remove all ocrKtp error message if any
        $('#ocr_error').remove();
        // remove ocr details if any
        $('#ocr_details').remove();

        $('#file_ktp_preview').after('<div id="ocr_details"></div>');
        // add input hidden fields to store the ocr details
        $('#ocr_details').append('<input type="hidden" id="nik" name="nik" value="">');
        $('#ocr_details').append('<input type="hidden" id="identity_name" name="identity_name" value="">');
        $('#ocr_details').append('<input type="hidden" id="birthdate" name="birthdate" value="">');
        $('#ocr_details').hide();
        // Get the file KTP
        fileKTP = $('#file_ktp')[0].files[0];
        fileSelfie = $('#file_selfie')[0].files[0];
        imageData = $('#file_ktp_preview').attr('src');
        selfieImageData = $('#file_selfie_preview').attr('src');
        if (!fileKTP) { 
            if (!imageData) {
                swal("Please select a file or upload an image.");
                return;
            }
            let result = await fetchKTP(imageData);
            if (result) {
                blob = result.blob;
                filename = result.filename;
            }
        } else {
            filename = fileKTP.name;
        }

        $('#file_selfie_ocr_error').remove();
        if (!fileSelfie) {
            if(!selfieImageData){
                $('#file_selfie').after('<span id="file_selfie_ocr_error" class="error" style="color: red;">Please upload a valid Selfie file.</span>');
            }
            let result = await fetchSelfie(selfieImageData, selfieFilename);
            if (result) {
                selfieBlob = result.blob;
                selfieFilename = result.filename;
            }
        } else {
            selfieFilename = fileSelfie.name;
        }

        // Prepare form data
        let formData = new FormData();
        formData.append('file_ktp', blob || fileKTP);
        formData.append('_token', '{{ csrf_token() }}'); 
        formData.append('requestId', '{{ isset($item) ? $item->id : '' }}');
        formData.append('filename', filename);
        formData.append('targetType', 'CUSTOMER');

        function showOCRError(message=null){
            Swal.fire({
                title: "OCR Error",
                text: "Data reading is not successful. Please retry the file upload.",
                icon: "error",
                button: "OK",
            });
            $('#file_ktp').after('<span id="ocr_error" class="error" style="color: red;">' + message + '</span>');
        }

        function resetIfError(message=null){
            $('#nik').val('');
            $('#identity_name').val('');
            $('#birthdate').val('');
            $('#ocr_details').hide();
            // remove preview button
            $('#preview_ocr_button').hide();
            $('#removeFileKtp').hide();
            showOCRError(message);
        }

        if ($('#isWhitelist').val() == 1){
            return;
        } else if(!(kyc && !fileKTPChanged)){
            // show loading spinner overlay body to prevent user interaction
            $('body').after('<div class="loading"></div>');
            // add bootstrap spinner to the loading spinner overlay
            $('.loading').append('<div class="spinner-border text-primary" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
        }

        // Send AJAX request to the endpoint using the route name
        $.ajax({
            url: "{{ route('electronic_certificate.ocr') }}", // Using Laravel route name
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // remove button download ktp if any
                if (!keepDownloadButton) {
                    $('#btn_download_ktp').remove();
                }
                // Hide the loading spinner overlay
                $('.loading').remove();
                // Display the OCR details or perform any other actions
                let result = response.result
                if (result){
                    // if response status code != 200, show result message
                    if (response.status_message != "success extract ktp"){
                        resetIfError(result.message);
                        return;
                    }
                    $('#nikModal').val(result.nik);
                    $('#nameModal').val(result.full_name);
                    $('#identity_name').val(result.full_name);
                    $("#nik").val(result.nik);
                    
                    // Format birth date to mm/dd/yyyy
                    var parts = result.date_of_birth.split("-");
                    // Rearrange the parts to mm/dd/yyyy format
                    var birthDate = parts[1] + '/' + parts[0] + '/' + parts[2];
                    $('#birthdateModal').val(birthDate);
                    $('#birthdate').val(birthDate);
                    initiatePreviewButton();
                    // remove error message if any
                    $('#ocr_error').remove();
                    // show image ktp
                    $('#file_ktp_preview').show();
                    $('#removeFileKtp').show();
                } else {
                    // resetIfError();
                }
            },
            error: function(xhr, status, error) {
                // Hide the loading spinner overlay
                $('.loading').remove();
                // Handle the error
                // resetIfError();
            }
        });
        // dont process further if ocr is not successful
        if ($('#ocr_error').text()){
            return;
        }
        
    }
    var kyc = {!! json_encode($kyc) !!};
    function initiatePreviewButton(){
        if(kyc && kyc.verihubs_status == 'verified' && !fileKTPChanged){
            $('#preview_ocr_button').hide();
            return;
        }
        $('#preview_ocr_button').show();
        $('#preview_ocr_button').css('display', 'block');
        $('#preview_ocr_button').css('max-width', '100%');
        // margin -top 10px
        $('#preview_ocr_button').css('margin-top', '10px');
        $('#preview_ocr_button').prop('disabled', false);
    }

    // when preview button is clicked show the ocr details
    $('#preview_ocr_button').click(function(event){
        event.preventDefault();
        $('#ocr_details').show();
        $('#ktpDetailsModal').modal('show');
        
    });

    function onFileKtpChanged(thisObj){
        fileKTPChanged = true;
        var file = thisObj.files[0];
        $('#ocr_details').remove();
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#file_ktp_preview').attr('src', e.target.result);
                $('#file_ktp_preview').attr('style', 'display: block; max-width: 100%; margin-top: 10px;');
                $('#file_ktp_preview').show();
                $('#removeFileKtp').show();
            };
            reader.readAsDataURL(file);
            // skip ocr if whitelist is checked
            if ($('#isWhitelist').val() == 1){
                return;
            }
            hitOcr();
        } else {
            $('#file_ktp_preview').attr('src', '');
            $('#file_ktp_preview').hide();
            $('#removeFileKtp').hide();
        }
    }
    
    var tempValidationMessage = '';

    function checkKtpAvailability() {
        tempValidationMessage = '';

        let statusValue = $('#status').val();
        if (statusValue == 0) {
            $('#ktp_no_error').remove();
        }

        if ($('#is_whitelist').val() == 1) {
            return;
        }

        let ktpInput = $('#ktp_no');
        $.ajax({
            url: "{{ route('yukk_co.customers.check_ktp') }}",
            type: "GET",
            data: {
                ktp_number: ktpInput.val()
            },
            success: function(response) {
                $('#ktp_no_error').remove();
                let result = response.result;

                if (result && result.validation_result !== true) {
                    if (statusValue == 1) {
                        ktpInput[0].setCustomValidity(result.message);
                        $('#ktp_no').after('<span id="ktp_no_error" class="error" style="color: red;">' + result.message + '</span>');
                    } else {
                        ktpInput[0].setCustomValidity('');
                    }
                    tempValidationMessage = result.message;
                } else {
                    ktpInput[0].setCustomValidity('');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred while checking KTP: " + error);
                if (statusValue == 1) {
                    ktpInput[0].setCustomValidity('KTP check failed due to a system error.');
                }
                tempValidationMessage = 'KTP check failed due to a system error.';
            }
        });
    }
    
    $(document).ready(function() {
        $('#status').on('change', function() {
            let statusValue = $(this).val();
            if (statusValue == 1) checkKtpAvailability() 
            if (statusValue == 1 && tempValidationMessage) {
                let ktpInput = $('#ktp_no');
                $('#ktp_no_error').remove();
                ktpInput[0].setCustomValidity(tempValidationMessage);
                $('#ktp_no').after('<span id="ktp_no_error" class="error" style="color: red;">' + tempValidationMessage + '</span>');
            } else {
                $('#ktp_no_error').remove();
                $('#ktp_no')[0].setCustomValidity('');
            }
        });
    })

    $(document).ready(function(isEditted = false){
        if(kymMessage && kymCode){
            swal({
                title: "KYM Result",
                text: kymMessage,
                icon: kymCode == 200 ? "success" : "error",
                confirmButtonText: 'OK'
            });
        }

        $('#skip_fill_rekening').change(function(){
            if ($('#skip_fill_rekening').is(':checked')){
                $('#bank_id').prop('required', false);
                $('#account_number').prop('required', false);
                $('#account_name').prop('required', false);
                $('#branch_name').prop('required', false);
            } else {
                $('#bank_id').prop('required', true);
                $('#account_number').prop('required', true);
                $('#account_name').prop('required', true);
                $('#branch_name').prop('required', true);
            }
        });
        // if img ktp is available, hit ocr and assign the img ktp as file input
        
        if ($('#file_ktp_preview').attr('src') && $('#file_ktp_preview').attr('src') != '#'){
            hitOcr(true);
        }
        // in case whitelist is changed, trigger ocr again
        $('#isWhitelist').change(function(){
            if ($('#isWhitelist').val() == 0){
                hitOcr();
            } else {
                $('#preview_ocr_button').hide();
            }
        });

        $(document).on('click', '#cancel_details', function(event) {
            event.preventDefault();
            // close modal
            $('#ktpDetailsModal').modal('hide');
        });

        $(document).on('click', '#change_details', function(event) {
            event.preventDefault();
            let nik = $('#nikModal').val();
            let name = $('#nameModal').val();
            let birthdate = $('#birthdateModal').val();
            $('#nik').val(nik);
            $('#identity_name').val(name);
            $('#birthdate').val(birthdate);
            document.getElementById('ktp_no').value = nik;
            if (nik != "0000000000000000" && $('#is_whitelist').val() != 1){
                checkKtpAvailability();
            }
            // Reset error messages
            $('#nik_error').text('');
            $('#name_error').text('');
            $('#birthdate_error').text('');

            // Validate NIK
            if (!(/^\d{16}$/.test(nik))) {
                $('#nik_error').text('NIK must be exactly 16 digits and numbers only');
                return;
            }

            // Validate Name
            if (!(/^[a-zA-Z .,']{1,250}$/.test(name))) {
                $('#name_error').text('Name must contain only alphabetical characters and spaces, max 250 characters');
                return;
            }

            // Validate Birth Date format
            if (!(/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/.test(birthdate))) {
                $('#birthdate_error').text('Invalid birth date format. Please use mm/dd/yyyy');
                return;
            }

            swal({
                title: 'KTP details has been changed',
                icon: 'success',
                confirmButtonText: 'OK'
            });

            $('#nik_error').text('');
            $('#name_error').text('');
            $('#birthdate_error').text('');

            // hide modal
            $('#ktpDetailsModal').modal('hide');
        });

        
        // mainform is either form_add or form_edit, check which one is available
        if ($('#form_add').length){
            mainForm = $('#form_add');
        } else if ($('#form_edit').length){
            mainForm = $('#form_edit');
        }

        // intercept main form submit, check if non whitelist and ocr data is not available then prevent submit
        $(mainForm).submit(function(event){
            event.preventDefault();
            let buttonPressed = $(document.activeElement).attr('id');
            submitFinalForm(buttonPressed);
        });

        async function advFormValidation() {
            if (!mainForm[0].checkValidity()) {
                mainForm[0].reportValidity();
                return false;
            }
            
            $('#phone_error').remove();
            $('#ktp_no_error').remove();
            $('#file_ktp_error').remove();
            $('#file_selfie_error').remove();
            $('#ocr_error').remove();

            if ($('#contact_no').val().length < 8 || $('#contact_no').val().length > 14) {
                $('#contact_no').after('<span id="phone_error" class="error" style="color: red;">Phone length must be between 8 and 14 numbers</span>');
                $('#contact_no').focus();
                return false;
            }

            if ($('#ktp_no').val().length != 16) {
                $('#ktp_no').after('<span id="ktp_no_error" class="error" style="color: red;">NIK must be exactly 16 digits</span>');
                $('#ktp_no').focus();
                return false;
            }

            if (fileKTP) {
                if (!fileKTP.type.match('image.*')) {
                    $('#file_ktp').after('<span id="file_ktp_error" class="error" style="color: red;">Please upload a valid KTP file.</span>');
                    $('#file_ktp').focus();
                    return false;
                }

                let realSize = fileKTP.size / 1024;
                if (realSize < 100 || realSize > 2048) {
                    $('#file_ktp').after('<span id="file_ktp_error" class="error" style="color: red;">File size must be between 100KB and 2MB</span>');
                    $('#file_ktp').focus();
                    return false;
                }

                try {
                    await validateImage(fileKTP, 640, 480, 'file_ktp', 'Image dimensions must be at least 640x480 pixels');
                } catch (error) {
                    $('#file_ktp').focus();
                    return false;
                }
            }

            if (fileSelfie) {
                if (!fileSelfie.type.match('image.*')) {
                    $('#file_selfie').after('<span id="file_selfie_error" class="error" style="color: red;">Please upload a valid Face Photo file.</span>');
                    $('#file_selfie').focus();
                    return false;
                }

                let realSize = fileSelfie.size / 1024;
                if (realSize < 100 || realSize > 4096) {
                    $('#file_selfie').after('<span id="file_selfie_error" class="error" style="color: red;">File size must be between 100KB and 4MB</span>');
                    $('#file_selfie').focus();
                    return false;
                }

                try {
                    await validateImage(fileSelfie, 640, 480, 'file_selfie', 'Image dimensions must be at least 640x480 pixels');
                } catch (error) {
                    $('#file_selfie').focus();
                    return false;
                }
            }

            if ($('#isWhitelist').val() == 0 && !$('#nik').val() && !$('#identity_name').val() && !$('#birthdate').val()) {
                $('#file_ktp').after('<span id="ocr_error" class="error" style="color: red;">Please upload a valid KTP file.</span>');
                $('#file_ktp').focus();
                return false;
            }

            return true;
        }

        function validateImage(file, minWidth, minHeight, fieldId, errorMsg) {
            return new Promise((resolve, reject) => {
                let img = new Image();
                img.src = window.URL.createObjectURL(file);
                img.onload = function () {
                    if (img.width < minWidth || img.height < minHeight) {
                        $(`#${fieldId}`).after(`<span class="error" style="color: red;">${errorMsg}</span>`);
                        reject(new Error(errorMsg));
                    } else {
                        resolve();
                    }
                };
                img.onerror = function () {
                    $(`#${fieldId}`).after('<span class="error" style="color: red;">Unable to load image</span>');
                    reject(new Error('Unable to load image'));
                };
            });
        }

        function internalBlacklistError(selector, message) {
            const inputField = $(selector);
            inputField.addClass('is-invalid');
            const errorContainer = inputField.next('.invalid-feedback');
            errorContainer.html(message);
            errorContainer.show();
        }

        async function submitFinalForm(buttonPressed){
            // validate form
            if (await advFormValidation() == false){
                return;
            }
            $('body').after('<div class="loading"></div>');
            // add bootstrap spinner to the loading spinner overlay
            $('.loading').append('<div class="spinner-border text-primary" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
            
            mainForm.append('<input type="hidden" name="from_cms" value="true">');
            // Submit the form using AJAX
            $.ajax({
                url: $(mainForm).attr('action'),
                type: 'POST',
                data: new FormData(mainForm[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    // Hide the loading spinner overlay
                    $('#account_number').removeClass('is-invalid');
                    $('#account_number').removeClass('.invalid-feedback');
                    $('#ktp_no').removeClass('is-invalid');
                    $('#ktp_no').removeClass('.invalid-feedback');
                    $('.loading').remove();
                    if(buttonPressed === 'save-and-create-another') {
                        swal({
                            title: "Created Successfully",
                            text: response.message,
                            icon: "success",
                        });
                    } else {
                        swal({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                        }).then((value) => {
                            window.location.href = response.redirect;
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Hide the loading spinner overlay
                    $('#account_number').removeClass('is-invalid');
                    $('#account_number').removeClass('.invalid-feedback');
                    $('#ktp_no').removeClass('is-invalid');
                    $('#ktp_no').removeClass('.invalid-feedback');
                    $('.loading').remove();
                    // if status_code is 422, and it has message, show the message
                    let response_message = "An error occurred while processing the request. Please try again later."
                    if (xhr.status == 422 || xhr.status == 400){
                        let response = xhr.responseJSON;

                        if (response.message){
                            response_message = response.message;
                        }
                    }
                    if (xhr.status === 409) {
                        const response = xhr.responseJSON;
                        console.log(response);
                        if (response.ktp_blacklist) {
                            internalBlacklistError('#ktp_no', response.ktp_blacklist.status_message);
                        }
                        if (response.cek_rekening) {
                            internalBlacklistError('#account_number', response.cek_rekening.status_message);
                        } 
                        return;
                    }

                    swal({
                        title: xhr.status == 422 ? "Validation Error" : "Error",
                        text: response_message,
                        icon: "error",
                    });
                }
            });
        }
    });
</script>