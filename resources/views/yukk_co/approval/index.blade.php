<x-app-layout>
    <x-page.header :title="__('cms.Manage Approval')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms." . $title) }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.' . $title)">
        <div class="row mb-3 text-center">
            <div class="col m-1">
                <div class="border pt-2 card-pending">
                    <h4>{{ isset($statusCounter['PENDING']) ? $statusCounter['PENDING'] : 0 }}</h4>
                    <h5>TOTAL PENDING</h5>
                </div>
            </div>
            <div class="col m-1">
                <div class="border pt-2 card-approved">
                    <h4>{{ isset($statusCounter['APPROVED']) ? $statusCounter['APPROVED'] : 0 }}</h4>
                    <h5>TOTAL APPROVED</h5>
                </div>
            </div>
            <div class="col m-1">
                <div class="border pt-2 card-rejected">
                    <h4>{{ isset($statusCounter['REJECTED']) ? $statusCounter['REJECTED'] : 0 }}</h4>
                    <h5>TOTAL REJECTED</h5>
                </div>
            </div>
        </div>

        <div class="dropdown mb-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" data-flip="false" aria-haspopup="true" aria-expanded="false">
                Filter @if ($filterCounter > 0)
                    <span>|</span> <span>{{ $filterCounter }}</span>
                @endif
            </button>
            <form action="{{ url()->current() }}" method="GET">
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li class="mb-2 p-2" style="background-color: #4F4A45;">
                    <div class="row text-center">
                            <div class="col">
                                <a href="{{ request()->url() }}" class="btn btn-outline-danger">Reset</a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-outline-primary">Apply</button>
                            </div>
                    </div>
                    </li>
                    <li>
                        <label class="dropdown-item"><input type="checkbox" id="date_filter"
                        @if ($filter['dates_by'])
                            checked
                        @endif
                        >&nbsp;&nbsp; Date</label>
                    </li>
                    <li class="dates_by pl-3 submenu
                    @if ($filter['dates_by'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="radio" name="dates_by" value="created_at"
                        @if ($filter['dates_by'] == 'created_at')
                            checked
                        @endif
                        >&nbsp;&nbsp; Created At</label>
                        <ul id="dates_by_submenu" class="dropdown-menu dropdown-submenu p-2">
                            <li>Start Date</li>
                            <li class="mb-2">
                                <input type="date" class="form-control bg-default" name="start_date" value="{{ $filter['start_date'] }}">
                            </li>
                            <li>End Date</li>
                            <li>
                                <input type="date" class="form-control bg-default" name="end_date" value="{{ $filter['end_date'] }}">
                            </li>
                        </ul>
                    </li>
                    <li class="dates_by pl-3 submenu
                    @if ($filter['dates_by'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="radio" name="dates_by" value="updated_at"
                        @if ($filter['dates_by'] == 'updated_at')
                            checked
                        @endif
                        >&nbsp;&nbsp; Updated At</label>
                    </li>
                    <li>
                        <label class="dropdown-item"><input type="checkbox" id="status"
                        @if ($filter['status'])
                            checked
                        @endif
                        >&nbsp;&nbsp; Status</label>
                    </li>
                    <li class="status_by pl-3 submenu
                    @if ($filter['status'])
                        submenu-active
                    @endif
                    ">
                        <p class="dropdown-item">
                            <button id="status_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                        </p>
                    </li>
                    <li class="status_by pl-3 submenu
                    @if ($filter['status'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="status[]" value="PENDING"
                        @if (in_array('PENDING', $filter['status']))
                            checked
                        @endif
                        >&nbsp;&nbsp; PENDING</label>
                    </li>
                    <li class="status_by pl-3 submenu
                    @if ($filter['status'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="status[]" value="APPROVED"
                        @if (in_array('APPROVED', $filter['status']))
                            checked
                        @endif
                        >&nbsp;&nbsp; APPROVED</label>
                    </li>
                    <li class="status_by pl-3 submenu
                    @if ($filter['status'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="status[]" value="REJECTED"
                        @if (in_array('REJECTED', $filter['status']))
                            checked
                        @endif
                        >&nbsp;&nbsp; REJECTED</label>
                    </li>
                    <li>
                        <label class="dropdown-item"><input type="checkbox" id="action"
                        @if ($filter['request_action'])
                            checked
                        @endif
                        >&nbsp;&nbsp; Action</label>
                    </li>
                    <li class="action_by pl-3 submenu
                    @if ($filter['request_action'])
                        submenu-active
                    @endif
                    ">
                        <p class="dropdown-item">
                            <button id="action_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                        </p>
                    </li>
                    <li class="action_by pl-3 submenu
                    @if ($filter['request_action'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="request_action[]" value="CREATE"
                        @if (in_array('CREATE', $filter['request_action']))
                            checked
                        @endif
                        >&nbsp;&nbsp; CREATE</label>
                    </li>
                    <li class="action_by pl-3 submenu
                    @if ($filter['request_action'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="request_action[]" value="UPDATE"
                        @if (in_array('UPDATE', $filter['request_action']))
                            checked
                        @endif
                        >&nbsp;&nbsp; UPDATE</label>
                    </li>
                    <li class="action_by pl-3 submenu
                    @if ($filter['request_action'])
                        submenu-active
                    @endif
                    ">
                        <label class="dropdown-item"><input type="checkbox" name="request_action[]" value="DELETE"
                        @if (in_array('DELETE', $filter['request_action']))
                            checked
                        @endif
                        >&nbsp;&nbsp; DELETE</label>
                    </li>
                </ul>

                <div class="row justify-content-between">
                    <div class="row mt-3 ml-1">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label>@lang("cms.Name")</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="@lang("cms.Search Name")" value="{{ $filter['search'] }}">
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group input-group-append position-static">
                                    <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i>@lang("cms.Search")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mr-2">
                        <div class="form-group">
                            <label>@lang("cms.Per page")</label>
                            <div class="form-group">
                                <select class="form-group per-page" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
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
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="selectedAll[]" onchange="onSelectAllToggle()"> All</th>
                        <th>{{ __("cms.ID") }}</th>
                        <th>{{ __("cms.Name") }}</th>
                        <th>{{ __("cms.Request By") }}</th>
                        <th>{{ __("cms.Updated By") }}</th>
                        <th>{{ __("cms.Request Action") }}</th>
                        <th>{{ __("cms.Status") }}</th>
                        <th>{{ __("cms.Created At") }}</th>
                        <th>{{ __("cms.Updated At") }}</th>
                        <th>{{ __("cms.Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($approvals) == 0)
                        <tr class="text-center">
                            <td colspan="7"> Data Not Found</td>
                        </tr>
                    @endif
                    @foreach($approvals as $item)
                    <tr>
                        <td>
                            @if ($item['status'] == "PENDING")
                                <input type="checkbox" name="userSelected[]" data-id="{{ $item['id'] }}" onchange="onChangeSelected(this)">
                            @else

                            @endif
                        </td>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ $item['properties']['identity']['name'] ?? '' }}</td>
                        <td>{{ $item['changed_by'] }}</td>
                        <td>{{ $item['action_by']['email'] ?? '' }}</td>
                        <td>
                            <span class="badge
                                @if ($item['type'] == "DELETE")
                                    bg-danger
                                @elseif ($item['type'] == "CREATE")
                                    bg-primary
                                @else
                                    bg-warning
                                @endif
                            ">{{ $item['type'] }}</span>
                        </td>
                        <td>
                            <span class="badge
                                @if ($item['status'] == "PENDING")
                                    bg-secondary
                                @elseif ($item['status'] == "APPROVED")
                                    bg-success
                                @else
                                    bg-danger
                                @endif
                            ">{{ $item['status'] }}</span>
                        </td>
                        <td>{{ date_format(date_create($item['created_at']), 'd-m-Y H:i:s') }}</td>
                        <td>{{ date_format(date_create($item['updated_at']), 'd-m-Y H:i:s') }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ request()->url() . '/' . $item['id'] }}" class="dropdown-item">
                                            @if ($item['status'] == "PENDING")
                                                <i class="icon-pencil7"></i> {{ __("cms.Edit") }}
                                            @else
                                                <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="float justify-content-between">
            <div class="float-left mt-3">
                {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
            </div>
            <div class="float-right mt-3">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-flat justify-content-end">
                            @php($plus_minus_range = 3)
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route($routing, array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                        <a href="{{ route($routing, array_merge(request()->all(), ['page' => $i])) }}"
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
                                    <a href="{{ route($routing, array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                       class="page-link">
                                        <i class="icon-arrow-right13"></i>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-confirm-toggle" tabindex="-1" role="dialog"
             aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirm-form"
                  data-attribute-action="{{ route($route) }}" class="modal-dialog"
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
    </x-page.content>

    @swal

    @push('styles')
    <style>
        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .submenu {
            display: none;
        }

        .submenu-active {
            display: block;
        }

        .card-pending {
            background-color: #F3B664;
            color: #000000;
        }

        .card-approved {
            background-color: #9FBB73;
            color: #000000;
        }

        .card-rejected {
            background-color: #FA7070;
            color: #000000;
        }

        .bg-default, .bg-default:focus {
            background-color: #383940;
            border: 1px solid white;
        }

        .btn-select-all:hover {
            background-color: white;
            border: 1px solid black;
            color: #000000;
        }

        .per-page {
            background-color: #1d1e21;
            color: #ffffff;
            padding: 10px;
        }
    </style>
    @endpush

    @push('scripts')

    @if ($filter['dates_by'])
        <script>
            $('#dates_by_submenu').css('display', 'block');
        </script>
    @endif

    <script>
        var selectedIds = []

        $('#date_filter').on('change', function() {
            if($('#date_filter').is(':checked')) {
                $('.dates_by').css('display', 'block');
            } else {
                $('input[type="date"]').val('');
                $('input[name="dates_by"]').prop('checked', false);
                $('.dates_by').css('display', 'none');
            }
        });

        $('#status').on('change', function() {
            if($('#status').is(':checked')) {
                $('.status_by').css('display', 'block');
            } else {
                $('input[name="status[]"]').prop('checked', false);
                $('.status_by').css('display', 'none');
            }
        });

        $('#action').on('change', function() {
            if($('#action').is(':checked')) {
                $('.action_by').css('display', 'block');
            } else {
                $('input[name="request_action[]"]').prop('checked', false);
                $('.action_by').css('display', 'none');
            }
        });

        $('input[name="dates_by"]').on('change', function() {
            $('#dates_by_submenu').css('display', 'block');
        });

        $('#status_all').on('click', function(e) {
            $('input[name="status[]"]').prop('checked', true);
        });

        $('#action_all').on('click', function(e) {
            $('input[name="request_action[]"]').prop('checked', true);
        });

        $(document).on('click', '.page-content .dropdown-menu', function (e) {
            e.stopPropagation();
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
            //add selectedIds to modal-confirm-input
            $('#modal-confirm-input').val(selectedIds)
            let selectedUser = $('input[name="userSelected[]"]:checked').length
            if (selectedUser > 0) {
                $('#btnRejectBulk').prop('disabled', false)
                $('#btnApproveBulk').prop('disabled', false)
            } else {
                $('#btnRejectBulk').prop('disabled', true)
                $('#btnApproveBulk').prop('disabled', true)
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

        $('#btnRejectBulk').on('click', function (e) {
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change ' + selectedIds.length + ' data status to REJECTED. Are you sure?')

            $('#btnConfirm').text('Reject');
            $('#btnConfirm').removeClass('btn-primary').addClass('btn-danger')

            //Input Hidden Data
            $('#modal-confirm-approveOrReject').val('REJECTED');
            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', selectedIds))
            $('#modal-confirm-toggle').modal('toggle')
        });

        $('#btnApproveBulk').on('click', function (e) {
            let modalConfirmMessage = $('#modal-confirm-message')
            modalConfirmMessage.empty()
            modalConfirmMessage.append('You will change ' + selectedIds.length + ' data status to APPROVED. Are you sure?')

            $('#btnConfirm').text('Approve');
            $('#btnConfirm').removeClass('btn-danger').addClass('btn-primary')

            //Input Hidden Data
            $('#modal-confirm-approveOrReject').val('APPROVED');
            let action = $('#modal-confirm-form').attr('data-attribute-action')
            $('#modal-confirm-form').attr('action', action.replace(':id', selectedIds))
            $('#modal-confirm-toggle').modal('toggle')
        });
    </script>
    @endpush

</x-app-layout>
