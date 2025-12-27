function swal(type, msg) {
    Swal.fire({
        'text': msg,
        'icon': type,
        'toast': true,
        'timer': 5000,
        'showConfirmButton': false,
        'position': 'top-right',
    });
}

function setDataModal(btnLabel, route) {
    $("#actionBody").html(btnLabel);
    $("#actionBtn").html(btnLabel);
    $("#formWarningModal").attr('action', route);
    $("#inputRetry").val(btnLabel == 'Retry' ? 1 : 0);
    $("#inputSuccess").val(btnLabel == 'Mark As Success' ? 1 : 0);
    $("#inputFailed").val(btnLabel == 'Mark As Failed' ? 1 : 0);
}