<x-app-layout>
    <x-page.header :title="__('cms.Manage Approval')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="$mainMenuUrl" :text="__('cms.' . $title)" />
            <x-breadcrumb.link :link="$approvalUrl" text="Action Page" />
            <x-breadcrumb.active>
                Master Detail
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.' . $title)">
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
                                <input type="text" disabled name="name" id="name" class="form-control" placeholder="@lang('cms.Name')" required autofocus value="{{ optional($master)['name'] }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="phone">@lang('cms.Phone')</label>
                            <div class="col-lg-8">
                                <input type="text" disabled name="phone" id="phone" class="form-control" placeholder="@lang('cms.Phone')" value="{{ optional($master)['phone'] }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="email">@lang('cms.Email')</label>
                            <div class="col-lg-8">
                                <input type="email" disabled name="email" id="email" class="form-control" placeholder="@lang('cms.Email')" value="{{ optional($master)['email'] }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="address">@lang('cms.Address')</label>
                            <div class="col-lg-8">
                                <input name="address" disabled id="address" class="form-control" placeholder="@lang('cms.Address')" value="{{ optional($master)['address'] }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"
                                for="city_id">@lang('cms.City')</label>
                            <div class="col-lg-8">
                            <select id="city_id" disabled name="city_id" class="form-control select2">
                                <option value="">@lang('cms.Select City')</option>
                                @foreach ($cities as $value)
                                <option value="{{ $value->id }}"
                                    {{ $master['city_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="active">@lang('cms.Status')</label>
                            <div class="col-lg-8 align-items-center justify-content-center">
                                <input type="checkbox" style="margin:auto;" name="active" id="active" value="1" disabled {{ optional($master)['active'] ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Legal Identification -->
        <div class="row">
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
                                        <option value="1" {{ optional($master)['merchant_type'] == "INDIVIDU" ? 'selected' : '' }}>INDIVIDU</option>
                                        <option value="2" {{ optional($master)['merchant_type'] == "BADAN_HUKUM" ? 'selected' : '' }}>BADAN HUKUM</option>
                                        <!-- Add owner type options -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if($master['id_card_path'])
                        <div class="col-lg-12">
                            <div class="form-group row" id="previewKtpContainerRow">
                                <label class="col-lg-4 col-form-label">@lang('cms.File KTP')</label>
                                @if($master['id_card_path'])
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-12" style="margin-top:10px">
                                            <div id="previewContainer" style="position: relative;">
                                                <img src="{{ $master['id_card_url'] }}" alt="KTP Image" id="file_ktp_preview" style="width: 100%; height: auto;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="id_card_number">@lang('cms.KTP No')</label>
                                <div class="col-lg-8">
                                    <input type="number" disabled min="0" id="id_card_number" name="id_card_number"
                                        class="form-control" placeholder="@lang('cms.KTP No')"
                                        value="{{ optional($master)['id_card_number'] }}" required data-rule-minlength="16"
                                        data-rule-maxlength="16" data-msg-minlength="Exactly 16 digits please"
                                        data-msg-maxlength="Exactly 16 digits please">

                                    <div class="invalid-feedback alert-box"></div>
                                </div>
                            </div>
                        </div>
                        @if($master['selfie_path'])
                        <div class="col-lg-12">
                            <div class="form-group row" id="previewSelfieContainerRow">
                                <label class="col-lg-4 col-form-label">@lang('cms.File Selfie')</label>
                                @if($master['selfie_path'])
                                <div class="col-lg-8">
                                    <div class="row">   
                                        <div class="col-lg-12" style="margin-top:10px">
                                            <div id="previewContainer" style="position: relative;">
                                                <img src="{{ $master['selfie_url'] }}" alt="Selfie Image" id="file_selfie_preview" style="width: 100%; height: auto;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($master['npwp_path'])
                        <div class="col-lg-12">
                            <div class="form-group row" id="previewNpwpContainerRow">
                                <label class="col-lg-4 col-form-label">@lang('cms.File NPWP')</label>
                                @if($master['npwp_path'])
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-12" style="margin-top:10px">
                                            <div id="previewContainer" style="position: relative;">
                                                <img src="{{ $master['npwp_url'] }}" alt="NPWP Image" id="file_npwp_preview" style="width: 100%; height: auto;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="npwp_number">@lang('cms.NPWP No')</label>
                                <div class="col-lg-8">
                                    <input type="number" disabled min="0" id="npwp_number" name="npwp_number" class="form-control"
                                        placeholder="@lang('cms.NPWP No')" value="{{ optional($master)['npwp_number'] }}"
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
    </x-page.content>
</x-app-layout>