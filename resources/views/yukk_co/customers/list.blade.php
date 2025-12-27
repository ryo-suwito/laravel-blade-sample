@extends('layouts.master')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Beneficiary List')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="breadcrumb-elements-item dropdown p-0">

                </div>
            </div>
        </div>
        {{-- <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Beneficiary List')</h4>
            </div>
        </div> --}}

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Beneficiary List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header bg-transparent header-elements-sm-inline py-sm-0">
            <h4 class="card-title py-sm-3">@lang('cms.Beneficiary List')</h4>
            <div class="header-elements">
                <a href="{{ route('yukk_co.customers.bulk_search_form') }}" class="btn btn-primary"><i class="icon-search4 mr-2"></i> @lang("cms.Bulk Search")</a>
                <a href="{{ route('yukk_co.customers.create') }}" class="btn btn-light ml-3"><i class="icon-add mr-2"></i> @lang('cms.Add Beneficiary')</a>
            </div>
        </div>

        <div class="card-body">
            <!-- Status Filter -->
            <div class="status-filter" id="statusFilter">
                <select class="form-control select2" id="status" name="status">
                    <option value="">@lang('cms.Select Status')</option>
                    <option value="1">@lang('cms.Active')</option>
                    <option value="0">@lang('cms.Inactive')</option>
                </select>
            </div>

            <!-- Whitelist Filter -->
            <div class="whitelist-filter" id="whitelistFilter">
                <select class="form-control select2" id="whitelist" name="whitelist">
                    <option value="">@lang('cms.Select Whitelist')</option>
                    <option value="1">@lang('cms.Yes')</option>
                    <option value="0">@lang('cms.No')</option>
                </select>
            </div>

            <div class="dibursement-interval-filter" id="disbursementIntervalFilter">
                <select class="form-control select2" id="disbursement_interval" name="auto_disbursement_interval">
                    <option value="DAILY">@lang('cms.Daily')</option>
                    <option value="WEEKLY">@lang('cms.Weekly')</option>
                    <option value="ON_HOLD">@lang('cms.On_Hold')</option>
                </select>
            </div>

        <table class="table table-bordered table-striped dataTable"">
                <thead>
                    <tr>
                        <th>@lang('cms.Customer ID')</th>
                        <th>@lang('cms.Merchant Label')</th>
                        <th>@lang('cms.Email')</th>
                        <th>@lang('cms.Account Number')</th>
                        <th>@lang('cms.Account Name')</th>
                        <th>@lang('cms.Bank Name')</th>
                        <th>@lang('cms.Bank Branch Name')</th>
                        <th>@lang('cms.Disbursement Interval')</th>
                        <th>@lang('cms.Whitelist')</th>
                        <th>@lang('cms.Status')</th>
                        <th>@lang('cms.Created At')</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


        {{-- <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('yukk_co.customers.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                    class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('yukk_co.customers.list', array_merge(request()->all(), ['page' => $i])) }}"
                                        class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('yukk_co.customers.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                    class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".dataTable").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ordering: true,
                // "info": false,
                searching: true,
                ajax: {
                    url: '{{ route('yukk_co.customers.datatable') }}',
                    type: 'POST',
                    data: function(d) {
                    d.status = $('#status').val(); // Get status filter
                    d.whitelist = $('#whitelist').val(); // Get whitelist filter
                    d.filter = $('#filter').val(); // Get search filter
                }
                },

                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(item) {
                            if (item) {
                                return item.split(',').join('<br>')
                            }
                            return '-'
                        }
                    },
                    {
                        data: 'account_number',
                        name: 'account_number'
                    },
                    {
                        data: 'account_name',
                        name: 'account_name'
                    },
                    {
                        data: 'bank.name',
                        name: 'bank.name'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'auto_disbursement_interval',
                        name: 'auto_disbursement_interval'   
                    },
                    {
                        data: 'is_whitelist',
                        name: 'is_whitelist',
                        width: '10%',
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<span class="badge badge-success">@lang('cms.Yes')</span>';
                            } else {
                                return '<span class="badge badge-danger">@lang('cms.No')</span>';
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: '10%',
                        render: function(data, type, row) {
                            if (data == true) {
                                return '<span class="badge badge-success">@lang('cms.Active')</span>';
                            } else {
                                return '<span class="badge badge-danger">@lang('cms.Inactive')</span>';
                            }
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searcable: false,
                        width: '15%'
                    },
                ],
            });


            //Take the Status filter drop down and append it to the datatables_filter div.
        //You can use this same idea to move the filter anywhere withing the datatable that you want
            $('#statusFilter').appendTo('.dataTables_filter');
            // MAKE status filter 'inline' with the search box also set the margin-left to 10px and min-width to 100px
            $('#statusFilter').css({
                'display': 'inline-block',
                'margin-left': '10px',
                'min-width': '110px'
            });

            $('#whitelistFilter').appendTo('.dataTables_filter');
            $('#whitelistFilter').css({
                'display': 'inline-block',
                'margin-left': '10px',
                'min-width': '110px'
            });

            $('#disbursementIntervalFilter').appendTo('.dataTables_filter');
            $('#disbursementIntervalFilter').css({
                'display': 'inline-block',
                'margin-left': '10px',
                'min-width': '110px'
            });

            // Initialize select2 for both status and whitelist with placeholders and allow clear
            $('#status').select2({
                placeholder: 'Select Status',
                allowClear: true
            });

            $('#whitelist').select2({
                placeholder: 'Select Whitelist',
                allowClear: true
            });

            $('#disbursement_interval').select2({
                placeholder: 'Select Disbursement Interval',
                allowClear: true,
                multiple: true,
            })

            $('#disbursement_interval option').prop('selected', true).trigger('change');

            // Handle changes for both status and whitelist
            $('#status, #whitelist, #disbursement_interval').on('change', function() {
                // Get current value of status
                var statusVal = $('#status').val();

                // You can also fetch whitelist value here if needed for further logic
                var whitelistVal = $('#whitelist').val();

                var disbursementIntervalVal = $('#disbursement_interval').val();

                // Re-draw DataTables with updated URL, including status parameter
                $('.dataTable').DataTable().ajax.url('{{ route('yukk_co.customers.data') }}?status=' + statusVal + '&is_whitelist=' + whitelistVal + '&interval=' + disbursementIntervalVal).load();
            });



            $(".select2").select2();


            $(".date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $('#button-add-log-modal').click(function(e) {
                e.preventDefault();

                $('#add-customer-modal').modal('show');
            });

            $('#bank_id').on('change', function(e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                if (valueSelected == 1) {
                    $("#bank_type_name").val('BCA');
                } else {
                    $("#bank_type_name").val('NON-BCA');
                }

            });
        });
    </script>
@endsection
