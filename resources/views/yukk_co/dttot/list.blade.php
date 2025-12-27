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
                <h4>DTTOT List</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">DTTOT List</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cms.yukk_co.dttot.list') }}" method="get">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <input name="search" class="form-control" placeholder="Search" value="@if (isset($filter['search'])){{ $filter['search'] }}@endif">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <select name="type" class="form-control" id="">
                                <option value=""> All Type </option>
                                <option @if (isset($filter['type']) && $filter['type'] === "PERSONAL") selected @endif value="PERSONAL">Personal</option>
                                <option @if (isset($filter['type']) && $filter['type'] === "CORPORATE") selected @endif value="CORPORATE">Corporate</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <select name="status" class="form-control select2" id="">
                                <option value="" selected> All Status </option>
                                <option @if (isset($filter['status']) && $filter['status'] === "ACTIVE") selected @endif value="ACTIVE">Active</option>
                                <option @if (isset($filter['status']) && $filter['status'] === "INACTIVE") selected @endif value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group text-right">
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-primary">Go</button>
                                <button type="button" onclick="onImport()" class="btn btn-secondary">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Densus Code</th>
                        <th>Identity Type</th>
                        <th>Status</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (@$dttot_list ?? [] as $index => $user)
                        <tr>
                            <td>
                                @if(! empty($user['names']))
                                    @foreach (array_slice($user['names'], 0, 5) as $name)
                                        {{ $name }} 
                                        @if(! $loop->last)
                                        <br>
                                        @endif 
                                    @endforeach
                                    <!-- if length > 5 add ... -->
                                    @if (count($user['names']) > 5)
                                        <br>
                                        ...
                                    @endif
                                @else 
                                    {{ $user['name'] }}
                                @endif
                            </td>
                            <td>{{ $user['type'] }}</td>
                            <td>{{ $user['densus_code'] }}</td>
                            <td>@foreach (@$user['identities'] ?? [] as $index => $identity)
                                    {{ $identity['identity_type'] }} : {{ $identity['count'] }} <br>
                                @endforeach</td>
                            <td>{{ $user['status'] }}</td>
                            <td class="text-center">
                                <x-table.action-dropdown>
                                    <a href="{{ route('cms.yukk_co.dttot.detail', $user['id']) }}"
                                        class="dropdown-item">Detail</a>
                                    <a href="{{ route('cms.yukk_co.dttot.edit', $user['id']) }}"
                                        class="dropdown-item">Edit</a>
                                    <a href="#"
                                        onclick="onActivationToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['name'] }}')"
                                        class="dropdown-item">Delete</a>
                                </x-table.action-dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="modal-confirmation-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form"
                data-attribute-action="{{ route('cms.yukk_co.dttot.delete', ':id') }}" class="modal-dialog"
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
                        Are you sure want to delete this?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- modal import -->
        <div class="modal fade" id="importModalConfirmation">
            <form id="formImport" class="modal-dialog" enctype="multipart/form-data"
                role="document" method="post" action="{{ route('cms.yukk_co.dttot.import_preview') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 style="width:100%" class="modal-title" id="demoModalLabel">Import Excel File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input name="file" type="file" class="form-control" required>
                        <p style="margin-top:1rem">Download template <a href="{{ route('cms.yukk_co.dttot.download_template') }}">here</a></p>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary-outline" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
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
                                    <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => 1])) }}"
                                        class="page-link"><i class="icon-first"></i></a>
                                </li>
                            @endif
                            <!-- previous page if current page > 1 -->
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link"><i class="icon-arrow-left12"></i></a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i == $current_page)
                                    <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $i])) }}"
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
                                    <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                        class="page-link">{{ $current_page - 1 }}</a>
                                </li>
                                @endif
                                <li class="page-item active"><a href="#" class="page-link">{{ $current_page }}</a></li>
                                @if ($current_page + 1 < $last_page)
                                    <li class="page-item">
                                        <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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
                                    <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                        class="page-link"><i class="icon-arrow-right13"></i></a>
                                </li>
                            @endif
                            <!-- last page -->
                            @if ($current_page == $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-last"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $last_page])) }}"
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
                                        <a href="{{ route('cms.yukk_co.dttot.list', array_merge(request()->all(), ['page' => $i])) }}"
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
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
                // disable ordering on column action
                "columnDefs": [{
                    "orderable": false,
                    "targets": [5]
                }],
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

        function onImport() {
            $('#importModalConfirmation').modal('toggle')
        }

        function onActivationToggleConfirmation(event, id, username) {
            $('#modal-confirmation-toggle-username').text(username)
            $('#modal-confirmation-input').val(id)

            let action = $('#modal-confirmation-form').attr('data-attribute-action')
            $('#modal-confirmation-form').attr('action', action.replace(':id', id))

            $('#modal-confirmation-toggle').modal('toggle')
        }
    </script>
@endsection
