@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.QRIS (PTEN) Menu')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.merchant.pten.list') }}" class="breadcrumb-item">@lang('cms.QRIS (PTEN) Menu')</a>
                    <span class="breadcrumb-item active">@lang('cms.List Merchant Branch')</span>
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
                <h5 class="ml-2 mr- card-title">@lang('cms.List Merchant Branch')</h5>
            </div>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('yukk_co.merchant.pten.bulk.submit') }}">
                @csrf
                <table class="table table-bordered table-striped dataTable" id="merchantBranchesTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select-all" id="select-all"></th>
                            <th>@lang('cms.Merchant Name')</th>
                            <th>@lang('cms.Branch Name')</th>
                            <th>@lang('cms.Start Contract Date')</th>
                            <th>@lang('cms.End Contract Date')</th>
                            <th>@lang('cms.MID')</th>
                            <th>@lang('cms.MPAN')</th>
                            <th>@lang('cms.Status')</th>
                            <th>@lang('cms.Last Updated At')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
                
                <!-- Submit button for bulk actions -->
                <button type="submit" class="btn btn-block btn-primary mt-4 mx-auto">@lang('cms.Submit to PTEN')</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#merchantBranchesTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pageLength: 10,
            ajax: {
                url: "{{ route('yukk_co.merchant.pten.bulk.listData') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.search.value = d.search.value || '';  // Pass the search value if available
                }
            },
            columns: [
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="checkbox" name="checkbox[]" value="${row.id}">`;
                    },
                    orderable: false,
                    searchable: false,
                },
                { data: 'merchant.name', name: 'merchant.name' },
                { data: 'name', name: 'Branch Name' },
                { data: 'start_date', name: 'Start Contract Date' },
                { data: 'end_date', name: 'End Contract Date' },
                { data: 'mid', name: 'MID' },
                { data: 'mpan', name: 'MPAN' },
                {
                    data: 'status_pten',
                    render: function(data, type, row) {
                        switch(data) {
                            case "READY_TO_SUBMIT":
                                return row.customer_id == null 
                                    ? '<span class="badge badge-danger badge-pill">NOT COMPLETED</span>' 
                                    : '<span class="badge badge-info badge-pill">READY</span>';
                            case "WAITING_FROM_PTEN":
                                return '<span class="badge badge-warning badge-pill">PENDING</span>';
                            case "APPROVED":
                                return '<span class="badge badge-success badge-pill">APPROVED</span>';
                            case "REJECTED":
                                return '<span class="badge badge-secondary badge-pill">REJECTED</span>';
                            default:
                                return '<span class="badge badge-secondary badge-pill">UNKNOWN</span>';
                        }
                    }
                },
                { data: 'updated_at', name: 'Last Updated At' }
            ],
            ordering: false,
            searching: true,
            lengthChange: true,
            info: true,
        });



        // Select/Deselect all checkboxes
        $('#select-all').on('click', function() {
            const isChecked = this.checked;
            $('#merchantBranchesTable tbody input[type="checkbox"]').each(function() {
                this.checked = isChecked;
            });
        });
    });
</script>
@endsection
