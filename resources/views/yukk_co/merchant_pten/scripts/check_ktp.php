<script>
function checkKtpAvailability(customer_id, checkKtpUrl){
    let customerIdInput = $('#customer_id');
    $.ajax({
        url: checkKtpUrl,
        type: "GET",
        data: {
            customer_id: customer_id
        },
        success: function(response) {
            $('#customerIdInput_error').remove();
            let result = response.result;
            if (result && result.validation_result !== true) {
                customerIdInput[0].setCustomValidity(result.message);
                $('#customer_id').after('<span id="customerIdInput_error" class="error" style="color: red;">'+result.message+'</span>');
                $("#btn-save-beneficiary").attr("disabled", true);
                $("#btn-submit-to-pten").attr("disabled", true);
            } else {
                customerIdInput[0].setCustomValidity('');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error occurred while checking KTP: " + error);
        }
    })
}
</script>