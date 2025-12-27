@extends('layouts.master')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Owner List')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="breadcrumb-elements-item dropdown p-0">

                </div>
            </div>
        </div>
        {{-- <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Owner List')</h4>
            </div>
        </div> --}}

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Owner List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <div class="form-group row">
            <h5 class="ml-2 mr- card-title">@lang('cms.Owner List')</h5>
            <div class="my-sm-auto ml-sm-auto mb-3">
                @if (in_array('MASTER_DATA.OWNERS.EDIT', $access_control))
                    <a href="{{ route('yukk_co.owners.create') }}" class="form-control justify-content-center">
                        <i class="icon-add mr-1"></i>@lang('Add Owner')
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-auto status-filter" id="statusFilter">
                <select class="form-control select2" id="status" name="status">
                    <option value="">@lang('cms.Select Status')</option>
                    <option value="1">@lang('cms.Active')</option>
                    <option value="0">@lang('cms.Inactive')</option>
                </select>
            </div>

            <div class="col-auto type-filter" id="typeFilter">
                <select class="form-control select2" id="type" name="type" data-minimum-results-for-search="Infinity">
                    <option value="">@lang('cms.Select Type')</option>
                    <option value="INDIVIDU">@lang('cms.Individual')</option>
                    <option value="BADAN_HUKUM">@lang('cms.Badan Hukum')</option>    
                </select>
            </div>
        </div>

        <table class="table table-bordered table-striped dataTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('cms.Owner ID')</th>
                    <th>@lang('cms.Owner Type')</th>
                    <th>@lang('cms.Name')</th>
                    <th>@lang('cms.Phone')</th>
                    <th>@lang('cms.Email')</th>
                    <th>@lang('cms.KTP No')</th>
                    <th>@lang('cms.NPWP No')</th>
                    <th>@lang('cms.Status')</th>
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
                            <a href="{{ route('yukk_co.owners.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                <a href="{{ route('yukk_co.owners.list', array_merge(request()->all(), ['page' => $i])) }}"
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
                            <a href="{{ route('yukk_co.owners.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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
                searching: true,
                order: [[0, 'desc']],
                ajax: {
                    url: '{{ route('yukk_co.owners.datatable') }}',
                    type: 'POST',
                    data: function(d) {
                        d.type = $('#type').val(); // Get type filter
                        d.filter = $('#filter').val(); // Get search filter
                    }
                },
                
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: true
                    },
                    {
                        data: 'merchant_type',
                        name: 'merchant_type',
                        searchable: true,
                        orderable: true,
                        render: function(data) {
                            if (data) {
                                return data.toLowerCase().replace(/_/g, ' ').replace(/\b\w/g, function(char) {
                                    return char.toUpperCase();
                                });
                            }

                            return data;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable: true
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        searchable: true,
                        render: function(data) {
                            if (data != null) {
                                return '<a href="tel:' + data + '">' + data + '</a>';
                            }

                            return data
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        searchable: true,
                        width: '30%',
                        render: function(data) {
                            if (data != null) {
                                return '<a href="mailto:' + data + '">' + data + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        data: 'id_card_number',
                        name: 'id_card_number',
                        searchable: true,
                        render: function(data) {
                            if (data == null) {
                                return '-';
                            }

                            return data;
                        }
                    },
                    {
                        data: 'npwp_number',
                        name: 'npwp_number',
                        searchable: true,
                        render: function(data) {
                            if (data == null) {
                                return '-';
                            }

                            return data;
                        }
                    },
                    {
                        data: 'active',
                        name: 'active',
                        width: '10%',
                        render: function(data) {
                            if (data == true) {
                                return '<span class="badge badge-success">@lang('cms.Active')</span>';
                            } else {
                                return '<span class="badge badge-danger">@lang('cms.Inactive')</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '5%',
                    },
                ],
            });
            
            $('#statusFilter').appendTo('.dataTables_filter');
            $('#typeFilter').appendTo('.dataTables_filter');
            $('#typeFilter').css({
                'display': 'inline-block',
                'margin-left': '10px',
                'min-width': '140px'
            });
            $('#statusFilter').css({
                'display': 'inline-block',
                'margin-left': '10px',
                'min-width': '110px'
            });

            $('#type').select2({
                placeholder: 'Select Type',
                allowClear: true
            });

            $('#status').select2({
                placeholder: 'Select Status',
                allowClear: true
            });
            
            $('#type, #status').on('change', function() {
                var typeVal = $('#type').val();
                var statusVal = $('#status').val();
                $('.dataTable').DataTable().ajax.url('{{ route('yukk_co.owners.datatable') }}?type=' + typeVal + '&status=' + statusVal).load();
            });
        });
    </script>
@endsection
