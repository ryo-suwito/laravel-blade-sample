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
                    <span class="breadcrumb-item active">@lang('cms.QRIS (PTEN) Menu')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <style>
        .dt-buttons {
            display: none;
        }
    </style>

    <div class="card mt-4">
        <form action="{{ route('yukk_co.merchant.pten.list') }}" method="get">
            <div class="card-header">
                <div class="form-group row">
                    <h5 class="ml-2 mr- card-title">@lang('cms.QRIS (PTEN) Menu')</h5>

                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("QRIS_MENU.QRIS_PTEN.DELETE_QRIS"))
                        <div class="my-sm-auto ml-sm-auto mb-3">
                            <a href="{{ route('yukk_co.merchant.pten.delete.list') }}" class="list-icons-item btn-danger form-control">
                                @lang('cms.Delete QRIS')
                            </a>
                        </div>
                    @endif

                    <div class="my-sm-auto ml-2 mb-3">
                        <a href="{{ route('yukk_co.merchant.pten.download.list') }}" class="list-icons-item form-control">
                            @lang('cms.Download QRIS')
                        </a>
                    </div>

                    <div class="my-sm-auto ml-2 mb-3">
                        <button class="dropdown-item form-group" name="export_to_csv" value="1" id="export_csv"><i
                                class="icon-file-download"></i> @lang('cms.Export to CSV')</button>
                    </div>
                    @if (in_array('QRIS_MENU.QRIS_PTEN.UPDATE', $access_control))
                        <div class="my-sm-auto ml-2 mb-3 mb-sm-0">
                            <div class="dropdown p-0">
                                <a href="#" class="list-icons-item form-control" data-toggle="dropdown">
                                    @lang('cms.Submit to PTEN')
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('yukk_co.merchant.pten.submit') }}" class="dropdown-item">
                                        <i class="icon-add"></i>@lang('cms.Add New')
                                    </a>
                                    <a href="{{ route('yukk_co.merchant.pten.bulk.list') }}" class="dropdown-item">
                                        <i class="icon-add"></i>@lang('cms.Select Existing')
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <select class="form-control" name="status" id="status">
                            <option value="" @if ($status == '') selected @endif>ALL STATUS</option>
                            <option value="APPROVED" @if ($status == 'APPROVED') selected @endif>APPROVED</option>
                            <option value="APPROVED_PROCESSED" @if ($status == 'APPROVED_PROCESSED') selected @endif>APPROVED_PROCESSED</option>
                            <option value="REJECTED" @if ($status == 'REJECTED') selected @endif>REJECTED</option>
                            <option value="PENDING" @if ($status == 'PENDING') selected @endif>PENDING</option>
                            <option value="READY" @if ($status == 'READY') selected @endif>READY</option>
                            <option value="NOT COMPLETED" @if ($status == 'NOT COMPLETED') selected @endif>NOT COMPLETED
                            </option>
                            <option value="PENDING_DELETE_PTEN" @if ($status == 'PENDING_DELETE_PTEN') selected @endif>PENDING DELETE PTEN</option>
                            <option value="WAITING_DELETE_PTEN" @if ($status == 'WAITING_DELETE_PTEN') selected @endif>WAITING DELETE PTEN</option>
                            <option value="REJECTED_DELETE_PTEN" @if ($status == 'REJECTED_DELETE_PTEN') selected @endif>REJECTED DELETE PTEN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn ripple btn-secondary"><i class="icon-search4"></i></button>
                    </div>
                </div>
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>@lang('cms.Merchant Name')</th>
                            <th>@lang('cms.Branch Name')</th>
                            <th>@lang('cms.Start Contract Date')</th>
                            <th>@lang('cms.End Contract Date')</th>
                            <th>@lang('cms.MID')</th>
                            <th>@lang('cms.MPAN')</th>
                            <th>@lang('cms.NMID')</th>
                            <th>@lang('cms.Status')</th>
                            <th>@lang('cms.Last Updated At')</th>
                            <th class="not-export-col">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @include('yukk_co.merchant_pten.modal.submit-pten')
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/extensions/select.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            let table = $(".dataTable").DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        className: "buttonsToHide",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    }
                ],
                ajax: {
                    url: '{{ route('yukk_co.merchant.pten.data') }}',
                    type: 'POST',
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.status = $("#status").val();               
                        d.search = {
                            value: d.search.value // Ensure search parameter is sent as expected
                        };
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'merchant.name',
                        name: 'merchant.name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'mid',
                        name: 'mid'
                    },
                    {
                        data: 'mpan',
                        name: 'mpan'
                    },
                    {
                        data: 'nmid_pten',
                        name: 'nmid_pten'
                    },
                    {
                        data: 'status_pten',
                        name: 'status_pten'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searcable: false
                    },
                ]
            });

            $('div.toolbar').html('<b>{rows} Row Selected</b>');

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
            $('#submit-to-pten-modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id = button.data('id') // Extract info from data-* attributes
                $('#btn-proceed-submit-to-pten').attr('data-id', id); // JQuery
            })

            $("#btn-proceed-submit-to-pten").click(function(e) {
                $.ajax({
                    url: "{{ route('yukk_co.merchant.pten.pending.json') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "merchant_branch_id": $(this).attr("data-id"),
                    },
                    beforeSend: function() {
                        $("#btn-proceed-submit-to-pten").attr("disabled", true);
                        $("#btn-proceed-submit-to-pten").html('Proceeding...');
                    },
                    success: function(data) {
                        $('#status_' + data.id).html('');
                        $('#status_' + data.id).append(
                            '<span class="badge badge-warning badge-pill ml-auto">PENDING</span></span>'
                        );

                    },
                    complete: function(data) {
                        $('#submit-to-pten-modal').modal('hide');
                        $("#btn-proceed-submit-to-pten").html('Proceed');
                        $("#btn-proceed-submit-to-pten").removeAttr("disabled");
                    },
                });
            });
        });

        function submitToPten(name) {
            $('#merchant_branch_name').text(name);
        }
    </script>
@endsection
