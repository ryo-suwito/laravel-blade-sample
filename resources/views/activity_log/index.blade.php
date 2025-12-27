@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Activity Logs")</h4>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Activity Logs")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('activity.log.index') }}" method="GET">
                <div class="row justify-content-between">
                    <div class="row col-lg-10">
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">
                                <label>@lang("cms.Date Range")</label>
                                <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_date->format("d-M-Y") }} - {{ $end_date->format("d-M-Y") }}">
                            </div>
                        </div>
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">
                                <label>@lang("cms.Menu")</label>
                                <select id="menu" class="select2 form-group" name="menu">
                                    <option selected value="{{ null }}">@lang("cms.Select One")</option>
                                    <option value="Master Data" @if($menu == 'Master Data') selected @endif>@lang("cms.Master Data")</option>
                                    <option value="QRIS Menu" @if($menu == 'QRIS Menu') selected @endif>@lang("cms.QRIS Menu")</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">
                                <label>@lang("cms.Sub-Menu")</label>
                                <select id="submenu" class="select2 form-group" name="submenu">
                                    <option selected value="{{ null }}">@lang("cms.Select One")</option>
                                    <option value="Beneficiary" @if($submenu == 'Beneficiary') selected @endif>@lang("cms.Beneficiary")</option>
                                    <option value="Partners" @if($submenu == 'Partners') selected @endif>@lang("cms.Partners")</option>
                                    <option value="Partner Fees" @if($submenu == 'Partner Fees') selected @endif>@lang("cms.Partner Fees")</option>
                                    <option value="Data Verification" @if($submenu == 'Data Verification') selected @endif>@lang("cms.Data Verification")</option>
                                    <option value="Merchant Branches" @if($submenu == 'Merchant Branches') selected @endif>@lang("cms.Merchant Branches")</option>
                                    <option value="EDCs" @if($submenu == 'EDCs') selected @endif>@lang("cms.EDCs")</option>
                                    <option value="Manage QRIS Settings" @if($submenu == 'Manage QRIS Settings') selected @endif>@lang("cms.Manage QRIS Settings")</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">
                                <label>@lang("cms.Type")</label>
                                <select class="select2 form-group" name="type">
                                    <option selected value="{{ null }}">@lang("cms.Select One")</option>
                                    <option value="CREATE" @if($type == 'CREATE') selected @endif>CREATE</option>
                                    <option value="UPDATE" @if($type == 'UPDATE') selected @endif>UPDATE</option>
                                    <option value="DELETE" @if($type == 'DELETE') selected @endif>DELETE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">    
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="@lang("cms.Search")">
                            </div>
                        </div>
                        <div class="col-lg-4 row">
                            <div class="form-group col-lg-12">
                                <label>&nbsp;</label>
                                <div class="input-group input-group-append position-static">
                                    <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 justify-content-end">
                        <div class="flex flex-row">
                            <label>@lang("cms.Per page")&nbsp;</label>
                            <div class="form-group">
                                <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option @if($per_page == 10) selected @endif>10</option>
                                    <option @if($per_page == 25) selected @endif>25</option>
                                    <option @if($per_page == 50) selected @endif>50</option>
                                    <option @if($per_page == 100) selected @endif>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th class="text-center">@lang("cms.Timestamp")</th>
                        <th class="text-center">@lang("cms.User")</th>
                        <th class="text-center">@lang("cms.Role")</th>
                        <th class="text-center">@lang("cms.Menu")</th>
                        <th class="text-center">@lang("cms.Type")</th>
                        <th class="text-center">@lang("cms.Affected ID")</th>
                        <th class="text-center">@lang("cms.Actions")</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $index => $log)
                    <!-- @php
                        dump($log);
                    @endphp -->
                        <tr>
                            <th class="text-center">{{ @$log->created_at }}</th>
                            <th class="text-center">{{ @$log->changed_by }}</th>
                            <th class="text-center">{{ @$log->properties->role->name }}</th>
                            <th class="text-center">
                                {{ @$log->properties->menu->name }}
                                <br>
                                {{ @$log->properties->menu->submenu }}
                            </th>
                            <th class="text-center">{{ @$log->type }}</th>
                            @if($log->table_name == 'customers')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->customers->name}}</th>
                            @elseif($log->table_name == 'partners')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->partners->name}}</th>
                            @elseif($log->table_name == 'partner_fees')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->partner_fees->name}}</th>
                            @elseif($log->table_name == 'edcs' && $log->type == 'UPDATE')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->edcs->merchant_branch_name_pten}}</th>
                            @elseif($log->table_name == 'partner_logins' && $log->type == 'UPDATE')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->partner_logins->name}}</th>
                            @elseif($log->table_name == 'merchant_branches')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->merchant_branches->name}}</th>
                            @elseif($log->table_name == 'edcs' && $log->type == 'DELETE')
                                @php
                                    if(@$log->edcs->type == 'QRIS_DYNAMIC'){
                                        $edc_type = 'Dynamic';
                                    }else{
                                        $edc_type = 'Static';
                                    }
                                @endphp
                                <th class="text-center">{{ @$log->new_row_id . ' - EDC QRIS - '.@$edc_type}}</th>
                            @elseif($log->table_name == 'partner_logins' && $log->type == 'DELETE')
                                <th class="text-center">{{ @$log->new_row_id . ' - ' . @$log->partner_logins->title}}</th>
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('activity.log.detail', @$log->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("activity.log.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("activity.log.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("activity.log.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            function updateSubmenuOptions() {
                var menu = $('#menu').val();
                var submenuOptions = {
                    '': [
                        { value: 'Beneficiary', text: '@lang("cms.Beneficiary")' },
                        { value: 'Partners', text: '@lang("cms.Partners")' },
                        { value: 'Partner Fees', text: '@lang("cms.Partner Fees")' },
                        { value: 'Data Verification', text: '@lang("cms.Data Verification")' },
                        { value: 'Merchant Branches', text: '@lang("cms.Merchant Branches")' },
                        { value: 'Manage QRIS Settings', text: '@lang("cms.Manage QRIS Settings")' },
                        { value: 'EDCs', text: '@lang("cms.EDCs")' }
                    ],
                    'Master Data': [
                        { value: 'Beneficiary', text: '@lang("cms.Beneficiary")' },
                        { value: 'Partners', text: '@lang("cms.Partners")' },
                        { value: 'Partner Fees', text: '@lang("cms.Partner Fees")' },
                        { value: 'Data Verification', text: '@lang("cms.Data Verification")' },
                        { value: 'Merchant Branches', text: '@lang("cms.Merchant Branches")' },
                    ],
                    'QRIS Menu': [
                        { value: 'Manage QRIS Settings', text: '@lang("cms.Manage QRIS Settings")' },
                        { value: 'EDCs', text: '@lang("cms.EDCs")' }
                    ]
                };

                var $submenu = $('#submenu');
                $submenu.empty();
                $submenu.append('<option value="{{ null }}">@lang("cms.Select One")</option>');

                if (submenuOptions[menu]) {
                    submenuOptions[menu].forEach(function(option) {
                        $submenu.append('<option value="' + option.value + '">' + option.text + '</option>');
                    });
                }

                var selectedSubmenu = "{{ $submenu }}";
                var selectMenu = "{{ $menu }}";
                if (selectedSubmenu) {
                    if(selectMenu == menu && selectNumber == 1){
                        $submenu.val(selectedSubmenu);
                    }else{
                        selectedSubmenu = "";
                    }
                }
            }

            $('#menu').change(function() {
                selectNumber = 0;
                updateSubmenuOptions();
            });

            var selectNumber = 1;
            updateSubmenuOptions();
        });
    </script>
@endsection

