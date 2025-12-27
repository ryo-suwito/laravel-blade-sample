function resetUrl() {
    if($('#form').prop('action') == exportAction) {
        $('#export').val(0);
        $('#form').prop('action', indexAction)
    }
}

function saveCode(provider, code) {
    var cacheName = 'YKK.MT.PRX.' + provider;

    if (localStorage.getItem(cacheName) == null) {
        localStorage.setItem(cacheName, code);
    } else {
        var codes = localStorage.getItem(cacheName);
        localStorage.setItem(cacheName, codes + ',' + code);
    }
}

function removeCode(provider, code) {
    var cacheName = 'YKK.MT.PRX.' + provider;

    if (localStorage.getItem(cacheName) != null) {
        var codes = localStorage.getItem(cacheName);
        codes = codes.split(',');

        codes = codes.filter(function (c) {
                return c !== code;
        })
        
        if (codes.length == 0) {
            localStorage.removeItem(cacheName);
            return;
        }

        localStorage.setItem(cacheName, codes.toString());
    }
}

function selectingCodes() {
    var cacheName = 'YKK.MT.PRX.' + $('.cb-providers:checkbox:checked').data('code');

    if (localStorage.getItem(cacheName) != null) {
        var codes = localStorage.getItem(cacheName);
        codes = codes.split(',');

        codes.forEach(function(code, index) {
            $('#selectedTransactions').append('<input type="hidden" id="tran-'+code+'" name="selectedCodes[]" value="'+code+'">');
            if ($('#cb-'+ code)) {
                $('#cb-'+ code).prop('checked', true);
            }
        })

        // Check full select or not
        setCheckAllValue();
    }
}

function setCheckAllValue() {
    var isChecked = $('.bulkAction:checkbox:not(:checked)').length > 0 || $('.bulkAction').length == 0;
    $('#checkedAll').prop('checked', isChecked > 0 ? false : true);
}

function setDisabledActionButton() {
    var provider = $('.cb-providers:checkbox:checked').data('code');

    if (localStorage.getItem('YKK.MT.PRX.' + provider) != null) {
        $('.btn-action-' + provider).prop('disabled', false);
    } else {
        $('.btn-action-' + provider).prop('disabled', true);
    }
}