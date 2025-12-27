@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>Payment Link Import</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.merchant_branches.payment_link.list') }}" class="breadcrumb-item">Payment Link
                        List</a>
                    <span class="breadcrumb-item active">Import</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a href="{{ route('cms.merchant_branches.payment_link.import.template') }}">
                                    <button style="margin-left:10px; float:right" class="btn btn-info form-control">
                                        Download Template</button>
                                </a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a href="{{ route('cms.merchant_branches.payment_link.bank-code') }}">
                                    <button style="margin-left:10px; float:right" class="btn btn-warning-100 form-control">
                                        List of Bank Code</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form-import-payment-link" class="row" enctype="multipart/form-data"
                onsubmit="event.preventDefault()">
                <input type="hidden" name="merchant_branch_id" value="{{ @$payment_link->merchant_branch_id }}">
                <div class="col-md-12 mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="file" name="file_import" class="form-control py-1" style="height: 40px;">
                            <div class="mt-2">*If expiration date is not set, default H+1</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-5 text-right">
                            <button onclick="submitForm()" class="btn btn-primary btn-right form-control"
                                style="float:right; width:90px">
                                Submit</button>
                            <button onclick="goBack()" class="btn btn-primary btn-right form-control"
                                style="float:right; width:90px; margin:0 10px"> Back
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function submitForm() {
            let is_valid = true
            var formData = new FormData(document.getElementById('form-import-payment-link'))
            formData.set('_token', '{{ csrf_token() }}')
            if (formData.get('file_import').name == '') {
                showAlert('error', 'Failed',
                    'please insert file before submitting!');
                return
            }
            $.ajax({
                url: "{{ route('cms.merchant_branches.payment_link.submit.import') }}",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    showAlert('success', 'Success',
                        'Payment link imported successfully, please check your email for result!');

                    window.location.href = data.url ? data.url : "#"
                },
                error: function(data) {
                    if (data.status == 400 && data.responseJSON.error_data != null) {
                        const errors = data.responseJSON.error_data
                        let message = '';
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                message = message.concat(`${errors[key]}\n`);
                            }
                        }
                        showAlert('error', 'Failed',
                            message);
                    } else {
                        showAlert('error', 'Failed',
                            data.responseJSON.error_message);
                    }
                }
            });
        }

        function showAlert(type, title, message) {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000 // Duration for the alert to be visible (in milliseconds)
            });
        }

        function goBack() {
            var url = "{{ route('cms.merchant_branches.payment_link.list') }}"
            window.location.href = url
        }
    </script>
@endsection
