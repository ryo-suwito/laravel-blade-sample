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
                <h4>DTTOT Preview List</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">DTTOT Preview List</span>
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
            <form action="{{ route('cms.yukk_co.dttot.import_preview_get') }}" method="get">
                <div class="row">
                    <div class="col-lg-8">
                    </div>
                    <div class="col-lg-4" style="display:flex;justify-content:flex-end">
                        <div class="form-group" style="margin-right:15px;min-width:200px">
                            <select name="filter_status" class="form-control select2" id="">
                                <option value="" selected> All Status </option>
                                <option @if (isset($filter['filter_status']) && $filter['filter_status'] === "ok") selected @endif value="ok">OK</option>
                                <option @if (isset($filter['filter_status']) && $filter['filter_status'] === "not_ok") selected @endif value="not_ok">NOT OK</option>
                            </select>
                        </div>
                        <div class="form-group text-right">
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-primary">Go</button>
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
                        <th>Validation</th>
                        <th>Description</th>
                        <th>Batch</th>
                        <th>Place of Birth</th>
                        <th>Date of Birth</th>
                        <th>Identities</th>
                        <th>Error Message</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (@$dttot_list ?? [] as $index => $user)
                        <tr {{ count($user['errors']) > 0 ? 'style=background:#666' : '' }} >
                            <td>
                                @if(isset($user['identities']) && isset($user['identities']['ALIAS']))
                                    @foreach ($user['identities']['ALIAS'] as $identity)
                                        {{ $identity }}
                                        <br/>
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $user['person']['type'] }}</td>
                            <td>{{ $user['person']['densus_code'] }}</td>
                            <td>@foreach (@$user['identities'] ?? [] as $index => $identity)
                                    @if ($index != 'ALIAS')
                                        {{ $index }} : {{ count($identity) }}  <br>
                                    @endif
                                @endforeach</td>
                            <td>{{ count($user['errors']) > 0 ? 'NOT OK' : 'OK' }} </td>
                            <td>{{ $user['person']['description'] }}</td>
                            <td>{{ $user['person']['batch'] }}</td>
                            <td>{{ $user['person']['place_of_birth'] }}</td>
                            <td>{{ $user['person']['date_of_birth'] }}</td>
                            <td>
                                @foreach (@$user['identities'] ?? [] as $index => $identity)
                                    @if ($index != 'ALIAS')
                                        @foreach ($identity as $id)
                                            {{ $index }} : {{ $id }} <br>
                                        @endforeach
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <ul>
                                @foreach ($user['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
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
                        Are you sure to delete this?
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
                role="document" method="post" action="{{ route('cms.yukk_co.dttot.import') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 style="width:100%" class="modal-title" id="demoModalLabel">Import Excel File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        <!-- first page -->
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-first"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.yukk_co.dttot.import_preview_get', array_merge(request()->all(), ['page' => 1])) }}"
                                    class="page-link"><i class="icon-first"></i></a>
                            </li>
                        @endif
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.yukk_co.dttot.import_preview_get', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                    <a href="{{ route('cms.yukk_co.dttot.import_preview_get', array_merge(request()->all(), ['page' => $i])) }}"
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
                                <a href="{{ route('cms.yukk_co.dttot.import_preview_get', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                    class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif
                        <!-- last page -->
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-last"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.yukk_co.dttot.import_preview_get', array_merge(request()->all(), ['page' => $last_page])) }}"
                                    class="page-link"><i class="icon-last"></i></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p style="width:100%; text-align:left"> {{ $successCount }} out of {{ $allCount }} row(s) will be saved </p>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="onImportSave()">Save</button>
                        <a href="{{ route('cms.yukk_co.dttot.list') }}" class="btn btn-primary-outline"> Cancel</a>
                    </div>
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
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });

        function onImportSave() {
            // redirect to save
            window.location.href = "{{ route('cms.yukk_co.dttot.import') }}"
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
