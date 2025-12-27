@extends('layouts.master')

@section('header')
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
        }
    </style>

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                @if($type == 'show')
                    <h4>@lang('cms.Request Detail')</h4>
                @else
                    <h4>@lang('cms.Request Edit')</h4>
                @endif
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.data_verification.list') }}" class="breadcrumb-item">
                        @lang('cms.Request List')</a>
                    <span class="breadcrumb-item active">
                        @if($type == 'show') @lang('cms.Request Detail') @else @lang('cms.Request Edit') @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form id="mainForm" method="POST" action="{{ route('yukk_co.data_verification.update', $id) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $data->source }}" name="source" id="source">
                <input type="hidden" value="{{ $m_type }}" name="merchant_hidden" id="merchant_hidden">
                <div class="row">
                    <div class="col-md-6">
                        @if($data->merchant->merchant == null)
                            <div class="col-md-12">
                            <h4 class="font-weight-bold">@lang("cms.Merchant")</h4>

                            <div class="row">
                                <label for="merchantType" class="col-sm-6 control-label">@lang("cms.Type")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantType" id="merchantType" class="form-control select2" required @if($type == 'show') disabled @endif>
                                        @foreach($merchant_types as $merchant_type)
                                            <option @if($data->merchant->type == $merchant_type['value']) selected @endif value="{{ $merchant_type['value'] }}">
                                                {{ @$merchant_type['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="detailType" class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="detailMerchantType" id="cvFirma" value="CV/FIRMA" disabled
                                        @if($data->merchant->detail_type == 'CV/FIRMA') checked @endif>
                                    <label class="form-check-label" for="cvFirma">CV / Firma</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="detailMerchantType" id="ptYayasanKoperasi" value="PT/YAYASAN/KOPERASI" disabled
                                        @if($data->merchant->detail_type == 'PT/YAYASAN/KOPERASI') checked @endif>
                                    <label class="form-check-label" for="ptYayasanKoperasi">PT / Yayasan / Koperasi</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="detailMerchantType" id="other" value="OTHER" disabled
                                        @if($data->merchant->detail_type == 'OTHER') checked @endif>
                                    <label class="form-check-label" for="other">Organisasi Lain</label>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input required type="text" id="merchantName" name="merchantName" class="form-control" value="{{ @$data->merchant->name }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Criteria")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantCriteria" id="merchantCriteria" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($criterias as $criteria)
                                            <option value="{{ $criteria['code'] }}" @if($data->merchant->criteria == $criteria['code']) selected @endif>
                                                {{ @$criteria['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Category")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantCategory" id="merchantCategory" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($data->merchant->category_id == $category->id) selected @endif>{{ @$category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.MCC")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantMcc" id="merchantMcc" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($mccs as $mcc)
                                            <option value="{{ $mcc->id }}" @if($data->merchant->mcc_id == $mcc->id) selected @endif>{{ @$mcc->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.MDR QRIS Category")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="mdrFee" id="mdrFee" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($mdrFees as $mdrFee)
                                            <option value="{{ $mdrFee->id }}" @if($data->bank_account->mdr_fee == $mdrFee->id) selected @endif>{{ $mdrFee->mdr_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Social Network")</label>
                                <div class="col-sm-6">
                                    <input type="text" id="facebook" name="facebook" placeholder="Facebook" class="form-control" value="{{ @$data->facebook_url }}" autofocus
                                           @if($type == "show") readonly @endif>
                                    <input type="text" id="instagram" name="instagram" placeholder="Instagram" class="form-control mt-2" value="{{ @$data->instagram_url }}" autofocus
                                           @if($type == "show") readonly @endif>
                                    <input type="text" id="website" name="website" placeholder="Website" class="form-control mt-2" value="{{ @$data->website_url }}" autofocus
                                           @if($type == "show") readonly @endif>
                                    <input type="text" id="other" name="other" placeholder="Lainnya" class="form-control mt-2" value="{{ @$data->other_url }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File Merchant Logo")</label>
                                <div class="col-sm-3">
                                    @if($logo_path)
                                        <img id="img_logo" alt="logo_file" src="{{ $logo_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_logo" name="file_logo" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif>
                                    <input type="hidden" id="file_logo_old" name="file_logo_old"/>
                                </div>
                                @if($logo_path)
                                    <div class="col text-right">
                                        <a id="btn_logo" href="{{ $logo_path }}" class="col-md-6 btn btn-success" target="_blank" type="button" style="margin-bottom: 5px">Download</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File Location Image")</label>
                                <div class="col-sm-3">
                                    @if($store_path)
                                        <img id="img_location" alt="file_location" src="{{ $store_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_store_photo" name="file_store_photo" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif>
                                    <input type="hidden" id="file_store_photo_old" name="file_store_photo_old"/>
                                </div>
                                @if($store_path)
                                    <div class="col text-right">
                                        <a id="btn_store_photo" href="{{ $store_path }}" class="col-md-6 btn btn-success" target="_blank" type="button" style="margin-bottom: 5px;">Download</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File Social Media")</label>
                                <div class="col-sm-3">
                                    @if($platform_screenshot_path)
                                        <img id="img_platform_screenshot" alt="platform_screenshot_file" src="{{ $platform_screenshot_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_platform_screenshot" name="file_platform_screenshot" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif>
                                    <input type="hidden" id="file_platform_screenshot_old" name="file_platform_screenshot_old"/>
                                </div>
                                @if($platform_screenshot_path)
                                    <div class="col text-right">
                                        <a id="btn_platform_screenshot" href="{{ $platform_screenshot_path }}" class="col-md-6 btn btn-success" target="_blank" type="button" style="margin-bottom: 5px;">Download</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                            <hr>
                        @endif

                        <div class="col-md-12">
                            <h4 class="font-weight-bold">@lang("cms.Merchant Branch")</h4>

                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantBranchName" name="merchantBranchName" class="form-control" value="{{ $data->merchant->branch_name }}" autofocus
                                           @if($type == "show") readonly @endif required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang('cms.Start Date')<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" autocomplete="off" id="start_date" name="start_date" class="form-control" value="{{ @$data->merchant->branch_start_date }}" autofocus
                                           @if($type == "show") disabled @endif required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang('cms.End Date')<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" autocomplete="off" id="end_date" name="end_date" class="form-control" value="{{ @$data->merchant->branch_end_date }}" autofocus
                                           @if($type == "show") disabled @endif required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Address")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantAddress" name="merchantAddress" class="form-control" required value="{{ @$data->merchant->address }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.RT")</label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantRt" name="merchantRt" class="form-control" value="{{ @$data->merchant->rt }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.RW")</label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantRw" name="merchantRw" class="form-control" value="{{ @$data->merchant->rw }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Province")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantProvince" id="merchantProvince" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" @if($data->merchant->province_id == $province->id) selected @endif>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.City")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantCity" id="merchantCity" required class="form-control select2" @if($type == "show") disabled @endif>
                                        @foreach($merchantCities as $city)
                                            <option value="{{ $city->id }}" @if($data->merchant->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Region")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="merchantRegion" id="merchantRegion" class="form-control select2" required @if($type == "show") disabled @endif>
                                        @foreach($merchantRegions as $region)
                                            <option value="{{ $region->id }}" @if($data->merchant->region_id == $region->id) selected @endif>{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Village")</label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantVillage" name="merchantVillage" class="form-control" value="{{ $data->merchant->village }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Postal Code")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantPostalCode" name="merchantPostalCode" class="form-control" value="{{ $data->merchant->postal_code }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">Latitude</label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantLatitude" name="merchantLatitude" class="form-control" value="{{ $data->merchant->latitude }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">Longitude</label>
                                <div class="col-sm-6">
                                    <input type="text" id="merchantLongitude" name="merchantLongitude" class="form-control" value="{{ $data->merchant->longitude }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                        </div>

                        @if($data->merchant->beneficiary == null)
                            <hr>

                            <div class="col-md-12">
                            <h4 class="font-weight-bold">@lang("cms.Beneficiary")</h4>

                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerName" name="ownerName" class="form-control" required value="{{ @$data->owner->name }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">Is Whitelist<span class="required text-danger requiredIconKTP"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="checkbox" id="isWhitelist" name="isWhitelist" @if(isset($data->is_whitelist) && $data->is_whitelist == 1) checked @endif
                                           @if($type == "show") disabled @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.KTP No")<span class="required text-danger requiredIconKTP"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerKTP" name="ownerKTP" class="form-control" required value="{{ @$data->owner->ktp_number }}" autofocus
                                           @if($type == "show") readonly @endif>
                                    <div class="invalid-feedback alert-box"></div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.NPWP No")<span id="requiredIconNpwp" class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerNPWP" name="ownerNPWP" class="form-control" required value="{{ @$data->owner->npwp_number }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Phone Number")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerPhoneNumber" name="ownerPhoneNumber" class="form-control" required value="{{ @$data->owner->phone_number }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Email")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerEmail" name="ownerEmail" class="form-control" value="{{ @$data->owner->email }}" required  @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Address")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="ownerAddress" name="ownerAddress" required class="form-control" value="{{ @$data->owner->address }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3" hidden>
                                <label class="col-sm-6 control-label">@lang("cms.Province")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input name="ownerProvince" id="ownerProvince" class="form-control select2" value="{{ @$ownerCities->province_id }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.City")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="ownerCity" id="ownerCity" class="form-control select2" required @if($type == "show") disabled @endif>
                                        <option value="{{ @$ownerCities->id }}" selected>{{ @$ownerCities->name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Bank Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="bank" id="bank" value="{{ $data->bank_account->bank->id }}">
                                    <select class="form-control select2" disabled>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" @if($bank->id == $data->bank_account->bank->id) selected @endif>{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Account Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="accountHolderName" name="accountHolderName" class="form-control" value="{{ @$data->bank_account->account_name }}" autofocus
                                           readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Account Number")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="accountNumber" name="accountNumber" class="form-control" value="{{ @$data->bank_account->account_number }}" autofocus
                                           readonly>
                                    <div class="invalid-feedback alert-box"></div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Bank Branch Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="bankBranch" name="bankBranch" class="form-control" value="{{ @$data->bank_account->branch_name }}" autofocus
                                           readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Bank Area Name")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="locationBankBranch" name="locationBankBranch" class="form-control" value="{{ @$data->bank_account->branch_area_name }}" autofocus
                                           readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Disbursement Interval")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="disbursementIntervalList" id="disbursementIntervalList" value="{{ $data->bank_account->disbursement_interval }}">
                                    <select class="form-control select2" disabled>
                                        <option value="">@lang("cms.pleaseChoose")</option>
                                        @foreach($disbursements as $disbursement)
                                            <option value="{{ $disbursement['value'] }}" @if($data->bank_account->disbursement_interval == $disbursement['value']) selected @endif>{{ $disbursement['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-6 control-label">@lang("cms.Bank Type")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="bankType" name="bankType" class="form-control" value="{{ @$data->bank_account->bank->name == 'Bank Central Asia' ? "BCA" : "NON BCA" }}" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label for="disbursementFee" class="col-sm-6 control-label">@lang("cms.Disbursement Fee")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="number" id="disbursementFee" name="disbursementFee" class="form-control" required value="{{ @$data->bank_account->disbursement_fee }}" autofocus
                                           @if($type == "show") readonly @endif>
                                </div>
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File KTP")<span class="required text-danger requiredIconKTP"> *</span></label>
                                <div class="col-sm-3">
                                    @if($ktp_path)
                                        <img id="img_ktp" alt="ktp_file" src="{{ $ktp_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_ktp" name="file_ktp" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif @if(! $ktp_path) required @endif>
                                    <input type="hidden" value="{{ $ktp_path }}" id="file_ktp_old" name="file_ktp_old"/>
                                    <button id="preview_ocr_button" class="btn btn-primary" style="display: none;">Preview OCR</button>
                                </div>
                                @if($ktp_path)
                                    <div class="col text-right">
                                        <a id="btn_download_ktp" target="_blank" href="{{ $ktp_path }}" download="{{ $ktp_path }}" class="col-md-6 btn btn-success" style="margin-bottom: 5px;">Download</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.Face Photo") <span id="fp-danger" class="text-danger" hidden> *</span>
                                </label>
                                <div class="col-sm-3">
                                    @if($face_photo_path)
                                        <img id="img_face_photo" alt="file_face_photo" src="{{ $face_photo_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_face_photo" name="file_face_photo" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif>
                                    <input type="hidden" value="{{ @$face_photo_path }}" id="file_face_photo_old" name="file_face_photo_old"/>
                                </div>
                                @if($face_photo_path)
                                    <div class="col text-right">
                                        <a id="btn_thumbnail" href="{{ $face_photo_path }}" class="col-md-6 btn btn-success" type="button" style="margin-bottom: 5px;">Download</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File NPWP") <span id="npwp-danger" class="text-danger" hidden> *</span> </label>
                                <div class="col-sm-3">
                                    @if($npwp_path)
                                        <img id="img_npwp" alt="npwp_file" src="{{ $npwp_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_npwp" name="file_npwp" class="form-control" value="" autofocus
                                           @if($type == "show") disabled @endif>
                                        <input type="hidden" value="{{ @$npwp_path }}" id="file_npwp_old" name="file_npwp_old"/>
                                </div>
                                @if($npwp_path)
                                    <div class="col text-right">
                                        <a id="btn_npwp" href="{{ $npwp_path }}" class="col-md-6 btn btn-success" type="button" style="margin-bottom: 5px;">Download</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3" style="margin-bottom: 15px;">
                                <label class="col-sm-4 control-label">@lang("cms.File Account Book Cover")<span class="required text-danger"> *</span></label>
                                <div class="col-sm-3">
                                    @if($book_account_cover_path)
                                        <img id="img_bank_account_cover" alt="bank_account_cover_file" src="{{ $book_account_cover_path }}" style="max-width: 250px; margin-bottom: 10px;"/>
                                    @else
                                        <label>[No File]</label>
                                    @endif
                                    <input type="file" id="file_account_book_cover" name="file_account_book_cover" @if(! $book_account_cover_path) required @endif class="form-control" value="" autofocus
                                    @if($type == "show") disabled @endif>
                                    <input type="hidden" id="file_account_book_cover_old" name="file_account_book_cover_old"/>
                                </div>
                                @if($book_account_cover_path)
                                    <div class="col text-right">
                                        <a id="btn_account_book_cover" href="{{ $book_account_cover_path }}" class="col-md-6 btn btn-success" type="button" style="margin-bottom: 5px">Download</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <input hidden id="hidden-merchant-id" value="{{ @$data->merchant->merchant->id }}">
                        <input hidden id="hidden-beneficiary-id" value="{{ @$data->merchant->beneficiary->id }}">
                        @if($data->merchant->merchant !== null)
                            <div class="card">
                                <h2 class="text-left card bg-black font-weight-bold px-2 py-1">
                                    @lang("cms.Merchant")
                                </h2>

                                <blockquote class="blockquote">
                                    <div class="row">
                                        <span class="col-3 mx-2">@lang("cms.Name")</span>
                                        <span>{{ @$data->merchant->merchant->name }}</span>
                                    </div>
                                    <div class="row mt-2">
                                        <span class="col-3 mx-2">@lang("cms.Criteria")</span>
                                        <span>{{ @$data->merchant->merchant->merchant_criteria }}</span>
                                    </div>
                                    <hr class="mx-2">
                                    <a class="btn btn-primary float-right mr-2 col-md-3" href="{{ route('yukk_co.merchant.show', $data->merchant->merchant->id) }}">
                                        @lang('cms.Detail')
                                    </a>
                                </blockquote>
                            </div>
                        @endif
                        @if($data->merchant->beneficiary !== null)
                            <div class="card">
                                <h2 class="text-left card bg-black font-weight-bold px-2 py-1">
                                    @lang("cms.Beneficiary")
                                </h2>

                                <blockquote class="blockquote">
                                    <div class="row">
                                        <span class="col-3 mx-2">@lang("cms.Name")</span>
                                        <span>{{ @$data->merchant->beneficiary->name }}</span>
                                    </div>
                                    <div class="row mt-2">
                                        <span class="col-3 mx-2">Is Whitelist</span>
                                        <input type="checkbox" id="isWhitelist" name="isWhitelist" @if(isset($data->is_whitelist) && $data->is_whitelist) checked @endif @if($type == "show") disabled @endif>
                                    </div>
                                    <div class="row mt-2">
                                        <span class="col-3 mx-2">@lang("cms.KTP")</span>
                                        <span>{{ @$data->merchant->beneficiary->ktp_no }}</span>
                                    </div>
                                    <hr class="mx-2">
                                    <a class="btn btn-primary float-right mr-2 col-md-3" href="{{ route('yukk_co.customers.detail', $data->merchant->beneficiary->id) }}">
                                        @lang('cms.Detail')
                                    </a>
                                </blockquote>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <center>
                            <a href="{{ route('yukk_co.data_verification.list') }}" class="btn btn-default">Back</a> &nbsp;
                            @if($type == "edit")
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            @endif
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal ktp details -->
    <div class="modal fade" id="ktpDetailsModal" tabindex="-1" role="dialog" aria-labelledby="ktpDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ktpDetailsModalLabel">KTP Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ktpDetailsContent">
                        <div class="row">
                            <label class="col-sm-3 control-label">NIK</label>
                            <div class="col-sm-9">
                                <input type="text" id="nikModal" name="nik" class="form-control">
                                <p class="error" id="nik_error" style="color: red;"></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" id="nameModal" name="name" class="form-control">
                                <p class="error" id="name_error" style="color: red;"></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 control-label">Birth Date</label>
                            <div class="col-sm-9">
                                <input type="text" id="birthdateModal" name="birthdate" class="form-control">
                                <p class="error" id="birthdate_error" style="color: red;"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button id="change_details" class="btn btn-primary">Change</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <!-- turn into button gorups -->
                    <div class="btn-group" style="width: 100%;">
                        <button id="change_details" class="btn btn-primary">Change</button>
                        <button id="cancel_details" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal ktp details -->
@endsection

@section('post_scripts')
    <script defer>
        async function fetchCitiesAndRegions(url, data) {
            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return await response.json();
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                return null;
            }
        }

        async function populateCitiesAndRegions(provinceSelector, citySelector, regionSelector, cityId = null, regionId = null) {
            const province_id = $(provinceSelector).val();
            const data = { "province_id": province_id };

            const cities = await fetchCitiesAndRegions("{{ route('city.list_to_production') }}", data);
            if (cities) {
                $(citySelector).html("");
                $(citySelector).append("<option value=''>Select City</option>");

                cities.forEach(city => {
                    let option = $("<option></option>");
                    option.html(city.name);
                    option.val(city.id);
                    if (city.id == cityId) {
                        option.attr("selected", true);
                    }
                    $(citySelector).append(option);
                });

                // trigger change event to populate regions
                $(citySelector).change();
            }
        }

        async function populateRegions(citySelector, regionSelector, regionId) {
            const city_id = $(citySelector).val();
            if (!city_id) {
                return;
            }
            const data = { "city_id": city_id };

            const regions = await fetchCitiesAndRegions("{{ route('region.list_to_production') }}", data);
            if (regions) {
                $(regionSelector).html("");
                $(regionSelector).append("<option value=''>Select Region</option>");

                regions.forEach(region => {
                    let option = $("<option></option>");
                    option.html(region.name);
                    option.val(region.id);
                    if (region.id == regionId) {
                        option.attr("selected", true);
                    }
                    $(regionSelector).append(option);
                });
            }

            $(regionSelector).change();
        }

        $(document).ready(function() {
            const type = document.getElementById('merchant_hidden').value;
            if (type){
                if (type == 1){
                    $("#detailType").attr('hidden', 'hidden');
                    $("#detailType input").attr('disabled', 'disabled');

                    $("#npwp-danger").attr('hidden', 'hidde');
                    $("#fp-danger").removeAttr('hidden');
                    $('#requiredIconNpwp').attr('hidden', 'hidden');
                    $('#ownerNPWP').attr('required', 'required');

                    $('.requiredIconKTP').removeAttr('hidden');
                    $('#ownerKTP').attr('required', 'required');
                    $('#file_ktp').attr('required', 'required');
                }else{
                    $('#detailType').removeAttr('hidden');
                    $("#detailType input").removeAttr("disabled");

                    $("#fp-danger").attr("hidden", "hidden");
                    $("#npwp-danger").removeAttr("hidden");
                    $('#requiredIconNpwp').removeAttr("hidden");
                    $('#ownerNPWP').removeAttr('required');

                    $('.requiredIconKTP').attr('hidden', 'hidden');
                    $('#ownerKTP').removeAttr('required');
                    $('#file_ktp').removeAttr('required');
                }
            }

            populateCitiesAndRegions('#merchantProvince', '#merchantCity', '#merchantRegion', '{{ $data->merchant->city_id }}', '{{ $data->merchant->region_id }}');

            $('#merchantProvince').change(function () {
                var self = $(this);
                populateCitiesAndRegions('#merchantProvince', '#merchantCity', '#merchantRegion');
            })

            $('#merchantCity').change(function () {
                populateRegions('#merchantCity', '#merchantRegion', '{{ $data->merchant->region_id }}');
            })

            $("#start_date").daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $("#end_date").daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $("#merchantType").change(function () {
                let merchant_type =  $("#merchantType").find(':selected')[0].text;
                const beneficiary_exist = {{ $data->merchant->beneficiary ? true : false }};

                if (merchant_type === 'BADAN HUKUM') {
                    $('#detailType').removeAttr('hidden');
                    $("#detailType input").removeAttr("disabled");

                    $("#fp-danger").attr("hidden", "hidden");
                    $("#npwp-danger").removeAttr("hidden");
                    $('#requiredIconNpwp').removeAttr("hidden");
                    $('#ownerNPWP').removeAttr('required');

                    $('.requiredIconKTP').attr('hidden', 'hidden');
                    $('#ownerKTP').removeAttr('required');
                    $('#file_ktp').removeAttr('required');

                    if (! beneficiary_exist){
                        let npwp_file = document.getElementById('file_npwp_old').value;

                        if (! npwp_file){
                            $("#file_npwp").attr("required", "required");
                            $("#file_face_photo").removeAttr("required");
                        }else{
                            $("#file_face_photo").removeAttr('required');
                            $("#file_npwp").removeAttr("required");
                        }
                    }
                } else {
                    $("#detailType").attr('hidden', 'hidden');
                    $("#detailType input").attr('disabled', 'disabled');

                    $("#npwp-danger").attr('hidden', 'hidde');
                    $("#fp-danger").removeAttr('hidden');
                    $('#requiredIconNpwp').attr('hidden', 'hidden');
                    $('#ownerNPWP').attr('required', 'required');

                    $('.requiredIconKTP').removeAttr('hidden');
                    $('#ownerKTP').attr('required', 'required');
                    $('#file_ktp').attr('required', 'required');

                    if (! beneficiary_exist){
                        let face_photo_file = document.getElementById('file_face_photo_old').value;
                        let ktp_file = document.getElementById('file_ktp_old').value;

                        if(ktp_file){
                            $("#file_ktp").removeAttr("required");
                        }else{
                            $("#file_ktp").attr("required", "required");
                        }

                        if (! face_photo_file){
                            $("#file_face_photo").attr("required", "required");
                            $("#file_npwp").removeAttr("required");
                        }else{
                            $("#file_face_photo").removeAttr('required');
                            $("#file_npwp").removeAttr("required");
                        }
                    }
                }
            }).change();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var verihubs = {!! json_encode($verihubsData) !!};
        var dttot = {!! json_encode($dttotData) !!};
        var is_edit = '{{ $type == "edit" }}';
        var fileKTP = null;
        var blob;
        var filename;
        var fileSelfie = null;
        var selfieBlob;
        var selfieFilename;
        var selfieImageData;
        var imageData;
        var fileKTPChanged = false;

        async function hitOcr(keepDownloadButton=false){
            blob = null;
            filename = null;
            selfieBlob = null;
            selfieFilename = null;
            // remove all ocrKtp error message if any
            $('#ocr_error').remove();
            // remove ocr details if any
            $('#ocr_details').remove();

            $('#img_ktp').after('<div id="ocr_details"></div>');
            // add input hidden fields to store the ocr details
            $('#ocr_details').append('<input type="hidden" id="nik" name="nik" value="">');
            $('#ocr_details').append('<input type="hidden" id="identity_name" name="identity_name" value="">');
            $('#ocr_details').append('<input type="hidden" id="birthdate" name="birthdate" value="">');
            $('#ocr_details').hide();
            // Get the file KTP
            fileKTP = $('#file_ktp')[0]?.files[0];
            fileSelfie = $('#file_selfie')[0]?.files[0];
            imageData = $('#img_ktp').attr('src');
            selfieImageData = $('#img_face_photo').attr('src');
            if (!fileKTP) {
                if (!imageData) {
                    swal("Please select a file or upload an image.");
                    return;
                }
                let result = await fetchKTP(imageData);
                if (result) {
                    blob = result.blob;
                    filename = result.filename;
                }
            } else {
                filename = fileKTP.name;
            }

            if (!fileSelfie) {
                if(!selfieImageData){
                    $('#file_selfie').after('<span id="file_selfie_ocr_error" class="error" style="color: red;">Please upload a valid Selfie file.</span>');
                }
                let result = await fetchSelfie(selfieImageData, selfieFilename);
                if (result) {
                    selfieBlob = result.blob;
                    selfieFilename = result.filename;
                }
            } else {
                selfieFilename = fileSelfie.name;
            }

            // Prepare form data
            let formData = new FormData();
            formData.append('file_ktp', blob || fileKTP);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('requestId', '{{ isset($item) ? $item->id : '' }}');
            formData.append('filename', filename);
            formData.append('targetType', 'CUSTOMER');


            function showOCRError(message=null){
                Swal.fire({
                    title: "OCR Error",
                    text: "Data reading is not successful. Please retry the file upload.",
                    icon: "error",
                    button: "OK",
                });
                if(!message) {
                    message = "Unknown error.";
                }
                $('#file_ktp').after('<span id="ocr_error" class="error" style="color: red;">' + message + '</span>');
            }

            function resetIfError(message=null){
                $('#nik').val('');
                $('#identity_name').val('');
                $('#birthdate').val('');
                $('#ocr_details').hide();
                // remove preview button
                $('#preview_ocr_button').hide();
                $('#btn_download_ktp').remove();
                showOCRError(message);
            }

            if ($('#isWhitelist').is(':checked') && !fileKTP) {
                return;
            } else {
                if(!(verihubs && dttot && !fileKTPChanged)){
                    // show loading spinner overlay body to prevent user interaction
                    $('body').after('<div class="loading"></div>');
                    // add bootstrap spinner to the loading spinner overlay
                    $('.loading').append('<div class="spinner-border text-primary" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
                }
            }

            // Send AJAX request to the endpoint using the route name
            $.ajax({
                url: "{{ route('electronic_certificate.ocr') }}", // Using Laravel route name
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // remove button download ktp if any
                    if (!keepDownloadButton) {
                        $('#btn_download_ktp').remove();
                    }
                    // Handle the response from the server
                    console.log(response);
                    // Hide the loading spinner overlay
                    $('.loading').remove();
                    // Display the OCR details or perform any other actions
                    let result = response.result
                    if (result){
                        // if response status code != 200, show result message
                        if (response.status_message != "success extract ktp"){
                            resetIfError(result.message);
                            return;
                        }
                        $('#nikModal').val(result.nik);
                        $('#nik').val(result.nik);
                        $('#nameModal').val(result.full_name);
                        $('#identity_name').val(result.full_name);

                        // Format birth date to mm/dd/yyyy
                        var parts = result.date_of_birth.split("-");
                        // Rearrange the parts to mm/dd/yyyy format
                        var birthDate = parts[1] + '/' + parts[0] + '/' + parts[2];
                        $('#birthdateModal').val(birthDate);
                        $('#birthdate').val(birthDate);
                        initiatePreviewButton();
                        // remove error message if any
                        $('#ocr_error').remove();
                        // show image ktp
                        $('#img_ktp').show();
                    } else {
                        resetIfError();
                    }
                },
                error: function(xhr, status, error) {
                    // Hide the loading spinner overlay
                    $('.loading').remove();
                    // Handle the error
                    resetIfError();
                }
            });
            // dont process further if ocr is not successful
            if ($('#ocr_error').text()){
                return;
            }

        }

        function initiatePreviewButton(){
            if(!is_edit){
                $('#preview_ocr_button').hide();
            } else if(verihubs && verihubs == 'verified' && dttot && !fileKTPChanged){
                $('#preview_ocr_button').hide();
                return;
            }
            $('#preview_ocr_button').show();
            $('#preview_ocr_button').css('display', 'block');
            $('#preview_ocr_button').css('max-width', '100%');
            $('#preview_ocr_button').prop('disabled', false);
        }

        // when preview button is clicked show the ocr details
        $('#preview_ocr_button').click(function(event){
            event.preventDefault();
            $('#ocr_details').show();
            $('#ktpDetailsModal').modal('show');

        });


        async function fetchImage(imageData, filename = null, imageType = 'image') {
            // Send a request to fetch the image asynchronously
            const urlImage = "{{ route('yukk_co.customers.image')}}";
            const encodedImageData = encodeURIComponent(imageData);
            const finalUrl = `${urlImage}?url=${encodedImageData}`;

            const response = await fetch(finalUrl);
            console.log(`Fetching ${imageType}... ${finalUrl}`);
            try {

                if (response.ok) {
                    const contentType = response.headers.get('Content-Type');

                    if (contentType.startsWith('image/')) {
                        // It's an image
                        console.log(`${imageType.charAt(0).toUpperCase() + imageType.slice(1)} received.`);

                        const blob = await response.blob();

                        if (!filename) {
                            const decodedImageData = decodeURIComponent(imageData);
                            filename = decodedImageData.split('/').pop().split('?')[0].split('_');
                            // Remove the first element which is the base64 prefix
                            filename.shift();
                        }


                        if(blob){
                            let fileNew = new File([blob], filename,{type:"image/png", lastModified:new Date().getTime()});
                            let container = new DataTransfer();
                            container.items.add(fileNew);
                            document.getElementById(imageType).files = container.files;
                            console.log(imageType, document.getElementById(imageType).files);
                        }

                        return { blob, filename };
                    } else {
                        console.error('The response is not an image.');
                        return null;
                    }
                } else {
                    console.error(`Request failed with status: ${response.status}`);
                    return null;
                }
            } catch (error) {
                console.error(`Request error occurred: ${error}`);
                return null;
            }
        }

        async function fetchKTP(imageData, filename = null) {
            return fetchImage(imageData, filename, 'file_ktp');
        }
        async function fetchSelfie(selfieImageData, selfieFilename = null) {
            return fetchImage(selfieImageData, selfieFilename, 'file_face_photo');
        }

        $(document).ready(function(){
            if ($('#img_face_photo').attr('src') && is_edit){
                let selfieFilename = $('#img_face_photo').attr('src').split('/').pop().split('?')[0];
                fetchSelfie($('#img_face_photo').attr('src'), selfieFilename);
            }

            if ($('#img_ktp').attr('src') && is_edit){
                // move file_ktp before img_ktp
                $('#file_ktp').insertBefore($('#img_ktp'));

                hitOcr(true);
            }
            $('#file_ktp').change(function() {
                fileKTPChanged = true;
                var file = this.files[0];
                $('#file_ktp_error').remove();
                $('#ocr_details').remove();
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#img_ktp').attr('src', e.target.result);
                        $('#img_ktp').attr('style', 'display: block; max-width: 250px; margin-bottom: 10px;');
                        $('#img_ktp').show();
                        $('#labelNone').hide();
                    };
                    reader.readAsDataURL(file);
                    $('#file_ktp').insertBefore($('#img_ktp'));
                    // skip ocr if whitelist is checked
                    if ($('#isWhitelist').is(':checked')){
                        return;
                    }
                    hitOcr();
                } else {
                    $('#img_ktp').attr('src', '');
                    $('#img_ktp').hide();
                    $('#labelNone').show();
                }
            });
            // in case whitelist is changed, trigger ocr again
            $('#isWhitelist').change(function(){
                if (!$('#isWhitelist').is(':checked')){
                    hitOcr();
                } else {
                    $('#preview_ocr_button').hide();
                }
            });

            $(document).on('click', '#cancel_details', function(event) {
                event.preventDefault();
                //close modal
                $('#ktpDetailsModal').modal('hide');
            });

            $(document).on('click', '#change_details', function(event) {
                event.preventDefault();
                let nik = $('#nikModal').val();
                let name = $('#nameModal').val();
                let birthdate = $('#birthdateModal').val();
                $('#nik').val(nik);
                document.getElementById('ownerKTP').value = nik;
                $('#identity_name').val(name);
                $('#birthdate').val(birthdate);

                // Reset error messages
                $('#nik_error').text('');
                $('#name_error').text('');
                $('#birthdate_error').text('');

                // Validate NIK
                if (!(/^\d{16}$/.test(nik))) {
                    $('#nik_error').text('NIK must be exactly 16 digits and numbers only');
                    return;
                }

                // Validate Name
                if (!(/^[a-zA-Z .,']{1,250}$/.test(name))) {
                    $('#name_error').text('Name must contain only contain comma, dot, and apostrophe and space, max 250 characters');
                    return;
                }

                // Validate Birth Date format
                if (!(/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/.test(birthdate))) {
                    $('#birthdate_error').text('Invalid birth date format. Please use mm/dd/yyyy');
                    return;
                }

                swal({
                    title: 'KTP details has been changed',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

                $('#nik_error').text('');
                $('#name_error').text('');
                $('#birthdate_error').text('');

                // hide modal
                $('#ktpDetailsModal').modal('hide');
            });
            $('#mainForm').submit(validateForm);
        });

        async function validateForm(event) {
            // Flag to track overall form validity
            let is_valid = true;
            event.preventDefault(); // Prevent the default form submission

            // Check if Beneficiary ID / Merchant ID is not empty
            let benef_id = document.getElementById('hidden-beneficiary-id').value;
            let merchant_id = document.getElementById('hidden-merchant-id').value;
            
            if(! merchant_id){
                // validate latitude and longitude allow number, dot, and minus
                let latitude = $('#merchantLatitude').val();
                let longitude = $('#merchantLongitude').val();
                // if file ktp has value, file face photo must not be empty
                if ($('#file_ktp').val() && !$('#file_face_photo').val()){
                    $('#img_face_photo').before('<span id="face_photo_error" class="error" style="color: red;">Please re-upload a valid Face Photo file.</span>');
                    $('#file_face_photo').focus();
                    return false;
                } else {
                    $('#face_photo_error').remove();
                }

                if (!(/^-?\d*\.?\d*$/.test(latitude))) {
                    $('#merchantLatitude').after('<span id="latitude_error" class="error" style="color: red;">Latitude must be a number</span>');
                    $('#merchantLatitude').focus();
                    is_valid = false;
                } else {
                    $('#latitude_error').remove();
                }

                if (!(/^-?\d*\.?\d*$/.test(longitude))) {
                    $('#merchantLongitude').after('<span id="longitude_error" class="error" style="color: red;">Longitude must be a number</span>');
                    $('#merchantLongitude').focus();
                    is_valid = false;
                } else {
                    $('#longitude_error').remove();
                }   
            }

            // Select all file input fields
            let fileInputs = ['#file_ktp', '#file_npwp', '#file_face_photo', '#file_account_book_cover', '#file_logo', '#file_store_photo', '#file_platform_screenshot', '#file_thumbnail'];

            // Remove any existing error messages
            $('#mainForm').find('.error').remove();
            
            if(! benef_id){
                // Validate owner phone number
                if ($('#ownerPhoneNumber').val()) {
                    if ($('#ownerPhoneNumber').val().length < 8 || $('#ownerPhoneNumber').val().length > 14) {
                        $('#ownerPhoneNumber').after('<span id="phone_error" class="error" style="color: red;">Phone length must be between 8 and 14 numbers</span>');
                        $('#ownerPhoneNumber').focus();
                        is_valid = false;
                    }
                }

                // Validate owner email
                if (!(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($('#ownerEmail').val()))){
                    $('#ownerEmail').after('<span id="email_error" class="error" style="color: red;">Invalid email format</span>');
                    $('#ownerEmail').focus();
                    is_valid = false;
                }

                // Validate owner KTP
                if ($('#ownerKTP').val()) {
                    if ($('#ownerKTP').val().length !== 16) {
                        $('#ownerKTP').after('<span id="ownerKTP_error" class="error" style="color: red;">NIK must be exactly 16 digits</span>');
                        $('#ownerKTP').focus();
                        is_valid = false;
                    }
                }

                // Validate owner name
                if (!(/^[a-zA-Z .,']{1,250}$/.test($('#ownerName').val()))){
                    $('#ownerName').after('<span id="ownerName_error" class="error" style="color: red;">Name must contain only alphabetical characters</span>');
                    $('#ownerName').focus();
                    is_valid = false;
                }

                // Check if whitelist is not checked and OCR data is not available
                if (!$('#isWhitelist').is(':checked') && !$('#nik').val() && !$('#identity_name').val() && !$('#birthdate').val() && !verihubs &&  !$('#file_ktp_old').val()){
                    $('#file_ktp').after('<span id="ocr_error" class="error" style="color: red;">Please upload a valid KTP file.</span>');
                    $('#file_ktp').focus();
                    is_valid = false;
                }
            }   

            // Validate files asynchronously
            let fileValid = await Promise.all(fileInputs.map(async (fileInput) => {
                let file = $(fileInput)[0] ? $(fileInput)[0].files[0] : null;
                if (file) {
                    try {
                        let validationResult = await checkImageValidation(file, fileInput);
                        console.log(fileInput, validationResult); // Log validation results
                        return validationResult; // Return the validation result
                    } catch (error) {
                        console.error('Error during image validation:', error);
                        return false; // Set file validation to false if an error occurs
                    }
                }
                return true; // Return true if no file is selected (no validation needed)
            }));

            // Check overall form validity
            if (!is_valid || fileValid.includes(false)){
                console.log("Invalid Form");
                return false; // Exit the function if the form is invalid
            }

            // Manually submit the form without using submit() function
            $('#mainForm').attr('enctype', 'multipart/form-data');
            let formData = new FormData($('#mainForm')[0]);

            // change isWhitelist value to 0 if not checked and 1 if checked
            formData.set('isWhitelist', $('#isWhitelist').is(':checked') ? 1 : 0);

            $('body').after('<div class="loading"></div>');
            // add bootstrap spinner to the loading spinner overlay
            $('.loading').append('<div class="spinner-border text-primary" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');

            // Send the form data via AJAX
            $.ajax({
                url: $('#mainForm').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Success:', response);
                    if(response.message && response.message.toLowerCase() != "success"){
                        swal({
                            title: "Update data Success with Warning",
                            text: response.message + ". Will be redirected in 5 seconds.",
                            icon: "warning",
                            button: "OK",
                        });
                        // Redirect on successful response with time delay
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 5000);
                    } else {
                        swal({
                            title: "Update data Success",
                            text: "Data has been updated successfully. Will be redirected in 5 seconds.",
                            icon: "success",
                            button: "OK",
                        });
                        // Redirect on successful response with time delay
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 5000);
                    }
                    // Hide the loading spinner overlay
                    $('.loading').remove();
                },
                error: function(xhr, status, error) {
                    $('.loading').remove();
                    $('#accountNumber').removeClass('is-invalid');
                    $('#accountNumber').removeClass('.invalid-feedback');
                    $('#ownerKTP').removeClass('is-invalid');
                    $('#ownerKTP').removeClass('.invalid-feedback');
                    // Handle errors

                    if (xhr.status === 409) {
                        const response = xhr.responseJSON;
                        if (response.ktp_blacklist) {
                                internalBlacklistError('#ownerKTP', response.ktp_blacklist.status_message);
                            }
                        if (response.cek_rekening) {
                            internalBlacklistError('#accountNumber', response.cek_rekening.status_message);
                        }
                        return;
                    }
                    swal({
                        title: "Error updating data",
                        text: error,
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        }

        //alertnya internal blacklist hehe
        function internalBlacklistError(selector, message) {
            const inputField = $(selector);
            inputField.addClass('is-invalid');
            const errorContainer = inputField.next('.invalid-feedback');
            errorContainer.html(message);
            errorContainer.show();
        }

        // Function to check image validation
        function checkImageValidation(file, id) {
            return new Promise((resolve, reject) => {
                let spanId = id + '_error';
                // strip # from id
                spanId = spanId.replace('#', '');
                $('#' + spanId).remove();

                if (!file.type.match('image.*')) {
                    $(id).after('<span id="' + spanId + '" class="error" style="color: red;">Please upload a valid image file.</span>');
                    $(id).focus();
                    resolve(false);
                } else {
                    let realSize = file.size / 1024;
                    if (realSize < 100 || realSize > 2048) {
                        $(id).after('<span id="' + spanId + '" class="error" style="color: red;">File size must be between 100KB to 2MB</span>');
                        $(id).focus();
                        resolve(false);
                    } else {
                        let img = new Image();
                        let url = URL.createObjectURL(file);
                        img.src = url;
                        img.onload = function() {
                            let width = img.width;
                            let height = img.height;
                            if (width < 640 || height < 480) {
                                $(id).after('<span id="' + spanId + '" class="error" style="color: red;">Image dimensions must be at least 640x480 pixels</span>');
                                $(id).focus();
                                resolve(false);
                            } else {
                                resolve(true);
                            }
                        };
                        img.onerror = function() {
                            reject(new Error('Failed to load image')); // Reject promise if image loading fails
                        };
                    }
                }
            });
        }

    </script>

@endsection
