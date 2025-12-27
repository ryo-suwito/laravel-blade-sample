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
                <h4>@lang("cms.User Detail")</h4>
            </div>

            <div class="my-sm-auto ml-auto">
                @hasaccess('STORE.USERS_EDIT')
                <button class="btn {{ $user['active'] == '1' ? 'btn-success' : 'btn-danger' }}" data-toggle="modal" data-target="#modal-toggle-active">
                    {{ $user['active'] == '1' ? 'Active' : 'Inactive' }}
                </button>
                @endhasaccess
            </div>

            {{--@hasaccess('STORE.USERS_RESET_PASSWORD')
            <div class="my-sm-auto ml-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#resetPass">@lang('cms.Reset Password')</button>
            </div>
            @endhasaccess--}}

            @hasaccess('STORE.USERS_EDIT')
            <div class="my-sm-auto ml-2">
                <a href="{{ route('cms.store.users.edit', $user['id']) }}" class="btn btn-primary">@lang("cms.Edit")</a>
            </div>
            @endhasaccess
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.store.users.list") }}" class="breadcrumb-item">@lang("cms.User")</a>
                    <a href="#" class="breadcrumb-item active">@lang("cms.Detail")</a>
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
            <div class="row">
                <div class="col-sm-6">
                    {{--<div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Username")</label>
                        <div class="col-lg-6">
                            <input form="confirmationModal" id="username" name="username" type="text" class="form-control" value="{{ $user['username'] }}" readonly>
                        </div>
                    </div>--}}

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-6">
                            <input form="confirmationModal" id="email" name="email" type="text" class="form-control" value="{{ $user['email'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Full Name")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ $user['full_name'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Phone")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ $user['phone'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Gender")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ $user['gender'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Description")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ $user['description'] }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Role Name(s)")</label>
                        <div class="col-lg-6 my-auto">
                            @foreach (collect($user['roles'])->unique('name') as $role)
                                <span class="badge badge-primary">{{ $role['name'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-6 my-auto">
                            @if($user['active'])
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">@lang("cms.Created At/By")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ date('d-F-Y H:i:s', strtotime($user['created_at'])) }} / {{ @$user['created_by']['username'] ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">@lang("cms.Updated At/By")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ date('d-F-Y H:i:s', strtotime($user['updated_at'])) }} / {{ @$user['updated_by']['username'] ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($partners)
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-2">
                    <label class="col-form-label">@lang("cms.List Partner")</label>
                </div>
            </div>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th style="width: 20%">@lang("cms.ID")</th>
                        <th>@lang("cms.Name")</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($partners as $index => $partner)
                    <tr>
                        <td>{{ @$partner['id'] }}</td>
                        <td>{{ @$partner['name'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($merchantBranches)
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-2">
                    <label class="col-form-label">@lang("cms.List Merchant")</label>
                </div>
            </div>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th style="width: 20%">@lang("cms.ID")</th>
                    <th>@lang("cms.Name")</th>
                </tr>
                </thead>
                <tbody>
                    @foreach(@$merchantBranches ?? [] as $index => $merchantBranch)
                    <tr>
                        <td>{{ @$merchantBranch['id'] }}</td>
                        <td>{{ @$merchantBranch['name'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($customers)
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-2">
                    <label class="col-form-label">@lang("cms.List Beneficiary")</label>
                </div>
            </div>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th style="width: 20%">@lang("cms.ID")</th>
                    <th>@lang("cms.Name")</th>
                    <th>@lang("cms.Phone Number")</th>
                </tr>
                </thead>

                <tbody>
                    @foreach($customers ?? [] as $customer)
                    <tr>
                        <td>{{ @$customer['id'] }}</td>
                        <td>{{ @$customer['name'] }}</td>
                        <td>{{ @$customer['contact_no'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{--<div class="modal fade" id="resetPass" tabindex="-1" role="dialog" aria-labelledby="resetPassword" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('cms.store.users.reset', $user['id']) }}" method="POST" id="resetPass">
                        @csrf
                        <div class="modal-header">
                            <div class="input-group row justify-content-center w-100">
                                <h5 class="modal-title w-100">Authentication</h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mr-3 mb-1">Insert Your Password</div>
                            <div class="input-group mb-3 row justify-content-center w-100">
                                <input id="password" name="password" type="password" value="" class="input form-control col-sm-6 w-100"/>
                            </div>
                        </div>
                        <div>
                            <p class="text-center"><em>By clicking Reset,you will reset password for this account {{$user['username']}} to system default value</em></p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary">Change</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>--}}

        <div class="modal fade" id="resetStatus" tabindex="-1" role="dialog" aria-labelledby="resetStatus" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="#" method="POST" id="resetPass">
                        @csrf
                        <div class="modal-header">
                            <div class="input-group row justify-content-center w-100">
                                <h5 class="modal-title w-100">Authentication</h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mr-3 mb-1">Insert Your Password</div>
                            <div class="input-group mb-3 row justify-content-center w-100">
                                <input id="password" name="password" type="password" value="" class="input form-control col-sm-6 w-100"/>
                            </div>
                        </div>
                        <div>
                            <p class="text-center"><em>By clicking Reset,you will reset password for this account to default value: 123456</em></p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary">Change</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-toggle-active" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form" class="modal-dialog"
                role="document" method="post" action="{{ route('cms.store.users.toggle.active', $user['id']) }}">
                @csrf
                <input id="modal-confirmation-input" name="id" type="hidden" value="{{ $user['id'] }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure to {{ empty($user['active']) ? 'activate' : 'deactivate' }} {{ $user['username'] }}?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
