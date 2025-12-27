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
                <h4>@lang('cms.User')</h4>
            </div>

            @hasaccess('STORE.USERS_CREATE')
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="breadcrumb-elements-item dropdown p-0">
                    <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center"
                        data-toggle="dropdown" style="width: 100px; height: 40px">
                        <i class="icon-add mr-1"></i>@lang('cms.Add')
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('cms.store.users.create') }}" class="dropdown-item">Add User</a>
                        <a href="{{ route('store.users.import_form') }}" class="dropdown-item">Bulk User</a>
                    </div>
                </div>
            </div>
            @endhasaccess
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.User')</span>
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
            <form action="{{ route('cms.store.users.list') }}" method="get">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang('cms.Search')</label>
                            <input name="search" class="form-control" placeholder="Search" value="{{ @$filter->search }}" />
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="active" class="form-control" id="">
                                <option value=""> -- Select Status -- </option>
                                <option {{ @$filter->active === "1" ? 'selected' : '' }} value="1">Active</option>
                                <option {{ @$filter->active === "0" ? 'selected' : '' }} value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Role</label>
                            <select name="role_id" class="form-control select2" id="">
                                <option value="" selected> -- Select Role -- </option>
                                @foreach (@$roles['data'] ?? [] as $role)
                                    <option {{ @$filter->role_id == $role['id'] ? 'selected' : '' }}
                                        value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i>
                                @lang('cms.Search')</button>
                        </div>
                    </div>

                    <div class="col-lg-1"></div>

                    <div class="col-lg-1 justify-content-end">
                        <div class="flex flex-row">
                            <label>@lang("cms.Per page")&nbsp;</label>
                            <div class="form-group">
                                <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option @if($users['per_page'] == 10) selected @endif>10</option>
                                    <option @if($users['per_page'] == 25) selected @endif>25</option>
                                    <option @if($users['per_page'] == 50) selected @endif>50</option>
                                    <option @if($users['per_page'] == 100) selected @endif>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.Email')</th>
                        <th>@lang('cms.Full Name')</th>
                        <th>@lang('cms.Phone')</th>
                        <th>@lang('cms.Role')</th>
                        <th>@lang('cms.Status')</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (@$users['data'] ?? [] as $index => $user)
                        <tr>
                            <td>
                                <a class="text-white" href="mailto:{{ $user['email'] }}">
                                    {{ $user['email'] }}
                                </a>
                            </td>
                            <td>{{ $user['full_name'] }}</td>
                            <td>
                                <a class="text-white" href="tel:{{ $user['phone'] }}">
                                    {{ $user['phone'] }}
                                </a>
                            </td>
                            <td>
                                @if (empty($user['roles']))
                                    -
                                @endif
                                @foreach (collect($user['roles'])->unique() as $role)
                                    <span class="badge badge-info mb-1">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $user['active'] ? 'badge-success' : 'badge-danger' }}">
                                    {{ $user['active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <x-table.action-dropdown>
                                    <a href="{{ route('cms.store.users.show', $user['id']) }}"
                                        class="dropdown-item">Detail</a>
                                    @hasaccess('STORE.USERS_EDIT')
                                    <a href="{{ route('cms.store.users.edit', $user['id']) }}"
                                        class="dropdown-item">Edit</a>
                                    @endhasaccess
                                    @hasaccess('STORE.USERS_EDIT')
                                    <a href="#"
                                        onclick="onActivationToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['username'] }}')"
                                        class="dropdown-item">Set Active/Inactive</a>
                                    @endhasaccess
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
                data-attribute-action="{{ route('cms.store.users.toggle.active', ':id') }}" class="modal-dialog"
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
                        Are you sure to activate / deactivate <span id="modal-confirmation-toggle-username"></span>?
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
                <div class="col-lg-6">
                    @if ($users['total'] > 0)
                        <p>
                            Showing {{ $users['from'] }} to {{ $users['to'] }} of {{ $users['total'] }} entries
                        </p>
                    @endif
                </div>
                <div class="col-lg-6">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.store.users.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                    <a href="{{ route('cms.store.users.list', array_merge(request()->all(), ['page' => $i])) }}"
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
                                <a href="{{ route('cms.store.users.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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

        function onActivationToggleConfirmation(event, id, username) {
            $('#modal-confirmation-toggle-username').text(username)
            $('#modal-confirmation-input').val(id)

            let action = $('#modal-confirmation-form').attr('data-attribute-action')
            $('#modal-confirmation-form').attr('action', action.replace(':id', id))

            $('#modal-confirmation-toggle').modal('toggle')
        }
    </script>
@endsection
