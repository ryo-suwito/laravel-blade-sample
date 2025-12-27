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
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
            <h4>Provider <span>{{ $provider['name'] }}</span></h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <a href="{{ route("money_transfer.providers.index") }}" class="breadcrumb-item">@lang("cms.Provider")</a>
                <span class="breadcrumb-item active">@lang("cms.Setting")</span>
            </div>

            {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
        </div>

        {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
    </div>
</div>
<!-- /page header -->

@hasaccess('MONEY_TRANSFER.PROVIDER_SETTINGS_CREATE')
    <div class="modal" id="addChannelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('money_transfer.providers.bank.store') }}" class="form" method="post">
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Add Payment Channel</h4>
                    </div>
                    <div id="selectedPaymentChannels">

                    </div>
                    <div class="modal-body relative">
                        <!-- Tabs content -->

                        <input type="hidden" name="provider_id" value="{{ $provider['id'] }}">

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
                                    @foreach($list_non_assigned as $key => $bank)
                                        <tr>
                                            <td><input type="checkbox" class="payment-channel"
                                                data-id="{{ $bank['id'] }}" data-code="{{ $bank['code'] }}" 
                                                data-key="{{ $key }}" value="{{ $bank['id'] }}"></td>
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

@hasaccess('MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE')
    <div class="modal" id="editChannelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 1200px">
            <div class="modal-content">
                <form action="{{ route('money_transfer.providers.bank.update', ['id' => $provider['id'] ] ) }}" class="form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Edit Payment Channel</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs content -->
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th>@lang("cms.Delete")</th>
                                        <th>@lang("cms.Bank Name")</th>
                                        <th>@lang("cms.Bank Code")</th>
                                        <th>@lang("cms.MDR Internal")</th>
                                        <th>@lang("cms.Status")</th>
                                    </tr>
                                </thead>
                                    @foreach($list_assigned as $key => $bank)
                                        <tr>
                                            <td><input type="checkbox" name="actions[{{ $bank['id'] }}][delete]" value="{{ $bank['id'] }}"></td>
                                            <td>{{ $bank['name'] }}</td>
                                            <td>
                                                <input type="text"
                                                    name="actions[{{ $bank['id'] }}][code]"
                                                    class="form-control"
                                                    value="{{ $bank['providers'][0]['pivot']['bank_code'] }}" required>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    onkeyup="setValue(this, formatRupiah(this.value, 'Rp '), 'action');"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" 
                                                    id="action-{{ $key }}" data-key="{{ $key }}"
                                                    class="form-control" 
                                                    value="Rp {{ number_format((int) $bank['providers'][0]['pivot']['fee'], 0, ',', '.') }}" required>
                                                <input type="hidden"
                                                    id="actions-{{ $key }}"
                                                    name="actions[{{ $bank['id'] }}][fee]"
                                                    value="{{ (int) $bank['providers'][0]['pivot']['fee'] }}">
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="actions[{{ $bank['id'] }}][active]" id="list_assigned-switch-{{ $bank['id'] }}" class="custom-control-input" 
                                                    @if($bank['providers'][0]['pivot']['active'])
                                                        checked 
                                                    @endif
                                                    >
                                                    <label class="custom-control-label" for="list_assigned-switch-{{ $bank['id'] }}"></label>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="globalSettingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('money_transfer.providers.update', ['id' => $provider['id']]) }}" class="form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header mx-auto">
                        <h4>Global Setting Edit</h4>
                    </div>
                    @php 
                        $index = 0;
                    @endphp
                    <div class="modal-body">
                        <!-- Tabs content -->
                        @foreach($config as $name => $type)
                            <div class="row d-sm-flex mb-2">
                                <div class="col my-auto">
                                    <span>{{ str_replace('_', ' ', $name) }}</span>
                                </div>
                                <div class="col custom-control custom-switch my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                                    <input type="hidden" name="settings[{{ $index }}][name]" value="{{ $name }}">
                                    <input type="hidden" name="settings[{{ $index }}][type]" value="{{ $type }}">
                                    @if($type == 'boolean')
                                        <input type="hidden" name="settings[{{ $index }}][value]" value="0">
                                        <input type="checkbox" class="custom-control-input" name="settings[{{ $index }}][value]" id="modal-switch-{{ $index }}" 
                                        @if($provider['settingWithMaps'][$name]['value'] ?? 0)
                                            checked 
                                        @endif
                                        value="1">
                                        <label class="custom-control-label" for="modal-switch-{{ $index }}"></label>
                                    @elseif($type == 'integer' || $type == 'float')
                                        @if(strpos($provider['settingWithMaps'][$name]['formatted_value'] ?? 0, 'Rp') === 0)
                                            <input type="text" id="setting-{{ $index }}" data-key="{{ $index }}"
                                                onkeyup="setValue(this, formatRupiah(this.value, 'Rp '), 'setting');"
                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                class="form-control" 
                                                value="{{ $provider['settingWithMaps'][$name]['formatted_value'] ?? 0 }}">
                                            <input type="hidden" 
                                                id="settings-{{ $index }}" 
                                                name="settings[{{ $index }}][value]" class="form-control" value="{{ $provider['settingWithMaps'][$name]['value'] ?? 0 }}">
                                        @else
                                            <input type="number" 
                                                id="settings-{{ $index }}"
                                                min="0"
                                                name="settings[{{ $index }}][value]" class="form-control" value="{{ $provider['settingWithMaps'][$name]['value'] ?? 0 }}">
                                        @endif
                                    @elseif($type == 'array')
                                        <input type="text" name="settings[{{ $index }}][value]" class="form-control" value="{{ $provider['settingWithMaps'][$name]['formatted_value'] ?? '' }}">
                                        <span class="text-danger" style="font-size:11px;">Must be separated by a comma (,)</span>
                                    @else
                                        @if ($name == 'INQUIRY_PROVIDER')
                                            <select name="settings[{{ $index }}][value]" id="settings[{{ $index }}][value]" class="form-control">
                                                @foreach ($inquiryProviders as $inquiry)
                                                    <option value="{{ $inquiry['code'] }}"
                                                    @if ($provider['settingWithMaps'][$name]['value'] == $inquiry['code'])
                                                        selected
                                                    @endif >{{ $inquiry['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="settings[{{ $index }}][value]" class="form-control" value="{{ $provider['settingWithMaps'][$name]['value'] ?? '' }}">
                                        @endif
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                        <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="maintenanceModeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formWarningModal" action="{{ route('money_transfer.providers.update', ['id' => $provider['id']]) }}" method="post">
                @method('PUT')    
                @csrf
                <input type="hidden" name="settings[0][name]" value="is_maintenance">
                <input type="hidden" name="settings[0][type]" value="boolean">
                <input type="hidden" name="settings[0][value]" value="{{ $provider['settingWithMaps']['IS_MAINTENANCE']['value'] ? 0 : 1 }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Warning!</h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to set maintenance mode {{ $provider['settingWithMaps']['IS_MAINTENANCE']['value'] ? 'OFF' : 'ON' }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="actionBtn" type="submit" class="btn btn-danger">{{ $provider['settingWithMaps']['IS_MAINTENANCE']['value'] ? 'OFF' : 'ON' }}</button>
                        <button type="button" class="btn btn-secondary btn-close-modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
@endhasaccess

@endsection

@section('content')
    <div class="card">
        <div class="card-header d-sm-flex">
            <label style="font-size: large; margin-bottom: 0px !important;">Maintenance Mode</label>
            <div class="custom-control custom-switch" style="margin: auto 10px;">
                <input type="checkbox" class="custom-control-input" id="maintenance_mode" 
                @if($provider['settingWithMaps']['IS_MAINTENANCE']['value'])
                    checked 
                @endif
                @if(! \App\Helpers\AccessControlHelper::checkCurrentAccessControl('MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE'))
                    disabled
                @endif
                >
                <label class="custom-control-label" for="maintenance_mode"></label>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-sm-flex">
            <div>
                <h4>@lang("cms.Global Setting")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE')
                    <button type="button" class="btn btn-primary w-100 w-sm-auto" data-toggle="modal" data-target="#globalSettingModal"><i class="icon-pencil"></i> Edit</button>
                @endhasaccess
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach($config as $name => $type)
                            @if (! in_array($name, ["IS_MAINTENANCE"]))
                                <tr>
                                    <td>{{ str_replace('_', ' ', $name) }}</td>
                                    @if($type == 'boolean')
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="switch-{{ $name }}" 
                                                @if($provider['settingWithMaps'][$name]['value'] ?? 0)
                                                    checked 
                                                @endif
                                                disabled>
                                                <label class="custom-control-label" for="switch-{{ $name }}"></label>
                                            </div>
                                        </td>
                                    @elseif($type == 'integer' || $type == 'float')
                                        <td>{{ $provider['settingWithMaps'][$name]['formatted_value'] ?? 0 }}</td>
                                    @else
                                        <td>{{ $provider['settingWithMaps'][$name]['formatted_value'] ?? ''}}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-sm-flex" style="margin-bottom: -15px;">
            <div>
                <h4>@lang("cms.Payment Channel")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.PROVIDER_SETTINGS_UPDATE')
                    @if(count($provider['banks']) > 0)
                        <button type="button" class="btn btn-primary w-100 w-sm-auto"  data-toggle="modal" data-target="#editChannelModal"><i class="icon-pencil"></i> Edit</button>
                    @endif
                @endhasaccess
                @hasaccess('MONEY_TRANSFER.PROVIDER_SETTINGS_CREATE')
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
                        <th>@lang("cms.MDR Internal")</th>
                        <th>@lang("cms.Status")</th>
                    </tr>
                    </thead>
                        @foreach($provider['banks'] as $bank)
                            <tr>
                                <td>{{ $bank['name'] }}</td>
                                <td>Rp {{ number_format($bank['pivot']['fee'], 0, ",", ".") }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                        @if($bank['pivot']['active'])
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
        {{---
            
        function copyToClipboard(elem) {
            // create hidden text element, if it doesn't already exist
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // can just use the original source element for the selection and copy
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // must use a temporary form element for the selection and copy
                target = document.getElementById(targetId);
                if (!target) {
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // select the content
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);
            
            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");
            } catch(e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }
            
            if (isInput) {
                // restore prior selection
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // clear temporary content
                target.textContent = "";
            }
            return succeed;
        }

            function copyLink() {
                
                copyToClipboard(document.getElementById('user_login_link'));

                alert("Copied link to clipboard");
            }
        ---}}

        function setValue(e, val, prefixId) {
            let el = $('#' + e.id);
            el.val(val);

            $('#'+ prefixId +'s-'+ el.data("key")).val(formatInteger(el.val()));

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

            $('.payment-channel').change(function() {
                if($(this).is(':checked')) {
                    $('#selectedPaymentChannels').append('<input type="hidden" id="pm-'+$(this).data('id')+'" name="selectedPM['+$(this).data('key')+'][id]" value="'+$(this).data('id')+'">')
                    $('#selectedPaymentChannels').append('<input type="hidden" id="pm-code-'+$(this).data('id')+'" name="selectedPM['+$(this).data('key')+'][code]" value="'+$(this).data('code')+'">')
                } else {
                    $('#pm-'+$(this).data('id')).remove();
                    $('#pm-code-'+$(this).data('id')).remove();
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

            $('#maintenance_mode').change(function(e) {
                $(this).prop('checked', ! $(this).prop('checked'));
                $('#maintenanceModeModal').show();
            })
        });
    </script>
@endsection
