@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{-- <h4><span class="font-weight-semibold">Seed</span> - Static layout</h4> --}}
                <h4>@lang('cms.Beneficiary')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{-- <button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button> --}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.customers.list') }}" class="breadcrumb-item">@lang('cms.Beneficiary')</a>
                    <span class="breadcrumb-item active">@lang('cms.Add New')</span>
                </div>

                {{-- <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a> --}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <style>
        .buttonX{
            margin-right: 10px; 
            cursor: pointer
        }
        .buttonXFloating{
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px;
            background-color: #333;
            border-radius: 50%;
            color : #fff;
            margin-right: 10px; 
            cursor: pointer
        }
        .textLeft{
            text-align: left;
        }
        .mbrem5{
            margin-bottom: 0.5rem;
        }

    </style>
    <form method="POST" id="form_add" action="{{ route('yukk_co.customers.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="status" value="1">
        <div class="row">

        <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>@lang('cms.General Information')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="hidden-2" class="w-100">
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="name">@lang('cms.Merchant Label')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="name" name="name" class="form-control"
                                                            placeholder="@lang('cms.Name')" value="{{ (!$is_whitelist) ? old('name') : request()->input('name') }}" required
                                                            autofocus data-rule-maxlength="100"
                                                            data-msg-maxlength="Maximum 100 characters please">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="email">@lang('cms.Billing Address')</label>
                                                    <div class="col-lg-8">
                                                        <textarea class="form-control" name="address" id="address" placeholder="@lang('cms.Billing Address')" required>{{ old('address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="email">@lang('cms.Disbursement Email')</label>
                                                    <div class="col-lg-8">
                                                        <textarea class="form-control" name="email" id="email" placeholder="@lang('cms.Disbursement Email')" required>{{ old('email') }}</textarea>

                                                        <small>Multiple email, please use comma separate</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>@lang('cms.Bank Acct Disbursement')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="hidden-2" class="w-100">
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="bank_id">@lang('cms.Bank Name')</label>
                                                    <div class="col-lg-8">
                                                        <select id="bank_id" name="bank_id" class="form-control select2 track-input"
                                                                required>
                                                            <option value="">Select Bank</option>
                                                            @foreach ($banks as $value)
                                                                <option value="{{ $value->id }}" data-bank-type="{{ $value->bank_type }}"
                                                                    {{ ( (!$is_whitelist) ? old('bank_id') : request()->input('bank_id') ) == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="branch_name">@lang('cms.Bank Branch Name')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="branch_name" name="branch_name"
                                                            class="form-control track-input" placeholder="@lang('cms.Bank Branch Name')"
                                                            value="{{ old('branch_name') }}"
                                                            data-rule-maxlength="100"
                                                            data-msg-maxlength="Maximum 100 characters please" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="account_number">@lang('cms.Account Number')</label>
                                                <div class="col-lg-8">
                                                    <input type="number" id="account_number" name="account_number"
                                                        class="form-control track-input" placeholder="@lang('cms.Account Number')"
                                                        value="{{ old('account_number') }}" required
                                                        data-rule-maxlength="100"
                                                        data-msg-maxlength="Maximum 100 digits please" step="1">
                                                    <div class="invalid-feedback alert-box"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="account_name">@lang('cms.Account Name')</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="account_name" name="account_name"
                                                        class="form-control track-input" placeholder="@lang('cms.Account Name')"
                                                        value="{{ old('account_name') }}" required 
                                                        data-rule-maxlength="100"
                                                        data-msg-maxlength="Maximum 100 characters please">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="bank_type">@lang('cms.Bank Type')</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="bank_type_name" name="bank_type"
                                                        class="bank_type form-control" value="@lang('cms.BCA')" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="auto_disbursement_interval">@lang('cms.Disbursement Interval')</label>
                                            <div class="col-lg-8">
                                                <select id="auto_disbursement_interval" name="auto_disbursement_interval"
                                                    class="form-control">
                                                    <option value="DAILY">@lang('cms.Daily')</option>
                                                    <option value="WEEKLY">@lang('cms.Weekly')</option>
                                                    <option value="ON_HOLD">@lang('cms.On_Hold')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="disbursement_fee">@lang('cms.Disbursement Fee')</label>
                                            <div class="col-lg-8">
                                                <input type="number" min="0" id="disbursement_fee"
                                                    name="disbursement_fee" class="form-control"
                                                    placeholder="@lang('cms.Disbursement Fee')"
                                                    value="{{ (!$is_whitelist) ? old('disbursement_fee') : request()->input('disbursement_fee') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('yukk_co.customers.modal.ocr_review')
        <div class="row">
            <div class="col-lg-12">
                <div class="btn-group d-block" style="float:right; margin-top: 20px; margin-bottom: 20px;">
                    <!-- Cancel Button -->
                    <a href="{{ route('yukk_co.customers.list') }}" class="btn btn-warning">
                        @lang('cms.Cancel')
                    </a>
                    <!-- Save and Create Another Button -->
                    <button type="submit" id="save-and-create-another" class="btn btn-info submitBtn">
                        Save and Create Another
                    </button>
                    <!-- Create Button -->
                    <button type="submit" class="btn btn-secondary submitBtn">
                        Save
                    </button>
                    <input type="hidden" name="value_submit" id="value_submit">
                </div>
            </div>
        </div>
    </form>
    
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js">
    </script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            const mainForm = $('#form_add');

            $("#bank_type_name").val($("#bank_id").find(':selected').data('bank-type'));

            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $('#bank_id').on('change', function(e) {
                var self = $(this);
                $("#bank_type_name").val(self.find(':selected').data('bank-type'));
            });

            let oldValues = {};

            $('.track-input').on('input', function() {
                let fieldName = $(this).attr('id') || $(this).attr('name');
                oldValues[fieldName] = $(this).val(); 
            });

            $(mainForm).submit(function(event){
                event.preventDefault();
                let buttonPressed = $(document.activeElement).attr('id');
                submitFinalForm(buttonPressed);
            });

            async function submitFinalForm(buttonPressed){
                showLoadingSpinner();
                
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
                        hideLoadingSpinner();
                        if(buttonPressed === 'save-and-create-another') {
                            swal({
                                title: "Created Successfully",
                                text: response.status_message,
                                icon: "success",
                            }).then((value) => {
                                window.location.href = "{{ route('yukk_co.customers.create') }}";
                            });
                        } else {
                            swal({
                                title: "Success",
                                text: response.status_message,
                                icon: "success",
                            }).then((value) => {
                                window.location.href = "{{ route('yukk_co.customers.detail', ['id' => ':id']) }}".replace(':id', response.result.id);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoadingSpinner();
                        let response_message = "An error occurred while processing the request. Please try again later."
                        if (xhr.status == 422 || xhr.status == 400){
                            let response = xhr.responseJSON;
                            if (response){
                                response_message = response;
                            }
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

        function showLoadingSpinner() {
            $('body').after('<div class="loading"></div>');
            $('.loading').append('<div class="spinner-border text-primary justify-content-center" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
        }

        function hideLoadingSpinner() {
            $('.loading').remove();
        }
    </script>
@endsection
