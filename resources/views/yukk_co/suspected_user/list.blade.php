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
                <h4>Suspected User List</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">Suspected User</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
<!-- boostrap tabs for yukk_app and beneficiary -->
    <div class="card">
        <div class="card-header">
            <h2>Suspected User List</h2>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
            <li class="nav-item">
                <a id="yukkAppTab" class="nav-link @if ($source_type == 'USER')active @endif" aria-current="page" href="#" onclick="switchSource('yukk_app')">Yukk App</a>
            </li>
            <li class="nav-item">
                <a id="beneficiaryTab" class="nav-link @if ($source_type == 'CUSTOMER')active @endif" href="#" onclick="switchSource('beneficiary')">Beneficiary</a>
            </li>
            </ul>
            <div id="yukkAppBody">
                <form action="{{ route('cms.yukk_co.suspected_user.list') }}" method="get">
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <input name="search" class="form-control" placeholder="Search" value="{{ @$search }}" />
                            </div>
                        </div>

                        <input type="hidden" name="source_type" value="{{ $source_type }}">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="status" class="form-control" id="">
                                    <option value="">Status</option>
                                    <option @if ($status == 'SUSPECTED') selected @endif value="SUSPECTED">SUSPECTED</option>
                                    <option @if ($status == 'BLOCKED') selected @endif value="BLOCKED">BLOCKED</option>
                                    <option @if ($status == 'RELEASED') selected @endif value="RELEASED">RELEASED</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="text" id="date_range" name="date_range" class="form-control" 
                                placeholder="@lang(" cms.Search Date Range")" 
                                @if (isset($date_range))
                                    value="{{ $date_range }}">
                                @else
                                value="{{ date("d-M-Y H:i:s") }} to {{ date("d-M-Y H:i:s") }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i>
                                    Go</button>
                            </div>
                        </div>
                    </div>
                    <!-- per page dropdown -->
                    <div class="row">
                    </div>
                </form>

                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th id="idHead">@if ($source_type == 'USER')
                                Yukk ID
                            @else
                                Beneficiary ID
                            @endif</th>
                            <th>KTP</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Matched Identity</th>
                            <th>DTTOT Batch</th>
                            <th>Score</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>@lang('cms.Actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach (@$users as $index => $user)
                            <tr>
                                <td>
                                    {{ $user['name'] }}
                                </td>
                                <td>
                                    @if($source_type == 'USER')
                                        {{ $user['additional']['yukk_id'] }}
                                    @else
                                        {{ $user['source_id'] }}
                                    @endif
                                </td>
                                <td>{{ $user['identity_value'] }}</td>
                                <td>{{ $user['phone_number'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td><p><a href="{{ route('cms.yukk_co.suspected_user.detail', $user['id']) }}">
                                    {{ $user['reference']['densus_code'] }}
                                </a></p></td>
                                <td>{{ $user['reference']['batch'] }}</td>
                                <td>
                                    {{ $user['score'] }}
                                </td>
                                <td>{{ date('d-M-Y H:i:s', strtotime($user['created_at'])) }}</td>
                                <td>
                                    @if ($user['status'] == 'SUSPECTED')
                                        <span class="badge badge-warning">SUSPECTED</span>
                                    @elseif ($user['status'] == 'BLOCKED')
                                        <span class="badge badge-danger">BLOCKED</span>
                                    @elseif ($user['status'] == 'RELEASED')
                                        <span class="badge badge-success">RELEASED</span>
                                    @else
                                        <span class="badge badge-info">{{ $user['status'] }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <x-table.action-dropdown>
                                        <a href="{{ route('cms.yukk_co.suspected_user.detail', $user['id']) }}"
                                            class="dropdown-item">Detail</a>
                                        <a href="#"
                                            onclick="onActivationToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['status'] }}')"
                                            class="dropdown-item">Change Status</a>
                                    </x-table.action-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modal-confirmation-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form" enctype="multipart/form-data"
                data-attribute-action="{{ route('cms.yukk_co.suspected_user.update', ':id') }}" class="modal-dialog"
                role="document" method="post" action="">
                @csrf
                <input id="modal-confirmation-input" name="id" type="hidden" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="text-align:center"> Change Status From <br/>
                        <select name="currentStatus" id="modal-confirmation-status" class="form-control" style="margin-bottom: 10px;">
                        </select>
                        <p style="text-align:center"> To <br/>
                        <select name="action" id="modal-confirmation-new-status" class="form-control" style="margin-bottom: 10px;">
                            <option value="BLOCKED">BLOCKED</option>
                            <option value="RELEASED">RELEASED</option>
                        </select>
                        <hr/>
                        <label class="form-label">Reason</label> <br/>
                        <textarea name="reason" id="" cols="30" rows="5" class="form-control" style="margin-bottom: 10px;"></textarea>
                        <!-- supporting documents / file -->
                        <label class="form-label">Supporting Documents</label> <br/>
                        <div id="additionalFiles" style="margin-bottom: 10px;">
                            <div style="margin-bottom: 10px; display:flex; justify-content:space-between">
                                <input type="file" name="file[]" id="supportingFiles" class="form-control">
                                <!-- remove file x -->
                                <a href="#" onclick="removeSupportingFile(event, this)" class="btn btn-danger" style="margin-left: 10px; display:none">X</a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addSupportingFile()">Add Supporting File</button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                                    <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => 1])) }}"
                                        class="page-link"><i class="icon-first"></i></a>
                                </li>
                            @endif
                            <!-- previous page if current page > 1 -->
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link"><i class="icon-arrow-left12"></i></a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i == $current_page)
                                    <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $i])) }}"
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
                                    <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link">{{ $current_page - 1 }}</a>
                                </li>
                                @endif
                                <li class="page-item active"><a href="#" class="page-link">{{ $current_page }}</a></li>
                                @if ($current_page + 1 < $last_page)
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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
                                    <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                        class="page-link"><i class="icon-arrow-right13"></i></a>
                                </li>
                            @endif
                            <!-- last page -->
                            @if ($current_page == $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-last"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $last_page])) }}"
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
                                        <a href="{{ route('cms.yukk_co.suspected_user.list', array_merge(request()->all(), ['page' => $i])) }}"
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
        var supportingFiles = [];
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
                "columnDefs": [{
                    "orderable": false,
                    "targets": [10]
                }],
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm',
                    firstDay: 1,
                },
                timePicker: true,
                maxDate: new Date(),
                timePicker24Hour: true,
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

        function onActivationToggleConfirmation(event, id, status="") {
            // add status to modal confirmation
            status = status == "" ? "PENDING" : status;
            $('#modal-confirmation-status').html('<option value="'+status+'">'+status+'</option>');
            // if status == "BLOCKED" remove option SUSPECTED and BLOCKED
            if(status == "BLOCKED"){
                $('#modal-confirmation-new-status option[value="SUSPECTED"]').remove();
                $('#modal-confirmation-new-status option[value="BLOCKED"]').remove();
            }
            $('#modal-confirmation-new-status').val(status);

            $('#modal-confirmation-input').val(id)

            let action = $('#modal-confirmation-form').attr('data-attribute-action')
            $('#modal-confirmation-form').attr('action', action.replace(':id', id))

            $('#modal-confirmation-toggle').modal('toggle')
        }

        function switchSource(target){
            let urlParams = ""
            // set source type
            let source_type = target == 'beneficiary' ? 'CUSTOMER' : 'USER';
            // url encode the search query
            urlParams += "source_type="+source_type;
            window.location.href = "{{ route('cms.yukk_co.suspected_user.list') }}?"+urlParams;
        }
        // addSupportingFile
        function addSupportingFile(){
            // add new file input to additional file container
            let additionalFiles = document.getElementById("additionalFiles");
            // append new file input
            let newFileInput = document.createElement("div");
            newFileInput.style.marginBottom = "10px";
            newFileInput.style.display = "flex";
            newFileInput.style.justifyContent = "space-between";
            newFileInput.innerHTML = '<input required type="file" name="file[]" id="supportingFiles" class="form-control"> <a href="#" onclick="removeSupportingFile(event, this)" class="btn btn-danger" style="margin-left: 10px; display:none">X</a>';
            additionalFiles.appendChild(newFileInput);
            // count how many file input in additionalFiles 
            let fileInputCount = additionalFiles.getElementsByTagName("input").length;
            // if file input count > 1, show remove button
            if(fileInputCount > 1){
                let removeButton = additionalFiles.getElementsByTagName("a");
                for(let i = 0; i < removeButton.length; i++){
                    removeButton[i].style.display = "block";
                }
            }
        }
        // removeSupportingFile
        function removeSupportingFile(event, target){
            event.preventDefault();
            // remove file input from additional file container
            let additionalFiles = document.getElementById("additionalFiles");
            additionalFiles.removeChild(target.parentNode);
            // count how many file input in additionalFiles 
            let fileInputCount = additionalFiles.getElementsByTagName("input").length;
            // if file input count == 1, hide remove button
            if(fileInputCount == 1){
                let removeButton = additionalFiles.getElementsByTagName("a");
                for(let i = 0; i < removeButton.length; i++){
                    removeButton[i].style.display = "none";
                }
            }
        }
    </script>
    <style>
    </style>
@endsection
