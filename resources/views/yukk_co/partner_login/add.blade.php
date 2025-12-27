@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Account Login Create')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.qris_setting.list') }}" class="breadcrumb-item">
                        @lang('cms.Manage QRIS Settings')</a>
                    <span class="breadcrumb-item active">@lang('cms.Account Login Create')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-2 ml-1"><u>@lang("cms.Scopes")</u></h3>
            </div>

            <form method="GET" action="{{ route('yukk_co.partner_login.add_from_qris', ['merchant_id' => $merchant_id, 'branch_id' => $branch_id]) }}">
                <div class="row ml-1">
                    <div class="row col-3 mt-1">
                        <div class="form-group mr-2 w-100">
                            <label>@lang('cms.Merchant')</label>
                            <input hidden name="merchant_name" value="{{ $merchant->name }}">
                            <select id="merchant_id" name="merchant_id" class="form-group select2" disabled>
                                <option value="{{ @$merchant->id }}">{{ @$merchant->name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row col-3 mt-1">
                        <div class="form-group w-100">
                            <label>@lang('cms.Merchant Branch')</label>
                            <select id="merchant_branch_id" name="merchant_branch_id[]" class="form-group select2" multiple required>
                            </select>
                        </div>
                    </div>
                    <div class="row col-3 mt-1 mr-1 ml-1">
                        <div class="form-group w-100">
                            <label>@lang('cms.QRIS Type')</label>
                            <select id="qris_type" name="qris_type[]" class="form-group select2" multiple>
                                <option value="STICKER" selected>@lang('cms.STICKER')</option>
                                <option value="QRIS_DYNAMIC" selected>@lang('cms.DYNAMIC')</option>
                            </select>
                        </div>
                    </div>
                    <div class="row col-2 mt-1">
                        <div class="form-group w-100">
                            <label></label>
                            <button class="btn btn-primary form-control mt-2" id="btn-search" type="submit"><i class="icon-search4 mr-2"></i>@lang('cms.Search')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <form method="POST" action="{{ route('yukk_co.partner_login.store') }}">
            @csrf
            <div class="card-body">
                <div hidden class="form-group row">
                    <input id="id_merchant" name="id_merchant" value="{{ @$merchant_id }}">
                    <input id="id_branch" name="id_branch[]" value="{{ $branch_id }}">
                </div>
                <div class="col-3 mb-3">
                    <span id="countChecked" style="font-size: 20px">
                    </span>
                </div>
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th style="width: 25%" class="text-center">@lang('cms.Merchant Branch')</th>
                            <th class="text-center">@lang('cms.EDC IMEI')</th>
                            <th class="text-center">@lang('cms.QRIS Type')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($branch_list)
                        @foreach($branch_list as $scope)
                            @if($scope->edcs !== [])
                                @foreach($scope->edcs as $edc)
                                    <tr>
                                        <td class="text-center">{{ @$scope->name }}</td>
                                        <td class="text-center">{{ @$edc->imei }}</td>
                                        <td class="text-center">{{ @$edc->type }}</td>
                                        <td class="text-center">
                                            <input onchange="checkboxes()" type="checkbox" id="edcs-{{ $edc->id }}" name="edcs[{{ $edc->id }}]" @if(in_array($edc->id, $scope_id)) checked @endif>
                                            <input hidden id="merchant_branch_ids" name="merchant_branch_ids[]">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No EDC Available!</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No Scopes Available!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-2"><u>@lang("cms.Account Login")</u></h3>
                    <div class="col-sm-4">
                    </div>

                    <h3 class="col-lg-4"><u>@lang("cms.Profile Information")</u></h3>
                    <div class="col-sm-2">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Username")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Input Username" required>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Input Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Password")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="password" name="password" placeholder="Input Password" required>
                    </div>

                    <label for="phone" class="col-form-label col-sm-2">@lang("cms.Phone")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Input Phone No">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-sm-2"></label>
                    <div class="col-sm-4">
                    </div>

                    <label for="email" class="col-form-label col-sm-2">@lang("cms.Email")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Input Email">
                    </div>
                </div>

                <div class="form-group row justify-content-center mt-5">
                    <a href="{{ route('yukk_co.qris_setting.list') }}" class="btn btn-default">Back</a> &nbsp;
                    <button class="btn btn-primary btn-block col-1 btn-submit" id="btn-submit-scope" type="submit">@lang("cms.Save")</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function checkboxes() {
            let count = 0;
            let elemCount = 0;
            const inputElems = document.querySelectorAll('input[type="checkbox"]:not(#checkAll)');

            inputElems.forEach(input => {
                if (input.id) {
                    elemCount++;
                    if (input.checked) {
                        count++;
                    }
                }
            });

            document.getElementById('countChecked').innerHTML = 'Selected: '+count+' / '+elemCount;

            if (count == 0){
                $('#btn-submit-scope').attr('disabled', 'disabled');
            }else{
                $('#btn-submit-scope').removeAttr('disabled');
            }
        }
        checkboxes();

        $('#btn-search').click(function (e) {
            let merchant_id = $('#merchant_id').val();
            let old_merchant_id = $('#old_merchant_id').val();

            if (merchant_id !== old_merchant_id){
                if (window.confirm("Changing Merchant will remove the existing selected EDC. Proceed?")) {
                } else {
                    e.preventDefault();
                }
            }
        });
    </script>
@endsection

@section('post_scripts')
    <script>
        $(document).ready(function() {
            $('#qris_type').select2({
                placeholder: 'QRIS Type',
                multiple: true
            });

            $('#merchant_branch_id').select2({
                closeOnSelect: false,
            });
            $('#merchant_id').change(function (){
                $.ajax({
                    url: "{{ route('json.merchant.branches.select') }}",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        'merchant_id': $('#merchant_id').val()
                    },
                }).then(function (data) {
                    merchant_branches = data

                    if (data.length > 0){
                        $('#merchant_branch_id').empty();
                        $('#merchant_branch_id').append('<option value="all">Select All</option>');
                        $('#merchant_branch_id').append('<option value="unselect">Deselect All</option>');
                        $.each(data, function (key, value) {
                            $('#merchant_branch_id').append('<option value="' + value.id + '" selected>' + value.text + '</option>');
                            $('#merchant_branch_id').append('<input type="hidden" id="'+ value.id +'" name="' + value.id + '" value="' + value.text + '"></div>')
                        });
                    }else{
                        $('#merchant_branch_id').empty();
                    }
                });
            }).change();

            $('#merchant_branch_id').on('change', function (e) {
                let value = $('#merchant_branch_id').val();

                let allIndex = $('#merchant_branch_id').val().indexOf("all");
                let unselectIndex =  $('#merchant_branch_id').val().indexOf("unselect");

                let branch = merchant_branches;

                if (allIndex >= 0) {
                    value.splice(0, 1);

                    $('#merchant_branch_id').val('').trigger('change');
                    $('#merchant_branch_id').val($.map(branch, function (item) {
                        return item.id
                    })).trigger('change');

                    $.map(branch, function (item) {
                        $('#merchant_branch_id').append('<input type="hidden" id="'+ item.id +'" name="' + item.id + '" value="' + item.text + '"></div>')
                    });
                }

                if (unselectIndex >= 0){
                    $('#merchant_branch_id').val('').trigger('change');

                    $.map(branch, function (item) {
                        let unselected_option = document.getElementById(item.id)
                        unselected_option.remove();
                    });
                }
            });
        });
    </script>
@endsection
