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
                    <span class="breadcrumb-item active">@lang('cms.Detail')</span>
                </div>

                {{-- <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a> --}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <form method="POST" id="form_edit" action="{{ route('yukk_co.customers.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
        <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3>@lang('cms.General Information')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="hidden-2" class="w-100">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="name">@lang('cms.Merchant Label')</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        placeholder="@lang('cms.Merchant Label')" value="{{ $item->name }}"
                                                        autofocus data-rule-maxlength="100"
                                                        data-msg-maxlength="Maximum 100 characters please" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="status">@lang('cms.Status')</label>
                                                <div class="col-lg-8">
                                                    <select class="form-control status" id="status" name="status" disabled>
                                                        <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                                                            @lang('cms.Active')</option>
                                                        <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>
                                                            @lang('cms.Inactive')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="email">@lang('cms.Billing Address')</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" name="address" id="address" placeholder="@lang('cms.Billing Address')" disabled>{{ $item->address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="email">@lang('cms.Disbursement Email')</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" name="email" id="email" placeholder="@lang('cms.Disbursement Email')" disabled>{{ $item->email }}</textarea>

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
            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>@lang('cms.Bank Acct Disbursement')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="bank_id">@lang('cms.Bank Name')</label>
                                            <div class="col-lg-8">
                                                <select id="bank_id" name="bank_id" class="form-control select2"
                                                    required disabled>
                                                    @foreach ($banks as $value)
                                                        <option value="{{ $value->id }}"
                                                            {{ $item->bank_id == $value->id ? 'selected' : '' }}>
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
                                                    class="form-control" placeholder="@lang('cms.Bank Branch Name')"
                                                    value="{{ $item->branch_name }}" required autofocus
                                                    data-rule-maxlength="100"
                                                    data-msg-maxlength="Maximum 100 characters please" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                   for="account_number">@lang('cms.Account Number')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="account_number" name="account_number"
                                                       class="form-control" placeholder="@lang('cms.Account Number')"
                                                       value="{{ $item->account_number }}" required autofocus
                                                       data-rule-maxlength="100"
                                                       data-msg-maxlength="Maximum 100 digits please" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="account_name">@lang('cms.Account Name')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="account_name" name="account_name"
                                                    class="form-control" placeholder="@lang('cms.Account Name')"
                                                    value="{{ $item->account_name }}" required autofocus
                                                    data-rule-maxlength="100"
                                                    data-msg-maxlength="Maximum 100 characters please" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="bank_type">@lang('cms.Bank Type')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="bank_type_name" name="bank_type"
                                                    class="bank_type form-control" value="{{ $item->bank_type }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="disbursement_fee">@lang('cms.Disbursement Fee')</label>
                                            <div class="col-lg-8">
                                                <input type="number" id="disbursement_fee" name="disbursement_fee"
                                                    class="form-control" placeholder="@lang('cms.Disbursement Fee')"
                                                    value="{{ explode(".", $item->disbursement_fee)[0] }}" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="auto_disbursement_interval">@lang('cms.Disbursement Interval')</label>
                                            <div class="col-lg-8">
                                                <select id="auto_disbursement_interval" name="auto_disbursement_interval"
                                                    class="form-control" disabled>
                                                    @if($item->auto_disbursement_interval == "PAYOUT_BY_REQUEST" || $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST_PARTNER")
                                                        <option value="PAYOUT_BY_REQUEST" {{ $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST" ? 'selected' : '' }}>@lang('cms.Payout By Request')</option>
                                                        <option value="PAYOUT_BY_REQUEST_PARTNER" {{ $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST_PARTNER" ? 'selected' : '' }}>@lang('cms.Payout By Request Partner')</option>
                                                    @endif
                                                    <option value="DAILY" {{ $item->auto_disbursement_interval == "DAILY" ? 'selected' : '' }}>@lang('cms.Daily')</option>
                                                    <option value="WEEKLY" {{ $item->auto_disbursement_interval == "WEEKLY" ? 'selected' : '' }}>@lang('cms.Weekly')</option>
                                                    <option value="ON_HOLD" {{ $item->auto_disbursement_interval == "ON_HOLD" ? 'selected' : '' }}>@lang('cms.On_Hold')</option>
                                                </select>
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

        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <a href="{{ route('yukk_co.customers.list') }}" class="btn btn-block btn-warning">
                            @lang('cms.Go Back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- <div class="col-sm-12 col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('cms.Payment Channel List')</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('cms.Name')</th>
                                    <th class="text-center">@lang('cms.Status')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach (@$partner->payment_channel_list as $index => $payment_channel)
                                <tr>
                                    <td>{{ @$payment_channel->payment_channel->name }}</td>
                                    <td class="text-center">
                                        @if (@$payment_channel->active)
                                            <i class="icon-checkmark text-success"></i>
                                        @else
                                            <i class="icon-cross2 text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js">
    </script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $('#form_edit').validate({
                rules: {
                    email: function(input) {
                        validate_email(input)
                    },
                    file_ktp: {
                        extension: "jpg,png,JPG,jpeg,JPEG",
                        filesize_max: 2048000
                    },
                    file_npwp: {
                        extension: "jpg,png,JPG,jpeg,JPEG",
                        filesize_max: 2048000
                    },
                    disbursement_fee: {
                        required: true,
                        integer: true,
                        range: [0, 1000000]
                    }
                },
                messages: {
                    disbursement_fee: 'The disbursement fee format is invalid',
                    file_ktp: {
                        required: "This field is required. Choose Again",
                        filesize_max: "File size must be equal or less than 2 MB.",
                    },
                    file_npwp: {
                        required: "This field is required. Choose Again",
                        filesize_max: "File size must be equal or less than 2 MB.",
                    },
                },
                submitHandler: function(form) {
                    console.log(form)
                    if (validate_email("email")) return true
                    else return false
                }
            });
            // Membuat metode kustom untuk validasi ukuran file
            $.validator.addMethod('filesize_max', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, 'File size must be equal or less than 2 MB.');

            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $('#bank_id').on('change', function(e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                if (valueSelected == 1) {
                    $("#bank_type_name").val('BCA');
                } else {
                    $("#bank_type_name").val('NON-BCA');
                }

            });
        });

        function inputPreviewImage(input, target) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(target).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#file_ktp").change(function() {
            inputPreviewImage(this, "#file_ktp_preview");
        });
        $("#file_npwp").change(function() {
            inputPreviewImage(this, "#file_npwp_preview");
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
