b<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Global Variables
    let kymMessage = "";
    let kymCode = "";
    let fileKTP = null;
    let blob, filename, fileSelfie, selfieBlob, selfieFilename, selfieImageData, imageData;
    let fileKTPChanged = false;
    let mainForm = null;

    // Constants
    const OCR_ENDPOINT = "{{ route('yukk_co.owners.scan.ktp') }}";
    const IMAGE_FETCH_ENDPOINT = "{{ route('yukk_co.owners.image') }}";
    const KTP_CHECK_ENDPOINT = "{{ route('yukk_co.customers.check_ktp') }}";

    // Image Handling
    async function fetchImage(imageData, filename = null, imageType = 'image') {
        try {
            const encodedImageData = encodeURIComponent(imageData);
            const finalUrl = `${IMAGE_FETCH_ENDPOINT}?url=${encodedImageData}`;
            const response = await fetch(finalUrl);

            if (!response.ok) throw new Error(`Request failed with status: ${response.status}`);

            const contentType = response.headers.get('Content-Type');
            if (!contentType.startsWith('image/')) throw new Error('The response is not an image.');

            const blob = await response.blob();
            if (!filename) {
                const decodedImageData = decodeURIComponent(imageData);
                filename = decodedImageData.split('/').pop().split('?')[0].split('_').slice(1).join('_');
            }

            if (blob) {
                const file = new File([blob], filename, { type: "image/png", lastModified: new Date().getTime() });
                const container = new DataTransfer();
                container.items.add(file);
                document.getElementById(imageType).files = container.files;
            }

            return { blob, filename };
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

    // OCR Processing
    async function hitOcr(keepDownloadButton = false) {
        if ($('#is_whitelist').val() == 1) return;

        resetOCRFields();

        const fileKTP = $('#file_ktp')[0].files[0];
        const fileSelfie = $('#file_selfie')[0].files[0];
        const imageData = $('#file_ktp_preview').attr('src');
        const selfieImageData = $('#file_selfie_preview').attr('src');

        if (!fileKTP && !imageData) {
            swal("Please select a file or upload an image.");
            return;
        }

        const ktpResult = await fetchKTP(imageData);
        if (ktpResult) {
            blob = ktpResult.blob;
            filename = ktpResult.filename;
        }

        if (!fileSelfie && !selfieImageData) {
            $('#file_selfie').after('<span id="file_selfie_ocr_error" class="error" style="color: red;">Please upload a valid Selfie file.</span>');
        } else {
            const selfieResult = await fetchSelfie(selfieImageData, selfieFilename);
            if (selfieResult) {
                selfieBlob = selfieResult.blob;
                selfieFilename = selfieResult.filename;
            }
        }

        const formData = new FormData();
        formData.append('file_ktp', blob || fileKTP);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('requestId', '{{ isset($item) ? $item->id : '' }}');
        formData.append('filename', filename);
        formData.append('targetType', 'CUSTOMER');

        showLoadingSpinner();

        $.ajax({
            url: OCR_ENDPOINT,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: handleOCRSuccess,
            error: handleOCRError
        });
    }

    function handleOCRSuccess(response) {
        hideLoadingSpinner();
        console.log(response);
        $('#preview_ocr_button').show();
        if (!response.result) return;

        if (response.status_message !== "success extract ktp") {
            resetIfError(response.result.message);
            return;
        }

        updateFormFields(response.result);
        initiatePreviewButton();
    }

    function handleOCRError(xhr, status, error) {
        console.log(xhr,status,error);
        hideLoadingSpinner();
        resetIfError();
    }

    function resetOCRFields() {
        blob = filename = selfieBlob = selfieFilename = null;
        $('#ocr_error, #ocr_details').remove();
        $('#ocr_details').hide();
    }

    function updateFormFields(result) {
        $('#nikModal').val(result.nik);
        $('#id_card_number').val(result.nik);
        $('#nameModal').val(result.full_name);
        $('#identity_name').val(result.full_name);
        $('#nik').val(result.nik);

        var parts = result.date_of_birth.split("-");
        var birthDate = parts[1] + '/' + parts[0] + '/' + parts[2];        
        $('#birthdateModal').val(birthDate);
        $('#birthdate').val(birthDate);

        $('#file_ktp_preview').show();
        $('#removeFileKtp').show();
    }

    // Form Validation
    async function advFormValidation() {
        if (!mainForm[0].checkValidity()) {
            mainForm[0].reportValidity();
            return false;
        }

        validatePhoneNumber();
        validateKTPNumber();
        await validateFile(fileKTP, 'file_ktp', 100, 2048, 'KTP');
        await validateFile(fileSelfie, 'file_selfie', 100, 4096, 'Face Photo');

        if ($('#isWhitelist').val() == 0 && !$('#nik').val()) {
            $('#file_ktp').after('<span id="ocr_error" class="error" style="color: red;">Please upload a valid KTP file.</span>');
            return false;
        }

        return true;
    }

    function validatePhoneNumber() {
        const phone = $('#contact_no').val();
        if (phone.length < 8 || phone.length > 14) {
            $('#contact_no').after('<span id="phone_error" class="error" style="color: red;">Phone length must be between 8 and 14 numbers</span>');
            return false;
        }
        return true;
    }

    function validateKTPNumber() {
        const ktp = $('#id_card_number').val();
        if (ktp.length !== 16) {
            $('#id_card_number').after('<span id="ktp_no_error" class="error" style="color: red;">NIK must be exactly 16 digits</span>');
            return false;
        }
        return true;
    }

    async function validateFile(file, fieldId, minSizeKB, maxSizeKB, fieldName) {
        if (!file) return true;

        if (!file.type.match('image.*')) {
            $(`#${fieldId}`).after(`<span id="${fieldId}_error" class="error" style="color: red;">Please upload a valid ${fieldName} file.</span>`);
            return false;
        }

        const realSize = file.size / 1024;
        if (realSize < minSizeKB || realSize > maxSizeKB) {
            $(`#${fieldId}`).after(`<span id="${fieldId}_error" class="error" style="color: red;">File size must be between ${minSizeKB}KB and ${maxSizeKB}KB</span>`);
            return false;
        }

        return true;
    }

    // Utility Functions
    function showLoadingSpinner() {
        $('body').after('<div class="loading"></div>');
        $('.loading').append('<div class="spinner-border text-primary justify-content-center" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
    }

    function hideLoadingSpinner() {
        $('.loading').remove();
    }

    function resetIfError(message = null) {
        $('#nik, #identity_name, #birthdate').val('');
        $('#ocr_details').hide();
        $('#preview_ocr_button, #removeFileKtp').hide();
        showOCRError(message);
    }

    function showOCRError(message) {
        Swal.fire({
            title: "OCR Error",
            text: message || "Data reading is not successful. Please retry the file upload.",
            icon: "error",
            button: "OK",
        });
        $('#file_ktp').after(`<span id="ocr_error" class="error" style="color: red;">${message}</span>`);
    }

    // Event Handlers
    $(document).ready(function () {
        initializeEventHandlers();
        if ($('#file_ktp_preview').attr('src')) hitOcr(true);
    });

    function initializeEventHandlers() {
        $('#preview_ocr_button').click(showOCRDetails);
        $('#file_ktp').change(onFileKtpChanged);
        $('#status').change(checkKtpAvailability);
        $('#skip_fill_rekening').change(toggleBankFields);
        $('#isWhitelist').change(toggleOCR);
        $(mainForm).submit(handleFormSubmit);
    }

    function showOCRDetails(event) {
        event.preventDefault();
        $('#ocr_details').show();
        $('#ktpDetailsModal').modal('show');
    }

    function onFileKtpChanged(event) {
        fileKTPChanged = true;
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $('#file_ktp_preview').attr('src', e.target.result).show();
                $('#removeFileKtp').show();
            };
            reader.readAsDataURL(file);
            if ($('#isWhitelist').val() != 1) hitOcr();
        } else {
            $('#file_ktp_preview').hide();
            $('#removeFileKtp').hide();
        }
    }

    function toggleBankFields() {
        const isChecked = $('#skip_fill_rekening').is(':checked');
        $('#bank_id, #account_number, #account_name, #branch_name').prop('required', !isChecked);
    }

    function toggleOCR() {
        if ($('#isWhitelist').val() == 0) hitOcr();
        else $('#preview_ocr_button').hide();
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        if (!await advFormValidation()) return;

        showLoadingSpinner();
        mainForm.append('<input type="hidden" name="from_cms" value="true">');

        $.ajax({
            url: mainForm.attr('action'),
            type: 'POST',
            data: new FormData(mainForm[0]),
            processData: false,
            contentType: false,
            success: handleFormSuccess,
            error: handleFormError
        });
    }

    function handleFormSuccess(response) {
        hideLoadingSpinner();
        swal({
            title: "Success",
            text: response.message,
            icon: "success",
        }).then(() => {
            window.location.href = response.redirect;
        });
    }

    function handleFormError(xhr) {
        hideLoadingSpinner();
        const message = xhr.responseJSON?.message || "An error occurred while processing the request.";
        swal({
            title: xhr.status == 422 ? "Validation Error" : "Error",
            text: message,
            icon: "error",
        });
    }   

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
        document.getElementById('id_card_number').value = nik;
        // if (nik != "0000000000000000"){
        //     checkKtpAvailability();
        // }
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
</script>