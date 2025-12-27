@extends('layouts.master')

@section('header')
<!-- Page header -->
<style>
    .bootstrap-select .no-results {
        background: #2c2d33 !important;
    }

    .bootstrap-select .dropdown-menu.inner {
        max-height: 300px;
    }

    div.dropdown-menu.show {
        max-width: 240px !important;
        max-height: 364px !important;
    }

    a.dropdown-item.selected {
        color: #65bbf9 !important;
    }

    li > a > span.text {
        white-space: break-spaces;
        margin-right: 10px !important;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        right: 15px !important;
        top: 25% !important;
    }

    .pointer {
        cursor: pointer;
    }
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            <h4>{{ request()->get('tag') }} - <span>{{ $settings['name'] }}</span></h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">

        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <a href="{{ route("money_transfer.partners.index") }}" class="breadcrumb-item">@lang("cms.User")</a>
                <span class="breadcrumb-item active">@lang("cms.Setting")</span>
            </div>

        </div>

    </div>
</div>
<!-- /page header -->

@hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')
    {{--
        <div class="modal" id="addUserLoginModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('money_transfer.partners.user.store') }}" class="form" method="post">
                        @csrf
                        <div class="modal-header mx-auto">
                            <h4>User Login Add</h4>
                        </div>
                        <div class="modal-body">
                            <!-- Tabs content -->

                            <input type="hidden" name="partner_id" value="{{ $settings['id'] }}">

                            <div class="row d-sm-flex mb-2">
                                <div class="my-auto">
                                    <span>@lang("cms.Name")</span>
                                </div>
                                <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                    <input type="text" name="name" id="user_name" class="form-control" value="">
                                </div>
                            </div>

                            <div class="row d-sm-flex mb-2">
                                <div class="my-auto">
                                    <span>@lang("cms.Email")</span>
                                </div>
                                <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                    <input type="text" name="email" id="user_email" class="form-control" value="">
                                </div>
                            </div>
                            
                            <!-- Tabs content -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                            <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>    
    --}}
    <div class="modal" id="addChannelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('money_transfer.partners.bank.store') }}" class="form" method="post">
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Add Payment Channel</h4>
                    </div>
                    <div id="selectedPaymentChannels">

                    </div>
                    <div class="modal-body relative">
                        <!-- Tabs content -->

                        <input type="hidden" name="id" value="{{ $settings['id'] }}">
                        <input type="hidden" name="tag" value="{{ request()->get('tag') }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-responsive dataTable" style="max-height: 350px">
                                <thead>
                                    <tr>
                                        <th class="w-auto">@lang("cms.Select")</th>
                                        <th class="w-50">@lang("cms.Bank Code")</th>
                                        <th class="w-50">@lang("cms.Bank Name")</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list_non_assigned as $bank)
                                        <tr>
                                            <td><input type="checkbox" class="payment-channel"
                                                data-id="{{ $bank['id'] }}" value="{{ $bank['id'] }}"></td>
                                            <td>{{ $bank['code'] }}</td>
                                            <td>{{ $bank['name'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Tabs content -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endhasaccess

@hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')
{{--- 
    <div class="modal" id="editUserLoginModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-user-login-edit" action="" class="form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>User Login Edit</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs content -->

                        <div class="row d-sm-flex mb-2">
                            <div class="my-auto">
                                <span>@lang("cms.Name")</span>
                            </div>
                            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                <input type="text" id="user_login_name" class="form-control" name="user_login_name" value="">
                            </div>
                        </div>

                        <div class="row d-sm-flex mb-2">
                            <div class="my-auto">
                                <span>@lang("cms.Email")</span>
                            </div>
                            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                <input type="text" id="user_login_email" class="form-control" name="user_login_email" value="">
                            </div>
                        </div>

                        <div class="row d-sm-flex mb-2">
                            <div class="my-auto">
                                <span>@lang("cms.Link")</span>
                            </div>
                            <div class="row my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                <input type="text" class="form-control mr-2" style="width: auto;" id="user_login_link" name="user_login_link" value="" readonly>
                                <button type="button" class="btn btn-secondary mr-2" onclick="copyLink()">Copy</button>
                            </div>
                        </div>

                        <div class="row d-sm-flex mb-2">
                            <div class="my-auto">
                                <span>Status</span>
                            </div>
                            <div class="custom-control custom-switch my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                <input type="checkbox" class="custom-control-input" id="user_login_status" name="user_login_status">
                                <label class="custom-control-label" for="user_login_status"></label>
                            </div>
                        </div>

                        
                        <!-- Tabs content -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
---}}
    <div class="modal" id="editChannelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 95%">
            <div class="modal-content">
                <form action="{{ route('money_transfer.partners.bank.update', ['id' => $settings['id'] ] ) . '?tag=' . request()->get('tag') }}" class="form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Edit Payment Channel</h4>
                    </div>
                    <div class="modal-body">
                        <div id="alert" class="alert alert-danger" role="alert"> 
                            <ul id="alert-list">
                                @foreach($list_assigned as $key => $bank)
                                    @if($bank['name'] != "YUKK")
                                        @if($bank['dpp'] <= $bank['internal_fee'])
                                            <li id="alert-{{ $key }}"><span class="text-danger" style="font-size:12px;">DPP External {{ $bank['name'] }} must be higher than MDR Internal!</span></li>
                                        @endif
                                        
                                        @if(is_float($bank['external_fee']))
                                            <li id="alert-{{ $key }}"><span class="text-danger" style="font-size:12px;">MDR External {{ $bank['name'] }} must be Integers!</span></li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <!-- Tabs content -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped dataTable" style="max-height: 350px">
                                <thead>
                                    <tr>
                                        <th>@lang("cms.Delete")</th>
                                        <th>@lang("cms.Bank Name")</th>
                                        <th>@lang("cms.MDR Internal")</th>
                                        <th>@lang("cms.DPP External")</th>
                                        <th>@lang("cms.PPN") ({{ $list_assigned[0]['ppn'] ?? 11 }})%</th>
                                        <th>@lang("cms.MDR External")</th>
                                        <th>@lang("cms.Status")</th>
                                    </tr>
                                </thead>
                                    @foreach($list_assigned as $key => $bank)
                                        <tr>
                                            <td><input type="checkbox" name="actions[{{ $bank['id'] }}][delete]" value="{{ $bank['id'] }}" class="deleteBtn"></td>
                                            <td>{{ $bank['name'] }}</td>
                                            <td>
                                                <input type="text"
                                                    id="mdr-internal-{{ $key }}"
                                                    data-name="{{ $bank['name'] }}"
                                                    class="form-control" 
                                                    value="Rp {{ number_format($bank['internal_fee'], 2, ',', '.') }}"
                                                    readonly>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    onkeyup="setMDRExternal('{{ $key }}', formatInteger(this.value))"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" 
                                                    id="dpp-external-{{ $key }}" data-key="{{ $key }}"
                                                    class="form-control" 
                                                    value="Rp {{ number_format($bank['dpp'], 0, ',', '.') }}">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    id="ppn-{{ $key }}"
                                                    data-ppn="{{ $bank['ppn'] }}"
                                                    class="form-control" 
                                                    value="Rp {{ number_format($bank['ppn_fee'], 2, ',', '.') }}"
                                                    readonly>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    id="mdr-external-{{ $key }}" data-key="{{ $key }}"
                                                    class="form-control" 
                                                    value="Rp {{ number_format($bank['external_fee'], 2, ',', '.') }}"
                                                    readonly>
                                                <input type="hidden"
                                                    id="mdr-externals-{{ $key }}"
                                                    name="actions[{{ $bank['id'] }}][fee]"
                                                    value="{{ $bank['external_fee'] }}">
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="actions[{{ $bank['id'] }}][active]" id="bank-assigned-status-{{ $key }}" class="pm-status custom-control-input" 
                                                    @if($bank['active'])
                                                        checked 
                                                    @endif
                                                    >
                                                    <label class="custom-control-label" for="bank-assigned-status-{{ $key }}"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <!-- Tabs content -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-pc-btn btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button id="btn-modal-edit-submit" type="submit" class="btn btn-success" disabled><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="globalSettingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 40%; min-width:664px;">
            <div class="modal-content">
                <form action="{{ route('money_transfer.partners.update', ['id' => $settings['id']]) . '?tag=' . request()->get('tag') }}" class="form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Global Setting Edit</h4>
                    </div>
                    @php 
                        $index = 0;
                    @endphp
                    <div class="modal-body">
                        <div id="global-alert" class="alert alert-danger" role="alert"> 
                            <ul id="global-alert-list">
                                @foreach($config['types'] as $name => $type)
                                    @if($type == 'integer' || $type == 'float')
                                        @if($config['views'][$name] == 'custom.external_fee') 
                                            @if($settings['dpp'] <= $settings['internal_fee'])
                                                <li id="global-alert-{{ $name }}"><span class="text-danger" style="font-size:12px;">DPP External {{ $name }} must be higher than MDR Internal!</span></li>
                                            @endif
                                            
                                            @if(is_float($settings['settingWithMaps'][$name]['value']))
                                                <li id="global-alert-{{ $name }}"><span class="text-danger" style="font-size:12px;">MDR External {{ $name }} must be Integers!</span></li>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <!-- Tabs content -->
                        @foreach($config['types'] as $name => $type)
                            @php
                                $readonly = array_key_exists($name, $config['readonly']);
                            @endphp
                            <div class="row d-sm-flex mb-2">
                                <div class="col-5 my-auto">
                                    <span>{{ str_replace('_', ' ', $name) }}</span>
                                </div>
                                <div class="col-7 custom-control custom-switch ml-auto">
                                    <input type="hidden" name="settings[{{ $index }}][name]" value="{{ $name }}">
                                    <input type="hidden" name="settings[{{ $index }}][type]" value="{{ $type }}">
                                    @if($type == 'boolean')
                                        <input type="hidden" name="settings[{{ $index }}][value]" value="0">
                                        <input type="checkbox" class="custom-control-input" name="settings[{{ $index }}][value]" id="modal-switch-{{ $name }}" 
                                        @if($settings['settingWithMaps'][$name]['formatted_value'] ?? 0)
                                            checked 
                                        @endif
                                        value="1">
                                        <label class="custom-control-label" for="modal-switch-{{ $name }}"></label>
                                    @elseif($type == 'integer' || $type == 'float')
                                        <input type="hidden" name="settings[{{ $index }}][view]" value="{{ $config['views'][$name] ?? 'default' }}">
                                        @if($config['views'][$name] == 'custom.external_fee') 
                                            <input type="hidden" name="settings[{{ $index }}][ppn]" value="{{ $settings['ppn'] }}">
                                            <div>
                                                <div class="row">
                                                    <div class="col my-auto">
                                                        <label for="global-mdr-internal-{{ $name }}">@lang("cms.MDR Internal")</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text"
                                                            id="global-mdr-internal-{{ $name }}"
                                                            data-name="{{ $name }}"
                                                            class="form-control mb-1" 
                                                            value="Rp {{ number_format($settings['internal_fee'], 0, ',', '.') }}"
                                                            readonly>

                                                        <input type="hidden"
                                                            name="settings[{{ $index }}][internal_fee]"
                                                            value="{{ $settings['internal_fee'] }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col my-auto">
                                                        <label for="global-dpp-external-{{ $name }}">@lang("cms.DPP External")</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text"
                                                            onkeyup="setMDRExternal('{{ $name }}', formatInteger(this.value), 'global-')"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" 
                                                            id="global-dpp-external-{{ $name }}" data-key="{{ $name }}"
                                                            class="form-control mb-1" 
                                                            value="Rp {{ number_format($settings['dpp'], 0, ',', '.') }}"
                                                            @if($readonly)
                                                                readonly
                                                            @endif>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col my-auto">
                                                        <label for="global-ppn-{{ $name }}">@lang("cms.PPN")({{ $settings['ppn'] }}%)</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text"
                                                            id="global-ppn-{{ $name }}" data-key="{{ $name }}"
                                                            data-ppn="{{ $settings['ppn'] }}"
                                                            class="form-control mb-1" 
                                                            value="Rp {{ number_format($settings['ppn_fee'], 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col my-auto">
                                                        <label for="global-mdr-external-{{ $name }}">@lang("cms.MDR External")</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text"
                                                            id="global-mdr-external-{{ $name }}" data-key="{{ $name }}"
                                                            class="form-control mb-1" 
                                                            value="{{ $settings['settingWithMaps'][$name]['formatted_value'] }}"
                                                            readonly>

                                                        <input type="hidden"
                                                            id="global-mdr-externals-{{ $name }}"
                                                            name="settings[{{ $index }}][value]"
                                                            value="{{ $settings['settingWithMaps'][$name]['value'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @if(strpos($settings['settingWithMaps'][$name]['formatted_value'] ?? 0, 'Rp') === 0)
                                                <input type="text" id="setting-{{ $name }}" data-key="{{ $name }}"
                                                    onkeyup="setValue(this, formatRupiah(this.value, 'Rp '), 'setting');"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                    class="form-control" 
                                                    value="{{ $settings['settingWithMaps'][$name]['formatted_value'] ?? 0 }}" 
                                                    @if($readonly)
                                                        readonly
                                                    @endif>
                                                <input type="hidden" 
                                                    id="settings-{{ $name }}" 
                                                    name="settings[{{ $index }}][value]" class="form-control" value="{{ $settings['settingWithMaps'][$name]['value'] ?? 0 }}">
                                            @else
                                                <input type="number" 
                                                    id="settings-{{ $name }}" 
                                                    min="0"
                                                    name="settings[{{ $index }}][value]" class="form-control" value="{{ $settings['settingWithMaps'][$name]['value'] ?? 0 }}"
                                                    @if($readonly)
                                                        readonly
                                                    @endif>
                                            @endif
                                        @endif
                                    @elseif($type == 'array')
                                        <input type="text" id="global-input-{{ $name }}" name="settings[{{ $index }}][value]" class="form-control" value="{{ $settings['settingWithMaps'][$name]['formatted_value'] ?? '' }}"
                                            @if($readonly)
                                                readonly
                                            @endif>
                                        <span class="text-danger" style="font-size:11px;">Must be separated by a comma (,)</span>
                                    @else
                                        <input type="text" id="global-input-{{ $name }}" name="settings[{{ $index }}][value]" class="form-control" value="{{ $settings['settingWithMaps'][$name]['formatted_value'] ?? '' }}"
                                            @if($readonly)
                                                readonly
                                            @endif>
                                    @endif
                                </div>
                            </div>

                            @php 
                                $index++;
                            @endphp
                        @endforeach
                        <!-- Tabs content -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-global-btn btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button type="submit" id="global-btn-modal-edit-submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (! is_null($cred))
        <div class="modal" id="editPublicKeyModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="
                        @if (is_null($cred['rsa']))
                            {{ route('money_transfer.entities.public-keys.store') }}
                        @else
                            {{ route('money_transfer.entities.public-keys.update', ['id' => $cred['rsa']['id'] ]) }}
                        @endif
                        " class="form" method="post">
                        @if (! is_null($cred['rsa']))
                            @method('PUT')
                        @endif

                        @csrf
                        <div class="modal-header mx-auto">
                            <h4>
                                @if (is_null($cred['rsa']))
                                    Set
                                @else
                                    Edit
                                @endif
                                Public Key
                            </h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="entity_type" value="{{ request()->get('tag') }}">
                            <input type="hidden" name="entity_id" value="{{ request()->route('id') }}">
                            <textarea class="form-control" name="public_key" cols="30" rows="10">{{ $cred['rsa']['public_key'] ?? '' }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel-pc-btn btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                            <button id="btn-modal-edit-public-key-submit" type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endhasaccess

@endsection

@section('content')
    <div class="card">
        <div class="card-header d-sm-flex">
            <div>
                <h4>@lang("cms.Global Setting")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')
                    <button type="button" class="btn btn-primary w-100 w-sm-auto" data-toggle="modal" data-target="#globalSettingModal"><i class="icon-pencil"></i> Edit</button>
                @endhasaccess
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach($config['types'] as $name => $type)
                            <tr>
                                <td>{{ str_replace('_', ' ', $name) }}</td>
                                @if($type == 'boolean')
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="switch-{{ $name }}" 
                                            @if($settings['settingWithMaps'][$name]['value'] ?? 0)
                                                checked 
                                            @endif
                                            disabled>
                                            <label class="custom-control-label" for="switch-{{ $name }}"></label>
                                        </div>
                                    </td>
                                @elseif($type == 'integer' || $type == 'float')
                                    <td>{{ $settings['settingWithMaps'][$name]['formatted_value'] ?? 0 }}</td>
                                @else
                                    <td>{{ $settings['settingWithMaps'][$name]['formatted_value'] ?? ''}}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-sm-flex">
            <div>
                <h4>@lang("cms.Api Credentials")</h4>
            </div>
            @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')
                @if (is_null($cred))
                    <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                        <form action="{{ route('money_transfer.entities.credentials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="entity_type" value="{{ request()->get('tag') }}">
                            <input type="hidden" name="entity_id" value="{{ request()->route('id') }}">

                            <button type="submit" class="btn btn-primary w-100 w-sm-auto">Generate</button>
                        </form>
                    </div>
                @endif
            @endhasaccess
        </div>

        @if (! is_null($cred))
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>NAME</th>
                            <th class="text-right">{{ $cred['entity_name'] }}</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ strtoupper(trans("cms.Client ID")) }}</td>
                                <td class="text-right">{{ $cred['client_id'] }} <i class="fas fa-copy pointer" data-val="{{ $cred['client_id'] }}" title="Copy to clipboard"></i></td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper(trans("cms.Client Secret")) }}</td>
                                <td class="text-right">{{ $cred['client_secret'] }} <i class="fas fa-copy pointer" data-val="{{ $cred['client_secret'] }}" title="Copy to clipboard"></i></td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper(trans("cms.Account Number")) }}</td>
                                <td class="text-right">{{ $cred['account_number'] }} <i class="fas fa-copy pointer" data-val="{{ $cred['account_number'] }}" title="Copy to clipboard"></i></td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper(trans("cms.Public Key")) }}</td>
                                <td class="text-right">
                                    @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')
                                        <label class="pointer" data-toggle="modal" data-target="#editPublicKeyModal">@if (is_null($cred['rsa']))
                                            Set
                                        @else
                                            Edit
                                        @endif</label>
                                    @endhasaccess
                                </td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{--- <div class="card">
        <div class="card-header d-sm-flex" style="margin-bottom: -15px;">
            <div>
                <h4>@lang("cms.User Login")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <button type="button" class="btn btn-primary w-100 w-sm-auto"  data-toggle="modal" data-target="#addUserLoginModal"><i class="fas fa-plus"></i> Add</button>
            </div>
        </div>

        <div class="card-body">     
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable mb-4">
                    <thead>
                    <tr>
                        <th>@lang("cms.Name")</th>
                        <th>@lang("cms.Email")</th>
                        <th>@lang("cms.Status")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>
                        @foreach($settings['users'] as $key => $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="user-switch-{{ $user['id'] }}" 
                                        @if($user['active'])
                                            checked 
                                        @endif
                                        disabled>
                                        <label class="custom-control-label" for="user-switch-{{ $user['id'] }}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" data-email="{{ $user['email'] }}"
                                                data-status="{{ $user['active'] }}" data-link="{{ $user['link'] }}"
                                                class="btn-user-edit dropdown-item" data-toggle="modal" data-target="#editUserLoginModal">
                                                    <i class="icon-search4"></i> @lang("cms.Detail")
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    <tbody>
                        
                    </tbody>
                </table>
            </div>       
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
        </div>
    </div> ---}}


    <div class="card">
        <div class="card-header d-sm-flex" style="margin-bottom: -15px;">
            <div>
                <h4>@lang("cms.Payment Channel")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_UPDATE')
                    @if(count($settings['disbursement_channels']) > 0)
                        <button type="button" class="btn btn-primary w-100 w-sm-auto"  data-toggle="modal" data-target="#editChannelModal"><i class="icon-pencil"></i> Edit</button>
                    @endif
                @endhasaccess
                @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')
                    <button type="button" class="btn btn-primary w-100 w-sm-auto"  data-toggle="modal" data-target="#addChannelModal"><i class="fas fa-plus"></i> Add</button>
                @endhasaccess
            </div>
        </div>

        <div class="card-body">     
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>@lang("cms.Name")</th>
                        <th>@lang("cms.MDR External (Fixed)")</th>
                        <th>@lang("cms.Status")</th>
                    </tr>
                    </thead>
                        @foreach($settings['disbursement_channels'] as $bank)
                            <tr>
                                <td>{{ $bank['name'] }}</td>
                                <td>Rp {{ number_format($bank['external_fee'], 0, ",", ".") }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                        @if($bank['active'])
                                            checked 
                                        @endif
                                        disabled>
                                        <label class="custom-control-label"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    <tbody>
                        
                    </tbody>
                </table>
            </div>       
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const assignedBanks = @json($list_assigned);
        const config = @json($config);
        const partner = @json($settings);
        let deleteState = [];

        function setValue(e, val, prefixId = '') {
            let el = $('#' + e.id);
            el.val(val);

            $('#'+ prefixId +'s-'+ el.data("key")).val(formatInteger(el.val()));

        }

        function isFloat(n){
            return Number(n) === n && n % 1 !== 0;
        }

        function showMessageAlert(prefixId = '', errorId, msg){
            $('#' + prefixId + 'alert-list').append('<li id="'+ errorId +'"><span class="text-danger" style="font-size:12px;">'+ msg +'</span></li>');
        }

        function hideBgErrorAlert(prefixId = ''){
            if($('ul#'+prefixId+'alert-list li').length == 0) {
                $('#' + prefixId + 'alert').hide();
            }
        }

        function showBgErrorAlert(prefixId){
            if(!$('#' + prefixId + 'alert').is(':visible')) {
                $('#' + prefixId + 'alert').show();
            }
        }

        function setDetailFee(prefixId = '', key, dppFee, ppnFee, externalFee, externalValue) {
            $('#'+prefixId+'dpp-external-'+key).val(formatRupiah(dppFee, 'Rp '));
            $('#'+prefixId+'ppn-'+key).val(formatRupiah(ppnFee, 'Rp '));
            $('#'+prefixId+'mdr-external-'+key).val(formatRupiah(externalFee, 'Rp '));
            $('#'+prefixId+'mdr-externals-'+key).val(externalValue);
        }

        function showErrorAlert(prefixId = '', errorId, message) {
            showBgErrorAlert(prefixId);

            if( ! $('#' + errorId).length ) {
                showMessageAlert(prefixId, errorId, message);
            }
        }

        function hideErrorAlert(prefixId = '', errorId) {
            $('#' + errorId).remove();

            hideBgErrorAlert(prefixId);
        }

        function setMDRExternal(key, value, prefixId = '') {
            let ppn = $('#'+prefixId+'ppn-'+key).data('ppn');
            let totalPpn;
            let errors = $('ul#'+prefixId+'alert-list li').length;

            if(value == '' || value == 'Rp' || value == 'Rp ') {
                value = 0;
            }

            totalPpn = parseInt(value)*ppn/100;

            totalPpnFormat = totalPpn.toString().replace('.',',');
            end = parseInt(value)+totalPpn;
            mdrEx = (end).toString().replace('.',',');

            setDetailFee(prefixId, key, value, totalPpnFormat, mdrEx, end);

            if(parseInt(value) <= parseInt(formatInteger($('#'+prefixId+'mdr-internal-'+key).val()).replace(',','.'))) {
                if ($('#'+prefixId+'mdr-internal-'+key).data('name') != "YUKK") {
                    showErrorAlert(prefixId, prefixId+'alert-'+key
                        , 'DPP External '+ $('#'+prefixId+'mdr-internal-'+key).data('name')
                        + ' must be higher than MDR Internal!');
                }
            } else {
                hideErrorAlert(prefixId, prefixId+'alert-'+key);
            }
            
            if(isFloat(end)) {
                showErrorAlert(prefixId, prefixId+'alert-mdr-ex-'+key
                    , 'MDR External '+ $('#'+prefixId+'mdr-internal-'+key).data('name') +' must be Integers!'); 
            } else {
                if( $('#'+prefixId+'alert-mdr-ex-'+key).length ) {
                    hideErrorAlert(prefixId, prefixId+'alert-mdr-ex-'+key);
                }
            }

            errors = $('ul#'+prefixId+'alert-list li').length;

            $('#'+prefixId+'btn-modal-edit-submit').prop('disabled', (errors == 0 && ! isNaN($('#'+prefixId+'mdr-externals-'+key).val())) ? false : true);
            
        }

        function formatInteger(rupiah) {
            var number_string = rupiah.replace('Rp ', ''),
                angka = number_string.split('.');
            
            return angka.join('');
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split    = number_string.split(','),
                sisa     = split[0].length % 3,
                rupiah     = split[0].substr(0, sisa),
                ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
            if(parseInt(number_string) == 0) {
                return 0;
            }

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        $(document).ready(function() {
            {{---
                let base_url_partner = "{{ route('money_transfer.partners.index') }}";

                $('.btn-user-edit').click(function(e) {
                    $('#user_login_name').val($(this).data('name'));
                    $('#user_login_email').val($(this).data('email'));
                    $('#user_login_link').val($(this).data('link'));
                    $('#user_login_status').attr('checked', Boolean($(this).data('status')));
                    $('#form-user-login-edit').attr(
                        'action', 
                        base_url_partner + '/' + $(this).data('id') + '/user/update' 
                    );
                });

            ---}}

            hideBgErrorAlert();
            hideBgErrorAlert('global-');

            if($('ul#global-alert-list li').length != 0) {
                $('#global-btn-modal-edit-submit').prop('disabled', true);
            }

            $('.payment-channel').change(function() {
                if($(this).is(':checked')) {
                    $('#selectedPaymentChannels').append('<input type="hidden" id="pm-'+$(this).data('id')+'" name="selectedPM[]" value="'+$(this).data('id')+'">')
                } else {
                    $('#pm-'+$(this).data('id')).remove();
                }
            })

            $(".dataTable").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": true,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $('.form').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
                $(this).find(':button[type=submit]').html('Loading..');
            })

            $('.deleteBtn').change(function() {
                let errors = $('ul#alert-list li').length;

                if($(this).is(':checked') && errors == 0) {
                    $('#btn-modal-edit-submit').prop('disabled', false);
                }
            });

            $('.pm-status').change(function() {
                let errors = $('ul#alert-list li').length;

                if(errors == 0) {
                    $('#btn-modal-edit-submit').prop('disabled', false);
                }
            });

            $('.cancel-pc-btn').click(function() {
                $('input[class="deleteBtn"]').each(function() { 
                    this.checked = false; 
                });
                $('#btn-modal-edit-submit').prop('disabled', true);

                assignedBanks.forEach(function(bank, key) {
                    setDetailFee('', key, bank.dpp.toString(), bank.ppn_fee.toString(), bank.external_fee.toString(), bank.external_fee);
                    $('#bank-assigned-status-' + key).prop('checked', bank.active);
                })
            });

            $('.cancel-global-btn').click(function() {
                let setting = partner['settingWithMaps'];
                let types = config['types'];

                $('#global-btn-modal-edit-submit').prop('disabled', false);

                Object.keys(types).forEach(function(name) {
                    if(types[name] == 'boolean') {
                        $('#modal-switch-' + name).prop('checked', setting[name]['formatted_value'])
                    } else if(types[name] == 'integer' || types[name] == 'float' ) {
                        
                        if(config['views'][name] == 'custom.external_fee') {
                            setDetailFee('global-', name, partner['dpp'].toString(), partner['ppn_fee'].toString(), setting[name]['value'].toString(), setting[name]['value']);
                        } else {
                            
                            if(setting[name]['formatted_value'].charAt(0) == 'R' && setting[name]['formatted_value'].charAt(1) == 'p') {
                                $('#setting-' + name).val(formatRupiah(setting[name]['value'].toString(), 'Rp '));
                                $('#settings-' + name).val(c);
                            } else {
                                $('#settings-' + name).val(setting[name]['formatted_value']);
                            }
                        }
                        
                    } else {
                        $('#global-input-' + name).val(setting[name]['formatted_value']);
                    }
                });
            });

            $('.fa-copy').click(function(e) {
                let textToCopy = $(this).data('val');

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy);
                } else {
                    // Use the 'out of viewport hidden text area' trick
                    const textArea = document.createElement("textarea");
                    textArea.value = textToCopy;
                        
                    // Move textarea out of the viewport so it's not visible
                    textArea.style.position = "absolute";
                    textArea.style.left = "-999999px";
                        
                    document.body.prepend(textArea);
                    textArea.select();

                    try {
                        document.execCommand('copy');
                    } catch (error) {
                        console.error(error);
                    } finally {
                        textArea.remove();
                    }
                }

                Swal.fire({
                    'text': "Success copy to clipboard!",
                    'icon': 'success',
                    'toast': true,
                    'timer': 3000,
                    'showConfirmButton': false,
                    'position': 'top-right',
                });
            });
        });
    </script>
@endsection
