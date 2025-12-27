@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Partner Edit")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.partner.list") }}" class="breadcrumb-item">@lang("cms.Partner List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner Edit")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
<style>
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
            font-size: 11px;
            line-height: 1.5;
        }
    </style>
    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.partner.update', $partner->id) }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" value="{{ $partner->name }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Code")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="code" value="{{ $partner->code }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="description" value="{{ $partner->description }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="short_description" value="{{ $partner->short_description }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Type")</label>
                        <div class="col-sm-4">
                            <select name="partner_type" id="partner_type" class="form-control select2" data-minimum-results-for-search="Infinity" required>
                                <option value="">Select Type</option>
                                <option value="MA" {{ $partner->type == 'MA' ? 'selected' : (old('partner_type') == 'MA' ? 'selected' : '') }}>@lang("cms.Merchant Aggregator (MA)")</option>
                                <option value="Internal" {{ $partner->type == 'Internal' ? 'selected' : (old('partner_type') == 'Internal' ? 'selected' : '') }}>@lang("cms.Internal")</option>
                                <option value="Others" {{ $partner->type == 'Others' ? 'selected' : (old('partner_type') == 'Others' ? 'selected' : '') }}>@lang("cms.Others")</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_in_percentage" value="{{ $partner->fee_partner_percentage }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_in_idr" value="{{ $partner->fee_partner_fixed }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_yukk_in_percentage" value="{{ $partner->fee_yukk_additional_percentage }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_yukk_in_idr" value="{{ $partner->fee_yukk_additional_fixed }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Minimum Nominal for QRIS")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="minimum_nominal" value="{{ $partner->minimum_nominal }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Owner")</label>
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <div class="col">
                                    <select id="owner_id" name="owner_id" class="form-control select2" required>
                                        <option value="">Select Owner</option>
                                        @foreach ($owner as $value)
                                            <option value="{{ $value->id }}"
                                            {{ $partner->owner_id == $value->id ? 'selected' : (old('owner_id') == $value->id ? 'selected' : '') }}>
                                            {{ $value->name }}</option>
                                        @endforeach
                                    </select>

                                    <div id="owner_details" class="card col-lg-12 mt-3" style="display: none; background-color: black; color: white; border-radius: 20px; border: 1px solid #969699; width: 100%;">
                                        <div class="card-body">
                                            <div class="form-group row" style="margin-left: 2px; margin-bottom: -15px; align-items: center;">
                                                <p id="owner_name_label" style="font-size: 18px;"></p>
                                                <p id="owner_id_label" style="margin-left: 5px; font-size: 12px; color: #969699;"></p>
                                            </div>
                                            <hr style="border-color: #969699;">
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <p id="owner_phone" style="font-size: 12px; color: #969699;"></p>
                                                    <p id="owner_email" style="font-size: 12px; color: #969699;"></p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p id="owner_ktp" style="font-size: 12px; color: #969699;"></p>
                                                    <p id="owner_npwp" style="font-size: 12px; color: #969699;"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Use PIC information from owner</label>
                        <div class="col-sm-4">
                            <input class="mt-2" type="checkbox" id="owner_fill_information" name="owner_fill_information"
                            {{ $partner->is_pic_details_using_owner ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.PIC Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input" name="pic_name" id="pic_name" value="{{ $partner->pic_name }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.PIC Email")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input" name="pic_email" id="pic_email" value="{{ $partner->pic_email }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.PIC Phone")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input" name="pic_phone" id="pic_phone" value="{{ $partner->pic_phone }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Email List")</label>
                        <div class="col-sm-4">
                            <textarea class="form-control" name="email_list">{{ $partner->email_list }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- <div class="form-group row">
                        <label class="col-form-label col-sm-2">Use Bank information from owner</label>
                        <div class="col-sm-4">
                            <input class="mt-2" type="checkbox" id="owner_fill_rekening" name="owner_fill_rekening"
                            {{ $partner->is_bank_details_using_owner ? 'checked' : '' }}>
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Bank Name")</label>
                        <div class="col-sm-4">
                            <select class="form-control track-input select2 @if ($errors->has("bank_id")) is-invalid @endif" name="bank_id" id="bank_id">
                                <option value="">Please Choose</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" data-bank-type="{{ $bank->bank_type }}" @if($partner->bank_id == $bank->id) selected @endif>{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has("bank_id"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("bank_id") }}</span>
                            @endif
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Bank Type")</label>
                        <div class="col-sm-4">
                            <input type="text" id="bank_type" name="bank_type"
                                   class="bank_type form-control" value="{{ $partner->bank_type }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Account Number")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input @if ($errors->has('account_number')) is-invalid @endif" id="account_number" name="account_number" value="{{ old('account_number' , $partner->account_number) }}" >
                            @error('account_number')
                                <div class="invalid-feedback alert-box">
                                    {!! $message !!}
                                </div>
                            @enderror
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Account Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input" id="account_name" name="account_name" value="{{ $partner->account_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Branch Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control track-input @if ($errors->has("branch_name")) is-invalid @endif" id="branch_name" name="branch_name" value="{{ old("branch_name") ?? $partner->account_branch_name }}">
                            @if ($errors->has("branch_name"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("branch_name") }}</span>
                            @endif
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.City Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has("city_name")) is-invalid @endif" value="{{ old("city_name") ?? $partner->account_city_name }}" name="city_name">
                            @if ($errors->has("city_name"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("city_name") }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Disbursement Fee")</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="disbursement_fee" value="{{ old("disbursement_fee") ?? intval($partner->disbursement_fee) }}">
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Disbursement Interval")</label>
                        <div class="col-sm-4">
                            @if (old("disbursement_interval"))
                                <select class="form-control select2" name="disbursement_interval" id="disbursement_interval">
                                    <option value="">Please Choose</option>
                                    @if ($partner->auto_disbursement_interval == "PAYOUT_BY_REQUEST")
                                        <option value="PAYOUT_BY_REQUEST" {{ old("disbursement_interval") == "PAYOUT_BY_REQUEST" ? 'selected' : '' }}>@lang('cms.Payout By Request')</option>
                                    @endif
                                    <option value="DAILY" @if(old("disbursement_interval") == "DAILY") selected @endif>@lang("cms.Daily")</option>
                                    <option value="WEEKLY" @if(old("disbursement_interval") == "WEEKLY") selected @endif>@lang("cms.Weekly")</option>
                                    <option value="ON_HOLD" @if(old("disbursement_interval") == "ON_HOLD") selected @endif>@lang("cms.On_Hold")</option>
                                </select>
                            @else
                                <select class="form-control select2" name="disbursement_interval" id="disbursement_interval">
                                    <option value="">Please Choose</option>
                                    @if ($partner->auto_disbursement_interval == "PAYOUT_BY_REQUEST")
                                        <option value="PAYOUT_BY_REQUEST" {{ $partner->auto_disbursement_interval == "PAYOUT_BY_REQUEST" ? 'selected' : '' }}>@lang('cms.Payout By Request')</option>
                                    @endif
                                    <option value="DAILY" @if($partner->auto_disbursement_interval == "DAILY") selected @endif>@lang("cms.Daily")</option>
                                    <option value="WEEKLY" @if($partner->auto_disbursement_interval == "WEEKLY") selected @endif>@lang("cms.Weekly")</option>
                                    <option value="ON_HOLD" @if($partner->auto_disbursement_interval == "ON_HOLD") selected @endif>@lang("cms.On_Hold")</option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Transfer Type")</label>
                        <div class="col-sm-4">
                            @if (old("transfer_type"))
                                <select class="form-control select2" name="transfer_type" id="transfer_type">
                                    <option value="">Please Choose</option>
                                    <option value="LLG" @if(old("transfer_type") == "LLG") selected @endif>@lang("cms.LLG")</option>
                                    <option value="RTGS" @if(old("transfer_type") == "RTGS") selected @endif>@lang("cms.RTGS")</option>
                                </select>
                            @else
                                <select class="form-control select2" name="transfer_type" id="transfer_type">
                                    <option value="">Please Choose</option>
                                    <option value="LLG" @if($partner->transfer_type == "LLG") selected @endif>@lang("cms.LLG")</option>
                                    <option value="RTGS" @if($partner->transfer_type == "RTGS") selected @endif>@lang("cms.RTGS")</option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Partner Parking Account Number")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2 @if ($errors->has("partner_parking_account_number")) is-invalid @endif" name="partner_parking_account_number" id="partner_parking_account_number">
                                <option value="" disabled selected>@lang("cms.Select One")</option>
                                @if (old("partner_parking_account_number"))
                                    @foreach($bank_account_list as $partner_parking)
                                        <option value="{{ $partner_parking->id }}" data-account-number="{{ $partner_parking->account_number }}" data-account-name="{{ $partner_parking->account_name }}" @if(old('partner_parking_account_number') == $partner_parking->id) selected @endif>{{ $partner_parking->name.' - '.$partner_parking->account_number }}</option>
                                    @endforeach
                                @else
                                    @foreach($bank_account_list as $partner_parking)
                                        <option value="{{ $partner_parking->id }}" data-account-number="{{ $partner_parking->account_number }}" data-account-name="{{ $partner_parking->account_name }}" @if($partner->rek_parking_bank_account_id == $partner_parking->id) selected @endif>{{ $partner_parking->name.' - '.$partner_parking->account_number }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <label for="partner_owner_name" class="col-form-label col-sm-2">@lang("cms.Partner Owner Name")</label>
                        <div class="col-sm-4">
                            <input id="partner_owner_name" name="partner_owner_name" class="form-control" value="{{ $partner->rek_parking_account_name }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="partner_owner_number" class="col-form-label col-sm-2">@lang("cms.Partner Owner Number")</label>
                        <div class="col-sm-4">
                            <input id="partner_owner_number" name="partner_owner_number" class="form-control" value="{{ $partner->rek_parking_account_number }}" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.SNAP QRIS Enable")</label>
                        <input type="checkbox" id="is_snap_enabled" name="is_snap_enabled" class="form-group" @if(old("is_snap_enabled", $partner->is_snap_enabled)) checked @endif>
                    </div>

                    <div id="snap_detail" @if(! old("is_snap_enabled", $partner->is_snap_enabled)) hidden @endif>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Client ID")</label>
                            <div class="col-sm-4">
                                <input type="text" readonly name="snap_client_id" class="form-control" id="snap_client_id" value="{{ old("snap_client_id", $partner->snap_client_id) }}">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-primary" type="button" id="btn-generate-client-id">@lang("cms.Generate")</button>
                                <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Client Secret")</label>
                            <div class="col-sm-4">
                                <input type="text" readonly name="snap_client_secret" class="form-control" id="snap_client_secret" value="{{ old("snap_client_secret", $partner->snap_client_secret) }}">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-primary" type="button" id="btn-generate-client-secret">@lang("cms.Generate")</button>
                                <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Public Key Partner")</label>
                            <div class="col-sm-4">
                                <textarea type="text" class="form-control" name="snap_public_key">{{ $partner->snap_public_key }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.URL B2B Access Token")</label>
                            <div class="col-sm-2">
                                <input type="text" placeholder="https://" class="form-control" name="qr_access_token_notify_base_url" value="{{ $partner->qr_access_token_notify_base_url }}">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder="/v1.0/access-token/b2b" class="form-control" name="qr_access_token_notify_relative_path" value="{{ $partner->qr_access_token_notify_relative_path }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.URL MPM Notify")</label>
                            <div class="col-sm-2">
                                <input type="text" placeholder="https://" class="form-control" name="qr_mpm_notify_base_url" value="{{ $partner->qr_mpm_notify_base_url }}">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder="/v1.0/qr/qr-mpm-notify" class="form-control" name="qr_mpm_notify_relative_path" value="{{ $partner->qr_mpm_notify_relative_path }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Client ID Partner")</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Client ID Partner" class="form-control" name="snap_notify_client_id" value="{{ $partner->snap_notify_client_id }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Client Secret Partner")</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Client Secret Partner" class="form-control" name="snap_notify_client_secret" value="{{ $partner->snap_notify_client_secret }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">@lang("cms.Channel ID")</label>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Channel ID" class="form-control" name="channel_id" value="{{ $partner->channel_id }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Webhook URL (API QRIS Registration)")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has("webhook_url_api_qris_registration")) is-invalid @endif" value="{{ $partner->webhook_url_api_qris_registration }}" name="webhook_url_api_qris_registration">
                            @if ($errors->has("webhook_url_api_qris_registration"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("webhook_url_api_qris_registration") }}</span>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="d-flex justify-content-center mb-5">
                    <button class="btn btn-primary btn-block col-3 btn-submit" id="btn-submit" type="submit">@lang("cms.Submit")</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let oldValues = {};

        $(document).ready(function() {
            $('input, select, textarea').each(function() {
                let fieldName = $(this).attr('id') || $(this).attr('name');
                if (fieldName) {
                    oldValues[fieldName] = $(this).val();
                }
            });

            $('#owner_id').select2({
                minimumResultsForSearch: 5,
                width: '100%',
                dropdownAutoWidth: true,
                theme: 'default'
            });

            var initialOwnerId = $('#owner_id').val();

            if (initialOwnerId) {
                fetchOwnerData(initialOwnerId);
            }

            $('#owner_id').on('change', function() {
                var ownerId = $(this).val();
                if (ownerId) {
                    fetchOwnerData(ownerId);
                } else {
                    $('#owner_details').hide();
                }
            });

            // Fungsi untuk mem-fetch data owner
            function fetchOwnerData(ownerId) {
                showLoadingSpinner();
                $.ajax({
                    url: '{{ route('yukk_co.owners.get_owner', ['id' => ':id']) }}'.replace(':id', ownerId),
                    type: 'GET',
                    data: { id: ownerId },
                    success: function(response) {
                        hideLoadingSpinner();
                        ownerData = response.owner;
                        $('#owner_details').show();

                        $('#owner_name_label').text(ownerData.name || '-');
                        $('#owner_id_label').text('ID: ' + (ownerData.id || '-'));
                        $('#owner_name').text('Name: ' + (ownerData.name || '-'));
                        $('#owner_phone').text('Phone: ' + (ownerData.phone || '-'));
                        $('#owner_email').text('Email: ' + (ownerData.email || '-'));
                        $('#owner_ktp').text('KTP: ' + (ownerData.id_card_number || '-'));
                        $('#owner_npwp').text('NPWP: ' + (ownerData.npwp_number || '-'));

                        if($('#owner_fill_information').prop('checked')){
                            $('#pic_name').val(ownerData.name);
                            $('#pic_email').val(ownerData.email);
                            $('#pic_phone').val(ownerData.phone);
                        }

                        // if (ownerData.has_bank_account) {
                        //     $('#owner_fill_rekening').prop('disabled', false);

                        //     if($('#owner_fill_rekening').prop('checked')){
                        //         $('#bank_id').val(ownerData.bank_id).trigger('change').prop('disabled', true);
                        //         $('#account_number').val(ownerData.bank_account_number).prop('disabled', true);
                        //         $('#account_name').val(ownerData.bank_account_name).prop('disabled', true);
                        //         $('#branch_name').val(ownerData.bank_account_branch).prop('disabled', true);
                        //     }
                        // } else {
                        //     $('#owner_fill_rekening').prop('disabled', true);
                        //     $('#owner_fill_rekening').prop('checked', false);
                        //     $('#bank_id').val(oldValues['bank_id']).trigger('change').prop('disabled', false);
                        //     $('#account_number').val(oldValues['account_number']).prop('disabled', false);
                        //     $('#account_name').val(oldValues['account_name']).prop('disabled', false);
                        //     $('#branch_name').val(oldValues['branch_name']).prop('disabled', false);
                        // }
                    },
                    error: function(xhr, status, error) {
                        hideLoadingSpinner();
                        Swal.fire({
                            title: "Error fetching owner",
                            text: "Data reading is not successful.",
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }
        });

        $('.track-input').on('input', function() {
            let fieldName = $(this).attr('id') || $(this).attr('name');
            oldValues[fieldName] = $(this).val();
        });

        // $('#owner_fill_rekening').on('change', function (e) {
        //     if (e.target.checked && ownerData) {
        //         $('#bank_id').val(ownerData.bank_id).trigger('change').prop('disabled', true);
        //         $('#account_number').val(ownerData.bank_account_number).prop('disabled', true);
        //         $('#account_name').val(ownerData.bank_account_name).prop('disabled', true);
        //         $('#branch_name').val(ownerData.bank_account_branch).prop('disabled', true);
        //     }else{
        //         $('#bank_id').val(oldValues['bank_id']).trigger('change').prop('disabled', false);
        //         $('#account_number').val(oldValues['account_number']).prop('disabled', false);
        //         $('#account_name').val(oldValues['account_name']).prop('disabled', false);
        //         $('#branch_name').val(oldValues['branch_name']).prop('disabled', false);
        //     }
        // });

        $('#owner_fill_information').on('change', function (e) {
            if (e.target.checked && ownerData) {
                $('#pic_name').val(ownerData.name);
                $('#pic_email').val(ownerData.email);
                $('#pic_phone').val(ownerData.phone);
            }else{
                $('#pic_name').val(oldValues['pic_name']    );
                $('#pic_email').val(oldValues['pic_email']);
                $('#pic_phone').val(oldValues['pic_phone']);
            }
        });

        $("#is_snap_enabled").change(function(e) {
            if ($("#is_snap_enabled").is(":checked")) {
                $("#snap_detail").removeAttr("hidden");
                $("#snap_detail input").removeAttr("disabled");
                $("#snap_detail textarea").removeAttr("disabled");
            } else {
                $("#snap_detail").attr("hidden", "hidden");
                $("#snap_detail input").attr("disabled", "disabled");
                $("#snap_detail textarea").attr("disabled", "disabled");
            }
        });

        $("#bank_id").change(function(e) {
                var self = $(this);
                $("#bank_type").val(self.find(':selected').data('bank-type'));
                var bankName = $('#bank_id option:selected').text();
                if (bankName == "Bank Central Asia") {
                     $("#bank_type").val("BCA").trigger('change');
                } else {
                     $("#bank_type").val("NON_BCA").trigger('change');
                }
            });

        function generateClientId(length = 24) {
            var result           = [];
            var characters       = '0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
            }
            return result.join('');
        }
        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
        function generateClientSecret(length = 24) {
            var result           = [];
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
            }
            return Base64.encode(result.join(''));
        }

        $("#btn-generate-client-id").click(function(e) {
            var clientId = generateClientId();
            $("#snap_client_id").val(clientId);
            e.preventDefault();
        });

        $("#btn-generate-client-secret").click(function(e) {
            var clientSecret = generateClientSecret();
            $("#snap_client_secret").val(clientSecret);
            e.preventDefault();
        });

        $("#btn-copy-client-id").click(function() {
            // Get the text field
            var copyText = $("#snap_client_id");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-copy-client-secret").click(function() {
            // Get the text field
            var copyText = $("#snap_client_secret");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-submit").click(function(e) {
            $('#bank_type').removeAttr('disabled');
            if (window.confirm("Are you sure you want to do this action?")) {
            } else {
                e.preventDefault();
            }
        });

        $("#partner_parking_account_number").change(function(e) {
            var selectedOption = $(this).find(':selected');

            var account_number = selectedOption.data('account-number');
            var account_name = selectedOption.data('account-name');

            document.getElementById('partner_owner_name').value = account_name;
            document.getElementById('partner_owner_number').value = account_number;
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
