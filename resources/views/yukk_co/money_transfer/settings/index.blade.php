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
            <h4>Money Transfer Settings</span></h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">

        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <span class="breadcrumb-item active">@lang("cms.Money Transfer Setting")</span>
            </div>

        </div>

    </div>
</div>
<!-- /page header -->

@hasaccess('MONEY_TRANSFER.SETTINGS_UPDATE')

<div class="modal" id="warningModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formWarningModal" action="{{ route('money_transfer.settings.update') }}" method="post">
        @method('PUT')    
        @csrf
        <input type="hidden" name="settings[0][name]" value="grouping_worker_is_maintenance">
        <input type="hidden" name="settings[0][type]" value="{{ gettype($settings['grouping_worker_is_maintenance']) }}">
        <input type="hidden" name="settings[0][value]" value="{{ $settings['grouping_worker_is_maintenance'] ? 0 : 1 }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning!</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure to set maintenance mode {{ $settings['grouping_worker_is_maintenance'] ? 'OFF' : 'ON' }}?</p>
            </div>
            <div class="modal-footer">
                <button id="actionBtn" type="submit" class="btn btn-danger">{{ $settings['grouping_worker_is_maintenance'] ? 'OFF' : 'ON' }}</button>
                <button type="button" class="btn btn-secondary btn-close-modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal" id="settingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 40%; min-width:664px;">
        <div class="modal-content">
            <form action="{{ route('money_transfer.settings.update') }}" class="form" method="post">
                @method('PUT')
                @csrf
                <div class="modal-header mx-auto">
                    <h4>@lang("cms.Setting Edit")</h4>
                </div>
                @php 
                    $index = 0;
                @endphp
                <div class="modal-body">
                    <!-- Tabs content -->
                    @foreach($settings as $name => $item)
                        @if (! in_array($name, ['grouping_worker_is_maintenance']))
                            <div class="row d-sm-flex mb-2">
                                <div class="col-5 my-auto">
                                    <span>{{ strtoupper(str_replace('_', ' ', $name)) }}</span>
                                </div>
                                @php
                                    $type = gettype($item);
                                @endphp
                                <div class="col-7 custom-control custom-switch ml-auto">
                                    <input type="hidden" name="settings[{{ $index }}][name]" value="{{ $name }}">
                                    <input type="hidden" name="settings[{{ $index }}][type]" value="{{ $type }}">
                                    @if($type == 'boolean')
                                        <input type="hidden" name="settings[{{ $index }}][value]" value="0">
                                        <input id="val_{{ $name }}" type="checkbox" class="custom-control-input" name="settings[{{ $index }}][value]" 
                                        @if($item)
                                            checked 
                                        @endif
                                        value="1"
                                        >
                                        <label class="custom-control-label" for="val_{{ $name }}"></label>
                                    @elseif($type == 'integer')
                                        <input id="val_{{ $name }}" type="number" class="form-control" name="settings[{{ $index }}][value]" value="{{ $item }}">
                                    @elseif($type == 'array')
                                        <input id="val_{{ $name }}" type="text" id="global-input-{{ $name }}" name="settings[{{ $index }}][value]" class="form-control" value="{{ implode(',', $item) }}"/>
                                        <span class="text-danger" style="font-size:11px;">Must be separated by a comma (,)</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @php 
                            $index++;
                        @endphp
                    @endforeach
                    <!-- Tabs content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-global-btn btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                    <button type="submit" id="global-btn-modal-edit-submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Update</button>
                </div>
            </form>
        </div>
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
                @if($settings['grouping_worker_is_maintenance'])
                    checked 
                @endif
                @if(! \App\Helpers\AccessControlHelper::checkCurrentAccessControl('MONEY_TRANSFER.SETTINGS_UPDATE'))
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
                <h4>@lang("cms.Setting List")</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.SETTINGS_UPDATE')
                    <button type="button" class="btn btn-primary w-100 w-sm-auto" data-toggle="modal" data-target="#settingModal"><i class="icon-pencil"></i> Edit</button>
                @endhasaccess
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach($settings as $name => $item)
                            @if (! in_array($name, ['grouping_worker_is_maintenance']))
                                <tr>
                                    <td>{{ strtoupper(str_replace('_', ' ', $name)) }}</td>
                                    @if(gettype($item) == 'boolean')
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="switch_{{ $name }}" 
                                                @if($item)
                                                    checked 
                                                @endif
                                                disabled>
                                                <label class="custom-control-label" for="switch_{{ $name }}"></label>
                                            </div>
                                        </td>
                                    @elseif(gettype($item) == 'array')
                                        <td>{{ implode(",", $item) }}</td>
                                    @else
                                        <td>{{ $item }}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    var settings = @json($settings);

    $(document).ready(function() {
        $('.form').submit(function() {
            $(this).find(':button[type=submit]').prop('disabled', true);
            $(this).find(':button[type=submit]').html('Loading..');
        })

        $('#formWarningModal').submit(function() {
            $(this).find(':button[type=submit]').prop('disabled', true);
            $(this).find(':button[type=submit]').html('Loading..');
            $(this).find(':button[type=button]').prop('disabled', true);
        })

        $('#maintenance_mode').change(function(e) {
            $(this).prop('checked', ! $(this).prop('checked'));
            $('#warningModal').show();
        })

        $('.btn-close-modal').click(function() {
            $('#warningModal').hide();
        })
        
        $('#settingModal').on('hidden.bs.modal', function () {
            for (const [name, value] of Object.entries(settings)) {
                if(typeof value == 'boolean') {
                    $('#val_' + name).prop('checked', value)
                } else {
                    $('#val_' + name).val(value);
                }
            }
        })
    })
</script>
@endsection
