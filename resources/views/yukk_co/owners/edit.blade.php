@extends('layouts.master')

@section('header')
<!-- Page header -->
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            <h4>@lang('cms.Owner')</h4>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="breadcrumb">
            <a href="{{ route('cms.index') }}" class="breadcrumb-item">
                <i class="icon-home2 mr-2"></i> @lang('cms.Home')
            </a>
            <a href="{{ route('yukk_co.owners.list') }}" class="breadcrumb-item">@lang('cms.Owner')</a>
            <span class="breadcrumb-item active">@lang('cms.Edit')</span>
        </div>
    </div>
</div>
@endsection

@section('html_head')
<style>
    .loading {
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    /* Transparent Overlay */
    .loading::before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.58);
    }
</style>
@endsection

@section('content')
<div class="loading" style="display: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
    <div class="text-warning" role="status" style="position: absolute; display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
        <img src="{{ asset('assets/images/ic_loading.gif') }}" alt="Loading..." style="width: 100px; height: 100px;">
    </div>
</div>

<form id="form-owner" action="{{ route('yukk_co.owners.update', $owner->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" form="form-owner" name="id" value="{{ $owner->id }}">
    <div class="row">
        <!-- General Information -->
        <div class="col-sm-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h6 class="font-weight-bold m-0">@lang('cms.General Information')</h6>
                    <button type="button" class="btn btn-sm" data-toggle="tooltip" data-trigger="click" data-placement="top" title="This section is required. Please fill in the required information.">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_name">@lang('cms.Name')</label>
                        <div class="col-lg-8">
                            <input type="text" name="name" value="{{ $owner->name }}" id="name" class="form-control" placeholder="@lang('cms.Name')" required autofocus>
                            <span id="name_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="phone">@lang('cms.Phone')</label>
                        <div class="col-lg-8">
                            <input type="text" name="phone" value="{{ $owner->phone }}" id="phone" class="form-control" placeholder="@lang('cms.Phone')" inputmode="numeric" pattern="[0-9]*">
                            <span id="phone_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="email">@lang('cms.Email')</label>
                        <div class="col-lg-8">
                            <input type="email" name="email" value="{{ $owner->email }}" id="email" required class="form-control" placeholder="@lang('cms.Email')">
                            <span id="email_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_address">@lang('cms.Address')</label>
                        <div class="col-lg-8">
                            <textarea name="address" id="address" rows="3" required class="form-control" placeholder="@lang('cms.Address')">{{ $owner->address }}</textarea>
                            <span id="address_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label"
                            for="city_id">@lang('cms.City')</label>
                        <div class="col-lg-8">
                            <select id="city_id" name="city_id" class="form-control select2" required>
                                <option value="">@lang('cms.Select City')</option>
                                @foreach ($cities as $value)
                                <option value="{{ $value->id }}"
                                    {{ $owner->city_id == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                            <span id="city_id_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="active">@lang('cms.Status')</label>
                        <div class="col-lg-8 align-items-center justify-content-center">
                            <input type="checkbox" style="margin:auto;" name="active" id="active" value="1" {{ $owner->active ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bank Account Information -->
        <div class="d-none">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h6 class="font-weight-bold m-0">@lang('Bank Account Information')</h6>
                    <button type="button" class="btn btn-sm" data-toggle="tooltip" data-trigger="click" data-placement="top" title="This section is optional. Leave it empty if not needed.">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_id">@lang('cms.Bank Name')</label>
                        <div class="col-lg-8">
                            <select id="bank_id" name="bank_id" class="form-control select2">
                                <option value="">@lang('cms.Select Bank')</option>
                                @foreach ($banks as $value)
                                <option value="{{ $value->id }}" data-bank-type="{{ $value->bank_type }}"
                                    {{ $owner->bank_id == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                            <span id="bank_id_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_account_number">@lang('cms.Account Number')</label>
                        <div class="col-lg-8">
                            <input type="text" @if($owner->bank_id == null) disabled @endif name="bank_account_number" id="bank_account_number" class="form-control" placeholder="@lang('cms.Account Number')" inputmode="numeric" pattern="[0-9]*" value="{{ $owner->bank_account_number }}">
                            <span id="bank_account_number_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_account_name">@lang('cms.Account Name')</label>
                        <div class="col-lg-8">
                            <input type="text" @if($owner->bank_id == null) disabled @endif name="bank_account_name" id="bank_account_name" class="form-control" placeholder="@lang('cms.Account Name')" value="{{ $owner->bank_account_name }}">
                            <span id="bank_account_name_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_account_branch">@lang('cms.Bank Branch Name')</label>
                        <div class="col-lg-8">
                            <input type="text" @if($owner->bank_id == null) disabled @endif name="bank_account_branch" id="bank_account_branch" class="form-control" placeholder="@lang('cms.Bank Branch Name')" value="{{ $owner->bank_account_branch }}">
                            <span id="bank_account_branch_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Legal Identification -->
        <div class="col-sm-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h6 class="font-weight-bold m-0">@lang('Legal Identification')</h6>
                        <button type="button" class="btn btn-sm" data-toggle="tooltip" data-trigger="click" data-placement="top" title="<ul><li>The merchant type is required and is required to fill in the KTP number or NPWP number.</li><li>If the selected merchant type is <b>Individu</b>, please fill in the KTP information.</li><li>If the selected merchant type is <b>Badan Hukum</b>, please fill in the NPWP information.</li></ul>">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="merchant_type">@lang('Owner Type')</label>
                            <div class="col-lg-8">
                                <select name="merchant_type" id="merchant_type" required class="form-control select2" required style="width: 100%;" data-minimum-results-for-search="Infinity">
                                    <option value="">@lang('cms.Select Type')</option>
                                    <option value="INDIVIDU" {{ $owner->merchant_type == 'INDIVIDU' ? 'selected' : '' }}>Individu</option>
                                    <option value="BADAN_HUKUM" {{ $owner->merchant_type == 'BADAN_HUKUM' ? 'selected' : '' }}>Badan Hukum</option>
                                </select>
                                <span id="merchant_type_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang('cms.File KTP')</label>
                            <div class="col-lg-8">
                                <input type="file" id="file_ktp" name="file_ktp" class="form-control" accept=".jpg, .png, .jpeg">
                                <span id="file_ktp_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                        <div class="form-group row" id="previewKtpContainerRow" @if($owner->id_card_path == null) style="display: none;" @endif>
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12" style="margin-top:10px">
                                        <div id="previewContainer" style="position: relative;">
                                            <button id="removeFileKtp" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: 0; z-index: 1;"><i class="fas fa-times"></i></button>
                                            <img src="{{ $owner->ktp_url }}" alt="" id="file_ktp_preview" style="width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="id_card_number">@lang('cms.KTP No')</label>
                            <div class="col-lg-8">
                                <input type="number" min="0" id="id_card_number" name="id_card_number"
                                    class="form-control" placeholder="@lang('cms.KTP No')"
                                    value="{{ $owner->id_card_number }}" inputmode="numeric" pattern="[0-9]*" data-rule-minlength="16"
                                    data-rule-maxlength="16" data-msg-minlength="Exactly 16 digits please"
                                    data-msg-maxlength="Exactly 16 digits please">
                                <span id="id_card_number_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang('cms.File Selfie')</label>
                            <div class="col-lg-8">
                                <input type="file" id="file_selfie" name="file_selfie" class="form-control" accept=".jpg, .png, .jpeg">
                                <span id="file_selfie_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                        <div class="form-group row" id="previewSelfieContainerRow" @if($owner->selfie_path == null) style="display: none;" @endif>
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12" style="margin-top:10px">
                                        <div id="previewContainer" style="position: relative;">
                                            <button id="removeFileSelfie" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: 0; z-index: 1;"><i class="fas fa-times"></i></button>
                                            <img src="{{ $owner->selfie_url }}" alt="" id="file_selfie_preview" style="width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang('cms.File NPWP')</label>
                            <div class="col-lg-8">
                                <input type="file" id="file_npwp" name="file_npwp" class="form-control" accept=".jpg, .png, .jpeg">
                                <span id="file_npwp_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                        <div class="form-group row" id="previewNpwpContainerRow" @if($owner->npwp_path == null) style="display: none;" @endif>
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12" style="margin-top:10px">
                                        <div id="previewContainer" style="position: relative;">
                                            <button id="removeFileNpwp" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: 0; z-index: 1;"><i class="fas fa-times"></i></button>
                                            <img src="{{ $owner->npwp_url }}" alt="" id="file_npwp_preview" style="width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="npwp_number">@lang('cms.NPWP No')</label>
                            <div class="col-lg-8">
                                <input type="number" min="0" id="npwp_number" name="npwp_number" class="form-control"
                                    placeholder="@lang('cms.NPWP No')" value="{{ $owner->npwp_number }}"
                                    data-rule-minlength="16" data-rule-maxlength="16"
                                    data-msg-minlength="Exactly 16 digits please"
                                    data-msg-maxlength="Exactly 16 digits please">
                                <span id="npwp_number_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="text-center">
        <button id="btn-check" type="button" class="btn btn-warning col-md-1" data-toggle="modal" data-target="#kycCheckingModal">
            @lang('cms.Check')
        </button>
    </div>
</form>

<div class="modal fade" id="modal-ocr" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">KTP Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="ktpDetailsContent">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">NIK</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_number }}" form="form-owner" class="form-control" data_form_id_card data_form_id_card_number>
                            <span id="id_card_number_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Name</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_name }}" form="form-owner" name="id_card_name" id="id_card_name" class="form-control" data_form_id_card data_form_id_card_name>
                            <span id="id_card_name_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Tanggal Lahir</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_date_of_birth }}" form="form-owner" name="id_card_date_of_birth" class="form-control" data_form_id_card data_form_id_card_date_of_birth>
                            <span id="id_card_date_of_birth_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Tempat Lahir</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_place_of_birth }}" form="form-owner" name="id_card_place_of_birth" class="form-control" data_form_id_card data_form_id_card_place_of_birth>
                            @if($errors->has('id_card_place_of_birth'))
                            @foreach($errors->get('id_card_place_of_birth') as $error)
                            <span id="id_card_place_of_birth_error" data-error-span class="error text-danger mt-1" style="display: block;">{{ $error }}</span>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_address">@lang('cms.Address')</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_address }}" form="form-owner" name="id_card_address" id="id_card_address" class="form-control" placeholder="@lang('cms.Address')" data_form_id_card data_form_id_card_address>
                            <span id="id_card_address_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_gender">@lang('cms.Gender')</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_gender }}" form="form-owner" name="id_card_gender" id="id_card_gender" class="form-control" placeholder="@lang('cms.Gender')" data_form_id_card data_form_id_card_gender>
                            <span id="id_card_gender_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_marital_status">@lang('cms.Marital Status')</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_marital_status }}" form="form-owner" name="id_card_marital_status" id="id_card_marital_status" class="form-control" placeholder="@lang('cms.Marital Status')" data_form_id_card data_form_id_card_marital_status>
                            <span id="id_card_marital_status_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_job">@lang('cms.Job')</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_job }}" form="form-owner" name="id_card_job" id="id_card_job" class="form-control" placeholder="@lang('cms.Job')" data_form_id_card data_form_id_card_job>
                            <span id="id_card_job_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_nationality">@lang('cms.Nationality')</label>
                        <div class="col-lg-8">
                            <input type="text" value="{{ $owner->id_card_nationality }}" form="form-owner" name="id_card_nationality" id="id_card_nationality" class="form-control" placeholder="@lang('cms.Nationality')" data_form_id_card data_form_id_card_nationality>
                            <span id="id_card_nationality_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group" style="width: 100%;">
                    <button id="btn-apply-review" class="btn btn-primary">Apply</button>
                    <button id="btn-cancel-review" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-kyc" tabindex="-1" role="dialog" aria-labelledby="kycCheckingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kycCheckingModalLabel">KYC Checking Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-kyc-body">
                <div data-kyc-action-card class="bg-dark rounded mb-3">
                    <div class="bg-light text-white p-2 d-flex justify-content-between align-items-center rounded">
                        <span data-kyc-action-card-title>Internal Blacklist</span>
                        <span data-kyc-action-card-label></span>
                        <span data-kyc-action-card-icon-success class="badge"><i class="icon-checkmark text-success" style="font-size: 20px;"></i></span>
                        <span data-kyc-action-card-icon-warning class="badge"><i class="icon-exclamation text-warning" style="font-size: 20px;"></i></span>
                        <span data-kyc-action-card-icon-failed class="badge"><i class="icon-cross text-danger" style="font-size: 20px;"></i></span>
                    </div>
                    <div class="bg-dark text-white p-2 d-flex rounded">
                        <p data-kyc-action-card-summary class="mb-0" id="internalBlacklistMessage" style="white-space: pre-wrap;">KTP is blacklisted. Please use other KTP</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center pb-3">
                    <button id="btn-kyc-cancel" type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: transparent; border: none;">Cancel</button>
                    <button id="btn-kyc-save" type="button" class="btn btn-primary" id="saveDataBtn">Save data</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'click',
            placement: 'bottom',
            html: true,
            customClass: 'bg-dark text-danger',
            boundary: 'window',
        });

        $('.loading').hide();

        $('#modal-kyc').on('show.bs.modal', function() {
            $('#modal-ocr').modal('hide');
        });

        $('#modal-kyc').on('hidden.bs.modal', function() {
            const modalKycBody = document.querySelector('#modal-kyc-body');
            while (modalKycBody.children.length > 1) {
                modalKycBody.removeChild(modalKycBody.children[1]);
            }
        });

        function handleFileInputChange(fileInputId, errorElementId, previewContainerId, previewImageId, removeButtonId) {
            $(`#${fileInputId}`).on('change', function() {
                const file = this.files[0];
                const errorElement = $(`#${errorElementId}`);
                const previewContainerRow = $(`#${previewContainerId}`);
                const previewImage = $(`#${previewImageId}`);

                // Reset error message and hide preview
                errorElement.hide().text('');
                previewContainerRow.hide();
                previewImage.attr('src', '#');

                if (!file) return;

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    errorElement.text('Invalid file type. Please upload a JPG, PNG, or JPEG file.').show();
                    return;
                }

                // Validate file size (2MB limit)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    errorElement.text('File size exceeds 2MB. Please upload a smaller file.').show();
                    return;
                }

                // Display preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.attr('src', e.target.result);
                    previewContainerRow.show();
                };
                reader.readAsDataURL(file);
            });

            // Remove file handler
            $(`#${removeButtonId}`).on('click', function(e) {
                e.preventDefault();
                $(`#${fileInputId}`).val('');
                $(`#${previewImageId}`).attr('src', '#');
                $(`#${previewContainerId}`).hide();
                $(`#${errorElementId}`).hide().text('');
            });
        }

        handleFileInputChange('file_ktp', 'file_ktp_error', 'previewKtpContainerRow', 'file_ktp_preview', 'removeFileKtp');
        handleFileInputChange('file_npwp', 'file_npwp_error', 'previewNpwpContainerRow', 'file_npwp_preview', 'removeFileNpwp');
        handleFileInputChange('file_selfie', 'file_selfie_error', 'previewSelfieContainerRow', 'file_selfie_preview', 'removeFileSelfie');

        $('#previewKtpContainerRow').on('click', function(e) {
            if (!$(e.target).closest('#removeFileKtp').length) {
                $('#modal-ocr').modal('show');
            }
        });

        $('#file_ktp').on('change', async function(event) {
            document.querySelectorAll('#ktpDetailsContent [data-error-span]').forEach(element => {
                element.textContent = '';
                element.style.display = 'none';
            });

            $('.loading').show();

            const file = event.target.files[0];

            const formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file_ktp', file);

            const response = await $.ajax({
                url: "{{ route('yukk_co.owners.scan.ktp') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
            }).catch(function(response) {
                toastError(response.responseJSON.status_message);
            });

            $('.loading').hide();

            if (response.status_code != 6000 || response.result == null) {
                toastError(response.status_message);
                return;
            }

            let result = response.result[0];

            if (result == null) {
                toastError(response.status_message);
                return;
            }

            if (! ['OK', 'CONFLICT'].includes(result.code)) {
                document.querySelector('#file_ktp_error').textContent = result.message;
                document.querySelector('#file_ktp_error').style.display = 'block';
                return;
            }

            if (result.code == 'CONFLICT') {
                document.querySelector('[data_form_id_card_number] ~ #id_card_number_error').textContent = result.message;
                document.querySelector('[data_form_id_card_number] ~ #id_card_number_error').style.display = 'block';

                document.querySelector('#btn-apply-review').disabled = true;

                document.querySelector('[data_form_id_card_number]').addEventListener('input', function(event) {
                    document.querySelector('#btn-apply-review').disabled = event.target.value == result.result.nik;
                });
            }

            document.querySelectorAll('[data_form_id_card_number]').forEach(element => {
                element.value = result.result.nik;
            });
            document.querySelectorAll('[data_form_id_card_name]').forEach(element => {
                element.value = result.result.full_name;
            });
            document.querySelectorAll('[data_form_id_card_date_of_birth]').forEach(element => {
                element.value = result.result.date_of_birth;
            });
            document.querySelectorAll('[data_form_id_card_place_of_birth]').forEach(element => {
                element.value = result.result.place_of_birth;
            });
            document.querySelectorAll('[data_form_id_card_address]').forEach(element => {
                element.value = result.result.address;
            });
            document.querySelectorAll('[data_form_id_card_gender]').forEach(element => {
                element.value = result.result.gender;
            });
            document.querySelectorAll('[data_form_id_card_marital_status]').forEach(element => {
                element.value = result.result.marital_status;
            });
            document.querySelectorAll('[data_form_id_card_job]').forEach(element => {
                element.value = result.result.occupation;
            });
            document.querySelectorAll('[data_form_id_card_nationality]').forEach(element => {
                element.value = result.result.nationality;
            });

            $('#modal-ocr').modal('show');
        });
                
        $('[data_form_id_card_number] ~ #id_card_number_error').on('change', function() {
            if ($(this).val()) {
                document.querySelector('#btn-apply-review').disabled = false;
            } else {
                document.querySelector('#btn-apply-review').disabled = true;
            }
        });

        $('#btn-check').on('click', async function() {
            $('.loading').show();
            document.querySelectorAll('[data-error-span]').forEach(element => {
                element.style.display = 'none';
                element.textContent = '';
            });
            document.querySelector('[data-kyc-action-card]').style.display = 'block';
            const formData = new FormData($('#form-owner')[0]);
            const response = await $.ajax({
                url: "{{ route('yukk_co.owners.verify') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
            }).catch(function(response) {
                $('.loading').hide();
                if (response.status == 500) {
                    toastError('Whoops, something went wrong. Please contact our support team.');
                }
                if (response.status == 422) {
                    const errors = response.responseJSON.result;

                    const idCardErrors = Object.keys(errors).filter(key => key.startsWith('id_card_'));

                    const idCardNumberErrors = Object.keys(errors).filter(key => key.startsWith('id_card_number'));

                    Object.keys(errors).forEach(key => {
                        document.querySelector(`#${key}_error`).textContent = errors[key][0];
                        document.querySelector(`#${key}_error`).style.display = 'block';
                    });

                    if (idCardErrors.length > 0) {
                        if (!document.querySelector('#file_ktp').value && document.querySelector('#merchant_type').value == 'INDIVIDU' && window.location.href.includes('edit')) {
                            document.querySelector('#file_ktp_error').textContent = "File KTP is required";
                            document.querySelector('#file_ktp_error').style.display = 'block';
                        } else if (idCardNumberErrors.length > 0) {
                            //
                        } else {
                            document.querySelector('#file_ktp_error').textContent = "Some fields are not valid. Please click the image below to show the KTP forms and which fields are not valid.";
                            document.querySelector('#file_ktp_error').style.display = 'block';
                        }
                    }
                }
                return;
            }).done(function(response) {
                $('.loading').hide();
            });
            const result = response?.result;
            if (result?.length == null) {
                return;
            }
            $('#modal-kyc').modal('show');
            result.forEach(element => {
                const card = document.querySelector('[data-kyc-action-card]').cloneNode(true);
                card.querySelector('[data-kyc-action-card-title]').textContent = element.label;
                if (! element.action.includes('_count')) {
                    card.querySelector('[data-kyc-action-card-summary]').textContent = element.message;
                } else {
                    card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.add('d-none');
                    card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.remove('d-flex');
                }
                if (element.code == 'OK') {
                    if (element.action.includes('_count')) {
                        card.querySelector('[data-kyc-action-card-label]').textContent = element.result.count;
                        card.querySelector('[data-kyc-action-card-icon-success]').style.display = 'none';
                        card.querySelector('[data-kyc-action-card-icon-failed]').style.display = 'none';
                        card.querySelector('[data-kyc-action-card-icon-warning]').style.display = 'none';
                    } else {
                        card.querySelector('[data-kyc-action-card-icon-success]').style.display = 'block';
                        card.querySelector('[data-kyc-action-card-icon-warning]').style.display = 'none';
                        card.querySelector('[data-kyc-action-card-icon-failed]').style.display = 'none';
                        card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.remove('d-flex')
                        card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.add('d-none')
                    }
                    if (Object.keys(element.errors).length > 0) {
                        card.querySelector('[data-kyc-action-card-icon-warning]').style.display = 'block';
                        card.querySelector('[data-kyc-action-card-icon-success]').style.display = 'none';
                        card.querySelector('[data-kyc-action-card-icon-failed]').style.display = 'none';
                        const summary = card.querySelector('[data-kyc-action-card-summary]');
                        const summaryList = document.createElement('ul');
                        Object.values(element.errors).forEach(error => {
                            const listItem = document.createElement('li');
                            listItem.textContent = error.message;
                            summaryList.appendChild(listItem);
                        });
                        summary.appendChild(summaryList);
                        card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.remove('d-none')
                        card.querySelector('[data-kyc-action-card-summary]').parentElement.classList.add('d-flex')
                    }
                } else {
                    card.querySelector('[data-kyc-action-card-label]').textContent = '';
                    card.querySelector('[data-kyc-action-card-icon-success]').style.display = 'none';
                    card.querySelector('[data-kyc-action-card-icon-warning]').style.display = 'none';
                    card.querySelector('[data-kyc-action-card-icon-failed]').style.display = 'block';
                    card.querySelector('[data-kyc-action-card-summary]').parentElement.style.display = 'block';
                    if (Object.keys(element.errors).length > 0) {
                        const summary = card.querySelector('[data-kyc-action-card-summary]');
                        const summaryList = document.createElement('ul');
                        Object.values(element.errors).forEach(error => {
                            const listItem = document.createElement('li');
                            listItem.textContent = error.message;
                            summaryList.appendChild(listItem);
                        });
                        summary.appendChild(summaryList);
                    }
                }
                document.querySelector('#modal-kyc-body').appendChild(card);
            });
            document.querySelector('[data-kyc-action-card]').style.display = 'none';
            
            result.filter(element => element.code == 'OK').length == result.length 
                ? document.querySelector('#btn-kyc-save').disabled = false 
                : document.querySelector('#btn-kyc-save').disabled = true;
        });

        $('#btn-apply-review').on('click', function() {
            document.querySelector('#id_card_number').value = document.querySelector('[data_form_id_card_number]').value;

            $('#modal-ocr').modal('hide');
        });

        $('#btn-cancel-review').on('click', function() {
            document.querySelectorAll('[data_form_id_card]').forEach(element => {
                element.value = '';
            });

            document.querySelector('#id_card_number').value = '';
            document.querySelector('#id_card_name').value = '';
            document.querySelector('#id_card_address').value = '';

            document.querySelector('#file_ktp').value = '';
            document.querySelector('#file_ktp_preview').src = '#';
            $('#previewKtpContainerRow').hide();
            $('#file_ktp_error').hide();

            $('#modal-ocr').modal('hide');
        });

        $('#btn-kyc-cancel').on('click', function() {
            $('#modal-kyc').modal('hide');
        });

        $('#btn-kyc-save').on('click', function() {
            $('#modal-kyc').modal('hide');

            $('#form-owner').submit();

            $('.loading').show();
        });

        $('#bank_id').on('change', function() {
            const bankId = $(this).val();

            if (bankId) {
                $('#bank_account_number').attr('disabled', false);
                $('#bank_account_name').attr('disabled', false);
                $('#bank_account_branch').attr('disabled', false);
            } else {
                $('#bank_account_number').attr('disabled', true);
                $('#bank_account_name').attr('disabled', true);
                $('#bank_account_branch').attr('disabled', true);
                //set all value to empty
                $('#bank_account_number').val('');
                $('#bank_account_name').val('');
                $('#bank_account_branch').val('');
            }

            return true;
        });

        $('#bank_account_number').on('keydown', function(e) {
            if (isNaN(e.key) && e.key !== 'c' && e.key !== 'v' && e.key !== 'x' && e.key !== 'Backspace') {
                e.preventDefault();
            }
        });

        $('#phone').on('input', function(e) {
            const phoneNumber = e.target.value;

            if (isNaN(phoneNumber)) {
                e.preventDefault();
            }
        });

        $('#phone').on('change', function() {
            const phoneNumber = $(this).val();

            if (phoneNumber.length > 15) {
                document.querySelector('#phone_error').textContent = 'Phone number must be less than 15 digits';
            } else {
                document.querySelector('#phone_error').textContent = '';
            }

            document.querySelector('#phone').value = phoneNumber.replaceAll('-', ''); 
            document.querySelector('#phone').value = phoneNumber.trim().replaceAll(' ', '');

            if (phoneNumber.startsWith('+')) {
                document.querySelector('#phone').value = phoneNumber.replace('+', '');
            }

            if (phoneNumber.startsWith('+62')) {
                document.querySelector('#phone').value = phoneNumber.replace('+62', '0');
            }
            
            if (phoneNumber.startsWith('62')) {
                document.querySelector('#phone').value = phoneNumber.replace('62', '0');
            }
        });

        $('#email').on('change', function() {
            const email = $(this).val();

            console.log(email);

            if (email.length > 30) {
                document.querySelector('#email_error').style.display = 'block';
                document.querySelector('#email_error').textContent = 'Email must be less than 30 characters';
            } else {
                document.querySelector('#email_error').style.display = 'none';
                document.querySelector('#email_error').textContent = '';
            }
            
            if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                document.querySelector('#email_error').style.display = 'block';
                document.querySelector('#email_error').textContent = 'Invalid email format';
            } else {
                document.querySelector('#email_error').style.display = 'none';
                document.querySelector('#email_error').textContent = '';
            }
        });
    });

    function toastError(message) {
        Swal.fire({
            text: message,
            icon: 'error',
            timer: 3000,
            showConfirmButton: false,
            position: 'center',
        });
    }

    function toastSuccess(message) {
        Swal.fire({
            text: message,
            icon: 'success',
            toast: true,
            timer: 3000,
            showConfirmButton: false,
            position: 'top-right',
        });
    }
</script>
@endsection