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
                    <span class="breadcrumb-item active">@lang('cms.Edit')</span>
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
        .is-invalid {
            border: 1px solid #dc3545;
        }
        .is-invalid:focus {
            outline: none;
            border: 3px solid #dc3045;
            box-shadow: 0 0 4px 2px rgba(220, 53, 69, 0.4);
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .alert-box {
            display: none;
            background-color: #ffe6e6;
            border: 1px solid #f5c2c7;
            color: #842029;
            padding: 10px;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 14px;
            line-height: 1.5;
            text-align: left;
        }
    </style>
    <form method="POST" id="form_edit" action="{{ route('yukk_co.customers.update', $item->id) }}" enctype="multipart/form-data">
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
                                        <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="name">@lang('cms.Merchant Label')</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        placeholder="@lang('cms.Merchant Label')" value="{{ $item->name }}"
                                                        autofocus data-rule-maxlength="100"
                                                        data-msg-maxlength="Maximum 100 characters please">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="status">@lang('cms.Status')</label>
                                                <div class="col-lg-8">
                                                    <select class="form-control status" id="status" name="status" required>
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
                                                    <textarea class="form-control" name="address" id="address" placeholder="@lang('cms.Billing Address')" required>{{ old('address') ? old('address') : $item->address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                    for="email">@lang('cms.Disbursement Email')</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" name="email" id="email" placeholder="@lang('cms.Disbursement Email')" required>{{ old('email') ? old('email') : $item->email }}</textarea>

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
                            <div class="card-header"
                                    style="display: flex; justify-content: space-between; align-items: center;">
                                <h3>@lang('cms.Bank Acct Disbursement')</h3>
                                <!-- floating right button "Request change" -->
                                <button type="button" class="btn btn-primary" onclick="requestChange()">Request Change</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="hidden-2" class="w-100">
                                        <!-- bank name -->
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                       for="account_number">@lang('cms.Bank Name')</label>
                                                <div class="col-lg-8">
                                                    <select id="bank_id" name="bank_id" class="form-control select2" disabled>
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $value)
                                                            <option value="{{ $value->id }}" data-bank-type="{{ $value->bank_type }}"
                                                                {{ old('bank_id', $item->bank_id) == $value->id ? 'selected' : '' }}>
                                                                {{ $value->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bank branch name -->
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                       for="account_number">@lang('cms.Bank Branch Name')</label>
                                                <div class="col-lg-8">
                                                    <input type="text"
                                                              class="form-control" placeholder="@lang('cms.Bank Branch Name')"
                                                              value="{{ old('branch_name', $item->branch_name) }}"
                                                              data-rule-maxlength="100"
                                                              data-msg-maxlength="Maximum 100 characters please"
                                                              disabled>
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
                                                           value="{{ old('account_number', $item->account_number) }}"
                                                           data-rule-maxlength="100"
                                                           data-msg-maxlength="Maximum 100 digits please"
                                                           disabled>
                                                    <div class="invalid-feedback alert-box"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label"
                                                       for="account_name">@lang('cms.Account Name')</label>
                                                <div class="col-lg-8">
                                                    <input type="text"
                                                           class="form-control" placeholder="@lang('cms.Account Name')"
                                                           value="{{ old('account_name', $item->account_name) }}"
                                                           data-rule-maxlength="100"
                                                           data-msg-maxlength="Maximum 100 characters please" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="bank_type">@lang('cms.Bank Type')</label>
                                            <div class="col-lg-8">
                                                <input type="text"
                                                    class="bank_type form-control" value="{{ $item->bank_type }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="disbursement_fee">@lang('cms.Disbursement Fee')</label>
                                            <div class="col-lg-8">
                                                <input type="number"
                                                    class="form-control" placeholder="@lang('cms.Disbursement Fee')"
                                                    value="{{ explode('.', $item->disbursement_fee)[0] }}" disabled>
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
                                                    @if ($item->auto_disbursement_interval == "PAYOUT_BY_REQUEST" || $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST_PARTNER")
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
                    <div class="col-sm-6 col-lg-6">
                        <a href="{{ route('yukk_co.customers.list') }}" class="btn btn-block btn-warning">
                            @lang('cms.Cancel')
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <button type="button" onclick="updateFormSubmit(event)" class="btn btn-block btn-secondary">
                            @lang('cms.Update')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- modal form requestChange consisting bank account and disburesement data -->
    <div class="modal" id="modal-request-change" tabindex="-1" role="dialog"
        aria-labelledby="demoModalLabel" aria-hidden="true">
        <div class="modal-content"
            style="width: 620px;top: 0;left: calc(50% - 310px); height:auto">
            <div class="modal-header">
                <h5 class="modal-title" id="changeRequesttitle" tabindex="-1">Change Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" id="contentRequest">
                <form id="bankActForm"method="POST" action="{{ route('yukk_co.customers.update_bank', $item->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="req_id" name="id" value="{{ $item->id }}">
                    <div class="form-group row mbrem5">
                        <label class="col-lg-4 col-form-label textLeft" for="bank_id">Bank Name</label>
                        <div class="col-lg-8">
                            <select id="req_bank_id" name="bank_id" class="form-control select2" 
                                onchange="handleBankChange()" required>
                                <option value="">Select Bank</option>
                                @foreach ($banks as $value)
                                    <option value="{{ $value->id }}" data-bank-type="{{ $value->bank_type }}"
                                        {{ old('bank_id', $item->bank_id) == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- bank branch name -->
                        <label class="col-lg-4 col-form-label textLeft" for="branch_name">Bank Branch Name</label>
                        <div class="col-lg-8">
                            <input type="text" id="req_branch_name" name="branch_name" class="form-control" placeholder="Bank Branch Name"
                                value="{{ old('branch_name', $item->branch_name) }}" required autofocus
                                data-rule-maxlength="100" data-msg-maxlength="Maximum 100 characters please">
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- account number -->
                        <label class="col-lg-4 col-form-label  textLeft" for="account_number">Account Number</label>
                        <div class="col-lg-8">
                            <input type="text" id="req_account_number" name="account_number" class="form-control" placeholder="Account Number"
                                value="{{ old('account_number', $item->account_number) }}" required autofocus
                                data-rule-maxlength="100" data-msg-maxlength="Maximum 100 digits please">
                            <div class="invalid-feedback alert-box"></div>
                        </div>
                    </div>  
                    <div class="form-group row mbrem5">
                        <!-- account name -->
                        <label class="col-lg-4 col-form-label  textLeft" for="account_name">Account Name</label>
                        <div class="col-lg-8">
                            <input type="text" id="req_account_name" name="account_name" class="form-control" placeholder="Account Name"
                                value="{{ old('account_name', $item->account_name) }}" required autofocus
                                data-rule-maxlength="100" data-msg-maxlength="Maximum 100 characters please">
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- bank type -->
                        <label class="col-lg-4 col-form-label  textLeft" for="bank_type">Bank Type</label>
                        <div class="col-lg-8">
                            <input type="text" id="req_bank_type" name="bank_type" class="form-control" placeholder="Bank Type"
                                value="{{ $item->bank_type }}" disabled autofocus
                                data-rule-maxlength="100" data-msg-maxlength="Maximum 100 characters please">
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- disbursement fee -->
                        <label class="col-lg-4 col-form-label  textLeft" for="disbursement_fee">Disbursement Fee</label>
                        <div class="col-lg-8">
                            <input type="number" id="req_disbursement_fee" name="disbursement_fee" class="form-control"
                                placeholder="Disbursement Fee" value="{{ explode('.', $item->disbursement_fee)[0] }}"
                                required>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- disbursement interval -->
                        <label class="col-lg-4 col-form-label  textLeft" for="auto_disbursement_interval">Disbursement Interval</label>
                        <div class="col-lg-8">
                            <select id="req_auto_disbursement_interval" name="auto_disbursement_interval" class="form-control">
                                
                                 @if ($item->auto_disbursement_interval == "PAYOUT_BY_REQUEST" || $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST_PARTNER")
                                    <option value="PAYOUT_BY_REQUEST" {{ $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST" ? 'selected' : '' }}>@lang('cms.Payout By Request')</option>
                                    <option value="PAYOUT_BY_REQUEST_PARTNER" {{ $item->auto_disbursement_interval == "PAYOUT_BY_REQUEST_PARTNER" ? 'selected' : '' }}>@lang('cms.Payout By Request Partner')</option>
                                @endif
                                <option value="DAILY" {{ $item->auto_disbursement_interval == "DAILY" ? 'selected' : '' }}>Daily</option>
                                <option value="WEEKLY" {{ $item->auto_disbursement_interval == "WEEKLY" ? 'selected' : '' }}>Weekly</option>
                                <option value="ON_HOLD" {{ $item->auto_disbursement_interval == "ON_HOLD" ? 'selected' : '' }}>@lang('cms.On_Hold')</option>
                            </select>
                        </div>
                    </div>
                    <!-- old value readonly -->
                    <div class="form-group row mbrem5">
                        <label class="col-lg-12 col-form-label textLeft" for="bank_id">
                            <strong>Old Value</strong>
                        </label>
                    </div>
                    <div class="form-group row mbrem5">
                        <label class="col-lg-4 col-form-label textLeft" for="bank_id">Bank Name</label>
                        <div class="col-lg-8 textLeft">
                            @php
                                $bankName = '';
                                foreach ($banks as $value) {    
                                    if ($value->id == $item->bank_id) {
                                        $bankName = $value->name;
                                    }
                                }
                            @endphp
                            <label class="col-form-label">{{ $bankName }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- bank branch name -->
                        <label class="col-lg-4 col-form-label textLeft" for="branch_name">Bank Branch Name</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->branch_name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- account number -->
                        <label class="col-lg-4 col-form-label  textLeft" for="account_number">Account Number</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->account_number }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- account name -->
                        <label class="col-lg-4 col-form-label  textLeft" for="account_name">Account Name</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->account_name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- bank type -->
                        <label class="col-lg-4 col-form-label  textLeft" for="bank_type">Bank Type</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->bank_type }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- disbursement fee -->
                        <label class="col-lg-4 col-form-label  textLeft" for="disbursement_fee">Disbursement Fee</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ explode('.', $item->disbursement_fee)[0] }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- disbursement interval -->
                        <label class="col-lg-4 col-form-label  textLeft" for="auto_disbursement_interval">Disbursement Interval</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->auto_disbursement_interval }}</label>
                        </div>
                    </div>
                    <div class="form-group row mbrem5">
                        <!-- disbursement interval -->
                        <label class="col-lg-4 col-form-label  textLeft" for="auto_disbursement_interval">Status</label>
                        <div class="col-lg-8 textLeft">
                            <label class="col-form-label">{{ $item->status == 1 ? 'Active' : 'Inactive' }}</label>
                        </div>
                    </div>
                    <hr/>
                    <!-- reason field -->
                    <div class="form-group row mbrem5">
                        <label class="col-lg-4 col-form-label textLeft" for="reason">Reason</label>
                        <div class="col-lg-8">
                            <textarea type="text" id="reason" name="reason" class="form-control" placeholder="Reason"></textarea>
                        </div>
                    </div>
                    <!-- supporting document -->
                    <div class="form-group row mbrem5">
                        <label class="col-lg-4 col-form-label textLeft" for="supporting_document">Supporting Document</label>
                        <div class="col-lg-8">
                            <input type="file" id="supporting_document" name="supporting_document" class="form-control" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="justify-content:center">
                <button type="button" id="confirmRequestChange" class="btn btn-primary" onclick="submitRequest()">Confirm</button>
                <!-- cancel -->
                <button type="button" class="btn btn-outline-secondary ml-2" data-dismiss="modal" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js">
    </script>
    <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let oldValues = {};
        let ownerData;

        $(document).ready(function() {
            $('input, select, textarea').each(function() {
                let fieldName = $(this).attr('id') || $(this).attr('name');
                if (fieldName) {
                    oldValues[fieldName] = $(this).val();
                }
            });
        });

        $('.track-input').on('input', function() {
            let fieldName = $(this).attr('id') || $(this).attr('name');
            oldValues[fieldName] = $(this).val(); 
        });

        var customerId = $('#id').val();

        function closeModal(e){
            e.preventDefault();
            resetForm();
            $('#modal-request-change').modal('hide');
            resetUrl();
        }
        function submitRequest(){
            resetForm(false);
            let isDifferent = false;
            let isValid = true;
            if ($('#reason').val() == ''){
                $('#reason').addClass('error');
                // if error message already exist, remove it first
                if ($('#reason-error').length){
                    $('#reason-error').remove();
                }
                $('#reason').after('<label id="reason-error" class="text-danger" for="reason">This field is required.</label>');
                isValid = false;
            } else {
                $('#reason').removeClass('error');
                $('#reason-error').remove();
            }
            if ($('#supporting_document').val() == ''){
                $('#supporting_document').addClass('error');
                // if error message already exist, remove it first
                if ($('#supporting_document-error').length){
                    $('#supporting_document-error').remove();
                }
                $('#supporting_document').after('<label id="supporting_document-error" class="text-danger" for="supporting_document">This field is required.</label>');
                isValid = false;
            } else {
                $('#supporting_document').removeClass('error');
                $('#supporting_document-error').remove();
            }
            for (const [key, value] of Object.entries(oldValues)) {
                if (value != $('#req_'+key).val()){
                    if(key == 'bank_name'){
                        // find selected option of bank_id
                        let bank_id = $('#req_bank_id').val();
                        let bank_name = $('#req_bank_id option[value="'+bank_id+'"]').text();
                        if (bank_id != oldValues['bank_id']){
                            isDifferent = true;
                            break;
                        }
                    }
                    else if(key == 'bank_type'){
                        // find selected option of bank_type
                        let bank_type = $('#req_bank_type').val();
                        if (bank_type != oldValues['bank_type']){
                            isDifferent = true;
                            break;
                        }

                    }
                    else {
                        isDifferent = true;
                        break;
                    }
                }
            }

            if (!isDifferent){
                // remove all error message
                $('#alertNoChange').remove();
                // add error message on top of modal, then scroll to top
                $('#contentRequest').prepend('<div id="alertNoChange"class="alert alert-danger alert-dismissible fade show" role="alert">No changes detected. Please change at least one field.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>')
            } else {
                // remove all error message
                $('#alertNoChange').remove();
            }
            if (!isValid){
                // remove all error message
                $('#alertNotValid').remove();
                // add error message on top of modal, then scroll to top
                $('#contentRequest').prepend('<div id="alertNotValid" class="alert alert-danger alert-dismissible fade show" role="alert">Please fill all required fields.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>')
            } else {
                // remove all error message
                $('#alertNotValid').remove();
            }
            if (isDifferent && isValid){
                // Add loading indicator or message to indicate form submission
                $('#contentRequest').prepend('<div id="loadingIndicator" class="alert alert-info" role="alert">Submitting changes...</div>');
                $('#changeRequesttitle').focus();
                resetUrl();
                let form = $('#bankActForm');
                let formData = new FormData(form[0]);
                let url = form.attr('action');
                let method = form.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Remove the loading indicator if it exists

                        $('#loadingIndicator').remove();

                        // Prepend the success alert to the contentRequest element
                        $('#contentRequest').prepend('<div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">Data changes are successfully saved and are in the process of being reviewed first"<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');

                        // Wait for a few seconds before redirecting
                        setTimeout(function() {
                            // Redirect to the desired location
                            window.location.href = "{{ route('yukk_co.customers.detail', ['id' => ':id']) }}".replace(':id', customerId);
                        }, 2000); // Adjust the time (in milliseconds) based on how long you want the delay to be
                    },
                    error: function(xhr, status, error) {
                        $('#loadingIndicator').remove();
                        let errorContent = "<div class='alert alert-danger' role='alert'>";
                        console.log(xhr);

                        if (xhr.status !== 200) {
                            if (xhr.responseJSON && typeof xhr.responseJSON === 'object') {
                                if(xhr.responseJSON.errors) {
                                    // Loop through the errors object if the error messages are in an array format
                                    Object.values(xhr.responseJSON.errors).forEach(function(messages) {
                                        if(Array.isArray(messages)) {
                                            // If the messages is an array, join them
                                            errorContent += messages.join('<br />');
                                        } else {
                                            // If messages is a string, display it directly
                                            errorContent += messages;
                                        }
                                    });
                                } else if(typeof xhr.responseJSON === 'string') {
                                    // Handle single string error message
                                    errorContent += xhr.responseJSON.error;
                                } else {
                                    // Fallback error message
                                    errorContent += "An error occurred. Please try again.";
                                }
                            } else if(xhr.status == 422){
                                errorContent += xhr.responseJSON;
                            } else {
                            // Non-JSON response or unexpected format
                            errorContent += "An unexpected error occurred.";
                            }
                        } else {
                            errorContent += error;
                        }
                        
                        errorContent += "</div>";
                        $('#contentRequest').prepend(errorContent);
                    }

                });

            } else {
                $('#changeRequesttitle').focus();
            }
        }

        function resetUrl(){
            var currentUrl = window.location.href;
            var updatedUrl = currentUrl.split('#')[0];
            history.replaceState({}, document.title, updatedUrl)
        }

        function resetForm(reset=true) {
            // Clear all validation errors
            $('.form-control').removeClass('is-invalid');
            $('.alert').remove();
            $('#reason').removeClass('error');
            $('#reason-error').remove();
            $('#supporting_document').removeClass('error');
            $('#supporting_document-error').remove();

            // Reset the form to its initial state
            if (reset) {
                // Reset form fields to oldData values
                $('#req_bank_id').val(oldValues['req_bank_id']).trigger('change'); // For select2, if you're using it
                $('#req_branch_name').val(oldValues['req_branch_name']);
                $('#req_account_number').val(oldValues['req_account_number']);
                $('#req_account_name').val(oldValues['req_account_name']);
                $('#req_disbursement_fee').val(oldValues['req_disbursement_fee']);
                $('#req_auto_disbursement_interval').val(oldValues['req_auto_disbursement_interval']);
                $('#req_bank_type').val(oldValues['req_bank_type']);

                // Clear file input
                $('#supporting_document').val('');
                $('#reason').val('');
            }
        }
            // if compact 'flag' == failed, then show modal
            @if (isset($flag) && $flag == 'failed')
                $('#modal-suspected').modal('show');
            @endif
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
                var self = $(this);
                $("#bank_type_name").val(self.find(':selected').data('bank-type'));
            });
            // Listen for changes in the 'reason' textarea
            $('#reason').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $(this).removeClass('error');
                    $('#reason-error').remove();
                }
            });

            // Listen for changes in the 'supporting_document' file input
            $('#supporting_document').on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('error');
                    $('#supporting_document-error').remove();
                }
            });
        
        function handleBankChange(){
            let bankValue = $('#req_bank_id').val();
            if(bankValue == 1){
                $('#req_bank_type').val('BCA')
            } else {
                $('#req_bank_type').val('NON_BCA')
            }
        }

        function requestChange() {
            resetForm();
            // make reason and supporting document required
            $('#reason').attr('required', true);
            $('#supporting_document').attr('required', true);
            // make supporting document accept only image and 2MB max
            $('#supporting_document').attr('accept', 'image/*');
            $('#supporting_document').attr('filesize_max', 2048000);
            // min size 100 kb
            $('#supporting_document').attr('min', 100);
            $('#modal-request-change').modal('show');
            resetUrl();
        }

        function inputPreviewImage(input, target) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(target).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateFormSubmit(e) {
                e.preventDefault();
                var form = $('#form_edit');
                var formData = form.serialize();

                showLoadingSpinner();

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    success: function(response) {
                        hideLoadingSpinner();
                        swal({
                            title: "Update Success",
                            // text: "Data changes are successfully saved and are in the process of being reviewed first",
                            icon: "success",
                        })
                        .then((value) => {
                            window.location.href = "{{ route('yukk_co.customers.detail', ['id' => ':id']) }}".replace(':id', customerId);
                        });
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

        $('#confirmSuspected').on('click', function() {
            $('#modal-suspected').modal('hide');
        })

        function showLoadingSpinner() {
            $('body').after('<div class="loading"></div>');
            $('.loading').append('<div class="spinner-border text-primary justify-content-center" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
        }

        function hideLoadingSpinner() {
            $('.loading').remove();
        }
        </script>
@endsection
