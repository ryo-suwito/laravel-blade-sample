@extends('layouts.master')

@section('header')
    <!-- local style -->
    <style>
        .badge {
            margin: 2px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>DTTOT Approval</h4>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">DTTOT Approval</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>DTTOT Approval List</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('cms.yukk_co.dttot_approval.list') }}" method="get">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <input name="search" class="form-control" placeholder="Search" value="{{ @$filter['search'] }}" />
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <select name="request" class="form-control" id="">
                            <option value="" @if(!isset($filter['request']) ||  @$filter['request'] == null)
                                selected
                                @endif
                                > All Request</option>
                                <option {{ @$filter['request'] == "ADD" ? 'selected' : '' }} value="ADD">Add</option>
                                <option {{ @$filter['request'] == "UPDATE" ? 'selected' : '' }} value="UPDATE">Update</option>
                                <option {{ @$filter['request'] == "DELETE" ? 'selected' : '' }} value="DELETE">Delete</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <select name="status" class="form-control select" id="">
                            <option value="" @if(!isset($filter['status']) ||  @$filter['status'] == null)
                                selected
                                @endif
                                > All Status</option>
                                <option {{ @$filter['status'] == "PENDING" ? 'selected' : '' }} value="PENDING">Pending</option>
                                <option {{ @$filter['status'] == "REJECTED" ? 'selected' : '' }} value="REJECTED">Rejected</option>
                                <option {{ @$filter['status'] == "APPROVED" ? 'selected' : '' }} value="APPROVED">Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="button-group">
                            <button class="btn btn-primary form-control" style="width:auto;display:inline-block" type="submit">Go</button>
                        </div>
                    </div>
                </div>
                <!-- per page dropdown -->
                @if($can_edit)
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <div class="button-group">
                                <button id="btnRejectBulk" type="button" class="btn btn-danger" disabled>Bulk Reject</button>
                                <button id="btnApproveBulk" type="button" class="btn btn-info" disabled>Bulk Approve</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <!-- select all user on this page -->
                        <th><input type="checkbox" name="selectedAll[]" onchange="onSelectAllToggle()"> All</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Densus Code</th>
                        <th>Request</th>
                        <th>Approval Status</th>
                        <th>Approver Action</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (@$users ?? [] as $index => $user)
                        <tr>
                            <!-- checkbox to select user -->
                            <td>
                                @if (($user['status'] == null || $user['status'] == 'PENDING') && $user['is_acted'] == false)
                                <input type="checkbox" name="userSelected[]" data-id="{{ $user['id'] }}" onchange="onChangeSelected(this)">
                                @endif
                            </td>
                            <td>{{ $user['reference']['name'] }}</td>
                            <td>{{ $user['reference']['type'] }}</td>
                            <td>{{ $user['reference']['densus_code'] }}</td>
                            <td>{{ $user['action']}}</td>
                            <td>{{ $user['status'] == null ? 'PENDING' : $user['status'] }}</td>
                            <td>
                                @if (isset($user['approved_by']) || isset($user['approved_by']))
                                    @if (isset($user['approved_by']) && $user['approved_by'])
                                        Approved by: <br/>
                                        @php
                                            $approvedByArray = explode(',', $user['approved_by']);
                                        @endphp
                                        @foreach($approvedByArray as $approvedBy)
                                            {{ $approvedBy }}<br>
                                        @endforeach
                                    @endif
                                    @if (isset($user['rejected_by']) && $user['rejected_by'])
                                        Rejected by: <br/>
                                        @php
                                            $rejectedByArray = explode(',', $user['rejected_by']);
                                        @endphp
                                        @foreach($rejectedByArray as $rejectedBy)
                                            {{ $rejectedBy }}<br>
                                        @endforeach
                                    @endif
                                @else
                                    No action taken
                                @endif
                            </td>
                            <td class="text-center">
                                <x-table.action-dropdown>
                                    <a href="{{ route('cms.yukk_co.dttot_approval.detail', $user['id']) }}"
                                        class="dropdown-item">Detail</a>
                                    @if (($user['status'] == null || $user['status'] == 'PENDING') && $user['is_acted'] == false && $can_edit)
                                    <a href="#" onclick="onApprovalToggleConfirmation(event, '{{ $user['id'] }}', '{{ str_replace("'", "\\'", $user['reference']['name']) }}')" class="dropdown-item">Approve</a>
                                    <a href="#" onclick="onRejectionToggleConfirmation(event, '{{ $user['id'] }}', '{{ str_replace("'", "\\'", $user['reference']['name']) }}')" class="dropdown-item">Reject</a>
                                    @endif
                                </x-table.action-dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="modal-confirm-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirm-form"
                data-attribute-action="{{ route('cms.yukk_co.dttot_approval.toggle') }}" class="modal-dialog"
                role="document" method="post" action="">
                @csrf
                <!-- input ids[] for multiple ids -->
                <input id="modal-confirm-input" name="ids" type="hidden" value="">
                <input id="modal-confirm-approveOrReject" name="approveOrReject" type="hidden" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Change Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="modal-confirm-message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnConfirm"></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        @if($total > 0)
                        <label for="">Total Rows : {{ $total }}</label>
                        @endif
                        <br>
                        <label for="">Showing 
                            <select name="per_page" class="form-control select" id="perPage" tabindex="-1" aria-hidden="true" style="display: inline; width: auto;">
                                <option  value="10" @if(!isset($filter['per_page']) ||  @$filter['per_page'] == null)
                                selected
                                @endif
                                >10</option>
                                <option {{ @$filter['per_page'] == "25" ? 'selected' : '' }} value="25">25</option>
                                <option {{ @$filter['per_page'] == "50" ? 'selected' : '' }} value="50">50</option>
                                <option {{ @$filter['per_page'] == "100" ? 'selected' : '' }} value="100">100</option>
                            </select>
                            per page
                        </label>
                    </div>
                </div>
                <div class="col-lg-8">
                    @if($total > 0)
                    <ul class="pagination pagination-flat justify-content-end">
                        <!-- show current page and last page -->
                        @php($plus_minus_range = 3)
                        <!--  always show 1,2,3,4,5 if last page > 5 -->
                        @if ($last_page > 5)
                            <!-- first page -->
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-first"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => 1])) }}"
                                        class="page-link"><i class="icon-first"></i></a>
                                </li>
                            @endif
                            <!-- previous page if current page > 1 -->
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link"><i class="icon-arrow-left12"></i></a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i == $current_page)
                                    <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $i])) }}"
                                            class="page-link">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            <!-- show ... if current_page > 6 -->
                            @if ($current_page > 7)
                                <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                            @endif
                            <!-- show current page, previous page and next page -->
                            @if ($current_page - 1 >= 5 )
                                @if ($current_page - 1 > 5 )
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link">{{ $current_page - 1 }}</a>
                                </li>
                                @endif
                                <li class="page-item active"><a href="#" class="page-link">{{ $current_page }}</a></li>
                                @if ($current_page + 1 < $last_page)
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                            class="page-link">{{ $current_page + 1 }}</a>
                                    </li>
                                @endif
                            @endif 

                            @if ($current_page < $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                            @endif
                            <!-- next page -->
                            @if ($current_page == $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                        class="page-link"><i class="icon-arrow-right13"></i></a>
                                </li>
                            @endif
                            <!-- last page -->
                            @if ($current_page == $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-last"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $last_page])) }}"
                                        class="page-link"><i class="icon-last"></i></a>
                                </li>
                            @endif
                        @else 
                            <!-- show 1,2,3,4,5 if last page <= 5 -->
                            @for ($i = 1; $i <= $last_page; $i++)
                                @if ($i == $current_page)
                                    <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.dttot_approval.list', array_merge(request()->all(), ['page' => $i])) }}"
                                            class="page-link">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var selectedIds = []
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
                // disable ordering on column checkbox and action
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ]
            });



            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
            // per page dropdown on change fetch data with new per page along with current filter
            $('#perPage').on('change', function() {
                let per_page = $(this).val()
                let url = new URL(window.location.href)
                let search_params = url.searchParams
                search_params.set('per_page', per_page)
                window.location.href = url.href
            })
        });

        function onChangeSelected(item){
            let id = $(item).attr('data-id')
            if ($(item).is(':checked')) {
                selectedIds.push(id)
            } else {
                selectedIds = selectedIds.filter(function(value, index, arr){
                    return value != id
                })
            }
            // add selectedIds to modal-confirm-input
            $('#modal-confirm-input').val(selectedIds)
            let selectedUser = $('input[name="userSelected[]"]:checked').length
            if (selectedUser > 0) {
                $('#btnRejectBulk').prop('disabled', false)
                $('#btnApproveBulk').prop('disabled', false)
                // attach onclick event to button reject bulk as onBulkRejectionToggleConfirmation
                $('#btnRejectBulk').attr('onclick', 'onBulkRejectionToggleConfirmation(event)')
                // attach onclick event to button approve bulk as onBulkApprovalToggleConfirmation
                $('#btnApproveBulk').attr('onclick', 'onBulkApprovalToggleConfirmation(event)')
            } else {
                $('#btnRejectBulk').prop('disabled', true)
                $('#btnApproveBulk').prop('disabled', true)
                // remove onclick event from button reject bulk
                $('#btnRejectBulk').removeAttr('onclick')
                // remove onclick event from button approve bulk
                $('#btnApproveBulk').removeAttr('onclick')
            }
        }

        function onSelectAllToggle() {
            if ($('input[name="selectedAll[]"]').is(':checked')) {
                // get all checkbox userSelected in this page
                let selectedUser = $('input[name="userSelected[]"]').length
                // check if all userSelected is checked
                for (let i = 0; i < selectedUser; i++) {
                    if (!$('input[name="userSelected[]"]')[i].checked) {
                        $('input[name="userSelected[]"]')[i].click()
                    }
                }
            } else {
                // get all checkbox userSelected in this page
                let selectedUser = $('input[name="userSelected[]"]').length
                // check if all userSelected is checked
                for (let i = 0; i < selectedUser; i++) {
                    if ($('input[name="userSelected[]"]')[i].checked) {
                        $('input[name="userSelected[]"]')[i].click()
                    }
                }
            }
        }

        function onRejectionToggleConfirmation(event, densus_code, name) {
            $('#modal-confirm-input').val(densus_code)
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change the approval status of ' + name + '. Are you sure?')
            // approveOrReject
            $('#modal-confirm-approveOrReject').val('reject')
            // change button confirm text to reject
            $('#btnConfirm').text('Reject')
            $('#densusCode').text(densus_code)
            // remove class btn-primary from button confirm, add class btn-danger
            $('#btnConfirm').removeClass('btn-primary').addClass('btn-danger')

            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', densus_code))
            $('#modal-confirm-toggle').modal('toggle')
        }

        function onApprovalToggleConfirmation(event, densus_code, name) {
            $('#modal-confirm-input').val(densus_code)
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change the approval status of ' + name + '. Are you sure?')
            // approveOrReject
            $('#modal-confirm-approveOrReject').val('approve')
            // change button confirm text to approve
            $('#btnConfirm').text('Approve')
            $('#densusCode').text(densus_code)
            // remove class btn-danger from button confirm, add class btn-primary
            $('#btnConfirm').removeClass('btn-danger').addClass('btn-primary')

            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', densus_code))
            $('#modal-confirm-toggle').modal('toggle')
        }

        // onBulkRejectionToggleConfirmation(event)
        function onBulkRejectionToggleConfirmation(event) {
            event.preventDefault()
            $('#modal-confirm-input').val(selectedIds)
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change the approval status of ' + selectedIds.length + ' rows. Are you sure?')
            // approveOrReject
            $('#modal-confirm-approveOrReject').val('reject')
            // change button confirm text to reject
            $('#btnConfirm').text('Reject')
            $('#densusCode').text(selectedIds)
            // remove class btn-primary from button confirm, add class btn-danger
            $('#btnConfirm').removeClass('btn-primary').addClass('btn-danger')

            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', selectedIds))
            $('#modal-confirm-toggle').modal('toggle')
        }

        // onBulkApprovalToggleConfirmation(event)
        function onBulkApprovalToggleConfirmation(event) {
            event.preventDefault()
            $('#modal-confirm-input').val(selectedIds)
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change the approval status of ' + selectedIds.length + ' rows. Are you sure?')
            // approveOrReject
            $('#modal-confirm-approveOrReject').val('approve')
            // change button confirm text to reject
            $('#btnConfirm').text('Approve')
            $('#densusCode').text(selectedIds)
            // remove class btn-primary from button confirm, add class btn-danger
            $('#btnConfirm').removeClass('btn-danger').addClass('btn-primary')

            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', selectedIds))
            $('#modal-confirm-toggle').modal('toggle')
        }
    </script>
@endsection
