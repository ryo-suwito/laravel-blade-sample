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
                    <a href="{{ route('yukk_co.partner_login.index') }}" class="breadcrumb-item">
                        @lang('cms.Account Login List')</a>
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
                <h4 class="col-sm-2 ml-1">@lang("cms.Scopes")</h4>
            </div>

            <form method="GET" action="{{ route('yukk_co.partner_login.add') }}">
                <div class="row ml-1">
                    <div class="row col-3 mt-1">
                        <div class="form-group w-100" style="padding-right: 15px;">
                            <label>@lang('cms.Merchant')</label>
                            @if($old && isset($old['merchant']['id']))
                                <input type="hidden" id="merchant_name" name="merchant_name" value="{{ $old['merchant']['name'] }}">
                            @else
                                <input type="hidden" id="merchant_name" name="merchant_name" value="{{ isset($merchant['name']) ? $merchant['name'] : '' }}">
                            @endif
                            <select id="merchant_id" name="merchant_id" class="form-group select2">
                                @if($old && isset($old['merchant']['id']))
                                    <option value="{{ @$old['merchant']['id'] }}">{{ @$old['merchant']['name'] }}</option>
                                @else
                                    <option value="">Select Merchant</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row col-4 mt-1">
                        <div class="form-group w-100" style="padding-right: 15px;">
                            <label>@lang('cms.Merchant Branch')</label>
                            <select id="merchant_branch_id" name="merchant_branch_id[]" class="form-group select2" multiple required>
                                @foreach ($branch_list as $branch)
                                    <option value="{{ $branch->id }}">{{ @$branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row col-3 mt-1 mr-1 ml-1">
                        <div class="form-group w-100">
                            <label>@lang('cms.QRIS Type')</label>
                            <select id="qris_type" name="qris_type[]" class="form-group select2" multiple>
                                <option value="STICKER" <?php if(in_array('STICKER', $qris_type)) echo 'selected'; ?>
                                >@lang('cms.STICKER')</option>
                                <option value="QRIS_DYNAMIC"  <?php if(in_array('QRIS_DYNAMIC', $qris_type)) echo 'selected'; ?>
                                >@lang('cms.DYNAMIC')</option>
                            </select>
                        </div>
                    </div>
                    <div class="row col-2 mt-1">
                        <div class="form-group w-100">
                            <label></label>
                            <button class="btn btn-primary form-control mt-2" id="btn-search" type="submit" disabled><i class="icon-search4 mr-2"></i>@lang('cms.Search')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <form method="POST" action="{{ route('yukk_co.partner_login.store') }}">
            @csrf
            <div class="card-body">
                <div hidden class="form-group row">
                    <input id="id_merchant" name="id_merchant" value="{{ @$old? $old['merchant']['id'] : $merchant_id }}">
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
                        <th class="text-center">
                            <input type="checkbox" id="checkAll"> All
                        </th>
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
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <h4 class="col-sm-2">@lang("cms.Account Login")</h4>
                    <div class="col-sm-4">
                    </div>

                    <h4 class="col-lg-4">@lang("cms.Profile Information")</h4>
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
                    <a onclick="history.go(-1)" class="btn btn-default">Back</a> &nbsp;
                    <button class="btn btn-primary btn-block col-1 btn-submit" id="btn-submit-scope" type="submit">@lang("cms.Save")</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
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
        var merchant_branches = [];
        let old_merchant_id = @json($old['merchant_id'] ?? null);
        let old_merchant_branch_id = @json($old['merchant_branch_id'] ?? []);
        var allOptions = []; 

        function checkboxes(reduce=false) {
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
            if (elemCount != count && !reduce && elemCount > 0){
                document.getElementById('checkAll').checked = false;
            } else if (elemCount == count && !reduce && elemCount > 0){
                document.getElementById('checkAll').checked = true;
            } 
        }

        $(document).ready(function() {
            checkboxes();

            var checkAll = document.getElementById('checkAll');
            // add event listener to the checkbox
            checkAll.addEventListener('change', function() {
                // get all the checkboxes
                var cb = document.querySelectorAll('input[type="checkbox"]');
                // loop through the checkboxes
                cb.forEach(function(checkbox) {
                    // set the checked property to the value of the checkAll checkbox
                    checkbox.checked = checkAll.checked;
                });
                checkboxes(true);
            });
            $('#qris_type').select2({
                placeholder: 'QRIS Type',
                multiple: true
            });
            $('#merchant_id').select2({
                ajax: {
                    url: "{{ route('json.merchant.select') }}",
                    type: "GET",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },

                    processResults: function(data, params) {
                        let more = data.more;                        
                        let result = data.result.map(function(item) {
                            item.id = item.id;
                            item.text = item.name;
                            return item;
                        });

                        return {
                            pagination: {
                                more: more
                            },
                            results: result,
                        };
                    },
                },
                cache: true,
                placeholder: 'Select Merchant',
            }).on("select2:select", function (data) {
                $('#merchant_name').empty();
                $('#merchant_name').val(data.params.data.name);
            });

            $('#merchant_id').change(function () {
                old_merchant_branch_id = null;
                $('#merchant_branch_id').val(null).trigger('change');
                $('#merchant_branch_id').trigger('change.select2'); 
                $('#merchant_branch_id').empty();
                $('#btn-search').removeAttr('disabled');
            });

            $("#merchant_branch_id").select2({
                ajax: {
                    url: "{{ route('json.merchant.branches.select') }}", // ensure you replace this with your actual URL
                    dataType: 'json',
                    delay: 250, // delay in milliseconds to wait after typing stops
                    // Prepend the special options
                    data: function(params) {
                        // If merchant_id is not selected, don't perform AJAX
                        let merchant_id = $('#merchant_id').val();
                        if (!merchant_id) {
                            merchant_id = '00000000000000000000';
                        }
                        return {
                            merchant_id: merchant_id,
                            merchant_branch_id: old_merchant_branch_id,
                            search: params.term,
                            page: params.page || 1,
                            per_page: 50
                        };
                    },
                    processResults: function (data, params) {
                        allOptions = data.results.map(option => option.id);
                        // if more is false, then add select all option
                        // check if  $('#merchant_branch_id') already has 'none' option
                        let selectElement = document.querySelector('#merchant_branch_id');
                        let is_none_exists = document.getElementById('deselect-all') !== null;

                        if(!is_none_exists) {
                            // Create the "Deselect All" option element
                            let deselectAllOption = document.createElement('option');
                            deselectAllOption.value = 'none';
                            deselectAllOption.text = 'Deselect All';

                            // Insert it as the first option
                            let selectElement = document.querySelector('#merchant_branch_id');
                            selectElement.insertBefore(deselectAllOption, selectElement.firstChild);

                            $(".select2-results").prepend(
                                '<li class="custom-select2-results__option select2-results__option" id="deselect-all" onclick="deselectAll()" role="option" aria-selected="false" data-select2-id="none">Deselect All</li>'
                            );
                        }
                        let is_all_exists = document.getElementById('select-all') !== null;

                        if (!is_all_exists) {
                            // Create the "Select All" option element
                            let selectAllOption = document.createElement('option');
                            selectAllOption.value = 'all';
                            selectAllOption.text = 'Select All';

                            // Insert it as the first option
                            selectElement.insertBefore(selectAllOption, selectElement.firstChild);
                            $(".select2-results").prepend(
                                '<li class="custom-select2-results__option select2-results__option" id="select-all" onclick="selectAll()" role="option" aria-selected="false" data-select2-id="all">Select All</li>'
                            );
                        }
                        let current_val = $('#merchant_branch_id').val();
                        // convert to integer
                        current_val = current_val.map(function (val) {
                            if (val === 'all' || val === 'none') {
                                return val;
                            }
                            return parseInt(val);
                        });
                        let existing_options_ids = $('#merchant_branch_id').children().map(function() {
                            return this.value;
                        }).get();
                        existing_options_ids = existing_options_ids.map(function (val) {
                            if (val === 'all' || val === 'none') {
                                return val;
                            }
                            return parseInt(val);
                        });
                        // render the results as a list of real <option> tags
                        for (let i = 0; i < data.results.length; i++) {
                            id = parseInt(data.results[i].id);
                            // if the option is already selected or the option is already in the dropdown, skip it
                            if (current_val.includes(data.results[i].id) || existing_options_ids.includes(data.results[i].id)) {
                                continue;
                            }
                            
                            $('#merchant_branch_id').append(new Option(data.results[i].text, data.results[i].id, false, false));
                        }
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    }
                },
                minimumInputLength: 0, // Allows empty search term to trigger the dropdown
                placeholder: 'Select a branch', // set default text to display in the dropdown
                escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
                width: '100%', // width of the dropdown
                scrollAfterSelect: true, // keep the dropdown open after selection
                closeOnSelect: false,
            });

            $("#merchant_branch_id").on('select2:select', function (e) {
                var selected = e.params.data.id;
                if (selected === 'none') {
                    // Deselect all options
                    $(this).val(null).trigger('change');
                }
            });
            // set old_merchant_branch_id as value
            $('#merchant_branch_id').val(old_merchant_branch_id).trigger('change');
        });
        $('#merchant_branch_id').on('change', function () {
            let selected = $(this).val();
            if (selected !== null && selected.length > 0 && !selected.includes('none')) {
                $('#btn-search').removeAttr('disabled');
            } else {
                $('#btn-search').attr('disabled', 'disabled');
            }
        });
        function selectAll() {
            $("#merchant_branch_id > option").prop("selected", true);
            $("#merchant_branch_id option[value='all']").prop("selected", false);
            $("#merchant_branch_id option[value='none']").prop("selected", false);
            $("#merchant_branch_id").trigger("change");
            let allOptions = document.querySelectorAll('#merchant_branch_id option');
            allOptions.forEach(option => {
                if (option.value !== 'all' && option.value !== 'none') {
                    merchant_branches.push({id: option.value, text: option.text});
                }
            });

            $('#btn-search').removeAttr('disabled');
            $('#merchant_branch_id').val(merchant_branches.map(item => item.id)).trigger('change');
        }

        function deselectAll() {
            $('#btn-search').attr('disabled', 'disabled');
            $('#merchant_branch_id').val('').trigger('change');
        }
    </script>
    <style>
        .custom-select2-results__option {
            cursor: pointer;
            list-style-type: none;
        }
        /* on hover */
        .custom-select2-results__option:hover {
            background-color: #494c55;
        }
    </style>
@endsection
