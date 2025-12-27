$(document).on('click', '.page-content .dropdown-menu', function (e) {
    e.stopPropagation();
});

$('#perPageDropdown').change(function(e) {
    resetUrl()
    $('#form').submit()
})

$('#date_filter').on('change', function() {
    if($('#date_filter').is(':checked')) {
        $('.dates_by').css('display', 'block');
    } else {
        $('input[type="date"]').val('');
        $('input[name="dates_by"]').prop('checked', false);
        $('#dates_by_created_at').prop('checked', true);
        $('.dates_by').css('display', 'none');
    }
});

$('#date_filter').on('change', function() {
    $('#dates_by_submenu').css('display', 'block');
});

$('#tags').on('change', function() {
    if($('#tags').is(':checked')) {
        $('.tags').css('display', 'block');
    } else {
        $('input[name="tags[]"]').prop('checked', false);
        $('.tags').css('display', 'none');
    }
});

$('#tags_all').on('click', function(e) {
    $('input[name="tags[]"]').prop('checked', true);
});

$('#providers').on('change', function() {
    if($('#providers').is(':checked')) {
        $('.providers').css('display', 'block');
    } else {
        $('input[name="providers[]"]').prop('checked', false);
        $('.providers').css('display', 'none');
    }
});

$('#providers_all').on('click', function(e) {
    $('input[name="providers[]"]').prop('checked', true);
});

$('.filter-clear').on('click', function(e) {
    var filter = $(this).data('filter');

    if(filter == 'dates') {
        $('input[type="date"]').val('');
        $('input[name="dates_by"]').prop('checked', false);
        $('#dates_by_created_at').prop('checked', true);
        $('.dates_by').css('display', 'none');
    } else {
        $('input[name="' + filter + '[]"]').prop('checked', false);
        $('.' + filter).css('display', 'none');
    } 

    resetUrl()
    $('#form').submit()
});

$('#delete_all_filters').on('click', function(e) {
    window.location.href = $('.btn-reset').attr('href');
});

$('#btn-apply-filter').on('click', function(e) {
    if ($('#date_filter').is(':checked')
        && ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '')) {
        swal('error', 'Date columns not complete');

        return; 
    }

    if ($('#tags').is(':checked') && ! $('input[name="tags[]"]').is(':checked')) {
        swal('error', 'Choose at least one tag');

        return; 
    }

    if ($('#providers').is(':checked') && ! $('input[name="providers[]"]').is(':checked')) {
        swal('error', 'Choose at least one provider');

        return; 
    }

    $(this).text("Loading...");
    $(this).prop('disabled', true);

    resetUrl()
    $('#form').submit();
})

$('#btn-search').on('click', function(e) {
    $(this).text("Loading...");
    $(this).prop('disabled', true);

    resetUrl()
    $('#form').submit()
})

$("#searchclear").click(function(){
    $("#searchinput").val('');
});

$('input[name="end_date"]').on('change', function(e) {
    const startDateEl = $('input[name="start_date"]');

    if (startDateEl.val() == '') {
        swal('warning', 'Choose start date first');

        $(this).val('');
    }

    const startDate = new Date(startDateEl.val());
    const endDate = new Date($(this).val());

    if (startDate > endDate) {
        swal('error', 'End date must be greater than start date');
        $(this).val('');
    }
});

$('input[name="start_date"]').on('change', function(e) {
    $('input[name="end_date"]').val('')
});

$('#btn-export').on('click', function(e) {
    $('#export').val(1);

    $('#form').prop('action', exportAction)

    $('#form').submit()

    swal('success', 'The export request is being processed please wait');
});

$(".bulkAction").change(function() {
    var code = $(this).data('code');
    var providerCode = $('.cb-providers:checkbox:checked').data('code');

    if($(this).is(':checked')) {
        saveCode(providerCode, code)
        $('#selectedTransactions').append('<input type="hidden" id="tran-'+code+'" name="selectedCodes[]" value="'+code+'">')
        setCheckAllValue();
    } else {
        $('#tran-'+code).remove();
        removeCode(providerCode, code)
        setCheckAllValue();
    }

    setDisabledActionButton();
});

$("#checkedAll").change(function(e) {
    var elements = $(".bulkAction");
    var providerCode = $('.cb-providers:checkbox:checked').data('code');

    if($(this).is(':checked')) {
        elements.each(function (index, item) {
            let code = $(item).data('code');
            saveCode(providerCode, code);
            $('#selectedTransactions').append('<input type="hidden" id="tran-'+code+'" name="selectedCodes[]" value="'+code+'">');

            if ($('#cb-'+ code)) {
                $('#cb-'+ code).prop('checked', true);
            }
        });
    
    } else {
        elements.each(function (index, item) {
            let code = $(item).data('code');
            
            if ($('#cb-'+ code)) {
                $('#cb-'+ code).prop('checked', false);
            }

            $('#tran-'+code).remove();
            removeCode(providerCode, code)
        });
    }

    setDisabledActionButton();
});

const btnListIdForModalWarning = ["btnUpdate", "btnRetry", "btnSuccess", "btnFailed"];

$("#" + btnListIdForModalWarning.join(",#")).click(function(){
    setDataModal($(this).data('label'), $(this).data('action'));
});

$('#formWarningModal').submit(function() {
    localStorage.removeItem('YKK.MT.CODE.'+ $('.cb-providers:checkbox:checked').data('code'));

    $(this).find(':button[type=submit]').prop('disabled', true);
    $(this).find(':button[type=submit]').html('Loading..');
})