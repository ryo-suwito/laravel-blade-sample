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
            <span class="breadcrumb-item active">@lang('cms.Detail')</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    .btn-orange {
        background-color: orange;
        color: white;
    }
</style>
@csrf
<!-- General Information -->
<div class="row">
    <div class="col-sm-12 col-lg-6">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="font-weight-bold">@lang('cms.General Information')</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="name">@lang('cms.Name')</label>
                    <div class="col-lg-8">
                        <input type="text" disabled name="name" id="name" class="form-control" placeholder="@lang('cms.Name')" required autofocus value="{{ optional($owner)->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="phone">@lang('cms.Phone')</label>
                    <div class="col-lg-8">
                        <input type="text" disabled name="phone" id="phone" class="form-control" placeholder="@lang('cms.Phone')" value="{{ optional($owner)->phone }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="email">@lang('cms.Email')</label>
                    <div class="col-lg-8">
                        <input type="email" disabled name="email" id="email" class="form-control" placeholder="@lang('cms.Email')" value="{{ optional($owner)->email }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="address">@lang('cms.Address')</label>
                    <div class="col-lg-8">
                        <input name="address" disabled id="address" class="form-control" placeholder="@lang('cms.Address')" value="{{ optional($owner)->address }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label"
                        for="city_id">@lang('cms.City')</label>
                    <div class="col-lg-8">
                        <select id="city_id" disabled name="city_id" class="form-control select2" required>
                            <option value="">Select City</option>
                            @foreach ($cities as $value)
                            <option value="{{ $value->id }}"
                                {{ optional($owner)->city_id == $value->id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="active">@lang('cms.Status')</label>
                    <div class="col-lg-8 align-items-center justify-content-center">
                        <input type="checkbox" style="margin:auto;" name="active" id="active" value="1" disabled {{ $owner->active ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(false)
    <!-- Bank Account Information -->
    <div class="col-sm-12 col-lg-6">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="font-weight-bold">@lang('Bank Account Information')</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="bank_account_number">@lang('cms.Account Number')</label>
                    <div class="col-lg-8">
                        <input type="text" disabled name="bank_account_number" id="bank_account_number" class="form-control" placeholder="@lang('cms.Account Number')" value="{{ optional($owner)->bank_account_number }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="bank_account_name">@lang('cms.Account Name')</label>
                    <div class="col-lg-8">
                        <input type="text" disabled name="bank_account_name" id="bank_account_name" class="form-control" placeholder="@lang('cms.Account Name')" value="{{ optional($owner)->bank_account_name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="bank_id">@lang('cms.Bank Name')</label>
                    <div class="col-lg-8">
                        <select id="bank_id" disabled name="bank_id" class="form-control select2"
                            required>
                            <option value="">Select Bank</option>
                            @foreach ($banks as $value)
                            <option value="{{ $value->id }}" data-bank-type="{{ $value->bank_type }}"
                                {{ optional($owner)->bank_id == $value->id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="bank_account_branch">@lang('cms.Bank Branch Name')</label>
                    <div class="col-lg-8">
                        <input type="text" disabled name="bank_account_branch" id="bank_account_branch" class="form-control" placeholder="@lang('cms.Bank Branch Name')" value="{{ optional($owner)->bank_account_branch }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Legal Identification -->
    <div class="col-sm-12 col-lg-6">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="font-weight-bold">@lang('Legal Identification')</h6>
            </div>
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="merchant_type">@lang('Owner Type')</label>
                        <div class="col-lg-8">
                            <select disabled name="merchant_type" id="merchant_type" class="form-control select2" style="width: 100%;" data-minimum-results-for-search="Infinity">
                                <option value="">@lang('cms.Select Type')</option>
                                <option value="1" {{ optional($owner)->merchant_type == "INDIVIDU" ? 'selected' : '' }}>INDIVIDU</option>
                                <option value="2" {{ optional($owner)->merchant_type == "BADAN_HUKUM" ? 'selected' : '' }}>BADAN HUKUM</option>
                                <!-- Add owner type options -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row" id="previewKtpContainerRow" >
                        <label class="col-lg-4 col-form-label">@lang('cms.File KTP')</label>
                        <div class="col-lg-8">
                            @if($owner->id_card_path != null)
                            <div class="row">
                                <div class="col-lg-12" style="margin-top:10px">
                                    <div id="previewContainer" style="position: relative;">
                                        <img src="{{ $owner->ktp_url }}" alt="File KTP" id="file_ktp_preview" style="width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row text-muted">
                                <div class="col-lg-12">
                                    <p>(File tidak tersedia)</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_number">@lang('cms.KTP No')</label>
                        <div class="col-lg-8">
                            <input type="number" disabled min="0" id="id_card_number" name="id_card_number"
                                class="form-control" placeholder="@lang('cms.KTP No')"
                                value="{{ optional($owner)->id_card_number }}" required data-rule-minlength="16"
                                data-rule-maxlength="16" data-msg-minlength="Exactly 16 digits please"
                                data-msg-maxlength="Exactly 16 digits please">
    
                            <div class="invalid-feedback alert-box"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row" id="previewSelfieContainerRow" >
                        <label class="col-lg-4 col-form-label">@lang('cms.File Selfie')</label>
                        <div class="col-lg-8">
                            @if($owner->selfie_path != null)
                            <div class="row">
                                <div class="col-lg-12" style="margin-top:10px">
                                    <div id="previewContainer" style="position: relative;">
                                        <img src="{{ $owner->selfie_url }}" alt="File Selfie" id="file_selfie_preview" style="width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row text-muted">
                                <div class="col-lg-12">
                                    <p>(File tidak tersedia)</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row" id="previewNpwpContainerRow" >
                        <label class="col-lg-4 col-form-label">@lang('cms.File NPWP')</label>
                        <div class="col-lg-8">
                            @if($owner->npwp_path != null)
                            <div class="row">
                                <div class="col-lg-12" style="margin-top:10px">
                                    <div id="previewContainer" style="position: relative;">
                                        <img src="{{ $owner->npwp_url }}" alt="File NPWP" id="file_npwp_preview" style="width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row text-muted">
                                <div class="col-lg-12">
                                    <p>(File tidak tersedia)</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="npwp_number">@lang('cms.NPWP No')</label>
                        <div class="col-lg-8">
                            <input type="number" disabled min="0" id="npwp_number" name="npwp_number" class="form-control"
                                placeholder="@lang('cms.NPWP No')" value="{{ optional($owner)->npwp_number }}"
                                data-rule-minlength="16" data-rule-maxlength="16"
                                data-msg-minlength="Exactly 16 digits please"
                                data-msg-maxlength="Exactly 16 digits please">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
</div>
<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <a href="{{ route('yukk_co.owners.list') }}" class="btn btn-block btn-warning">
                    @lang('cms.Go Back')
                </a>
            </div>
        </div>
    </div>
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
                            <input type="text" disabled value="{{ optional($owner)->id_card_number }}" form="form-owner" name="id_card_number" class="form-control" data_form_id_card data_form_id_card_number>
                            <p class="error" id="id_card_number_error" data-error-span style="color: red;"></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Name</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_name }}" form="form-owner" name="id_card_name" id="id_card_name" class="form-control" data_form_id_card data_form_id_card_name>
                            <p class="error" id="id_card_name_error" data-error-span style="color: red;"></p>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Tanggal Lahir</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_date_of_birth }}" form="form-owner" name="id_card_date_of_birth" class="form-control" data_form_id_card data_form_id_card_date_of_birth>
                            <span id="id_card_date_of_birth_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-4 col-form-label">Tempat Lahir</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_place_of_birth }}" form="form-owner" name="id_card_place_of_birth" class="form-control" data_form_id_card data_form_id_card_place_of_birth>
                            <span id="id_card_place_of_birth_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_address">@lang('cms.Address')</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_address }}" form="form-owner" name="id_card_address" id="id_card_address" class="form-control" placeholder="@lang('cms.Address')" data_form_id_card data_form_id_card_address>
                            <span id="id_card_address_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_gender">@lang('cms.Gender')</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_gender }}" form="form-owner" name="id_card_gender" id="id_card_gender" class="form-control" placeholder="@lang('cms.Gender')" data_form_id_card data_form_id_card_gender>
                            <span id="id_card_gender_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_marital_status">@lang('cms.Marital Status')</label>
                        <div class="col-lg-8 d-flex flex-column">
                            <input type="text" disabled value="{{ optional($owner)->id_card_marital_status }}" form="form-owner" name="id_card_marital_status" id="id_card_marital_status" class="form-control" placeholder="@lang('cms.Marital Status')" data_form_id_card data_form_id_card_marital_status>
                            <span id="id_card_marital_status_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_job">@lang('cms.Job')</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_job }}" form="form-owner" name="id_card_job" id="id_card_job" class="form-control" placeholder="@lang('cms.Job')" data_form_id_card data_form_id_card_job>
                            <span id="id_card_job_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="id_card_nationality">@lang('cms.Nationality')</label>
                        <div class="col-lg-8">
                            <input type="text" disabled value="{{ optional($owner)->id_card_nationality }}" form="form-owner" name="id_card_nationality" id="id_card_nationality" class="form-control" placeholder="@lang('cms.Nationality')" data_form_id_card data_form_id_card_nationality>
                            <span id="id_card_nationality_error" data-error-span class="error text-danger mt-1" style="display: none;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group" style="width: 100%;">
                    <button id="btn-apply-review" class="btn btn-primary">Close</button>
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
            //if the clicked area is not within #removeFileKtp, then show the modal
            if (!$(e.target).closest('#removeFileKtp').length) {
                $('#modal-ocr').modal('show');
            }
        });

        $('#btn-apply-review').on('click', function(e) {
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
    });
</script>
@endsection