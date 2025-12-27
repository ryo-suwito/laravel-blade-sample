@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Client Credential")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Client Credential")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mt-4">
        <form action="{{ route('client_credential.index') }}" method="GET">
            <div class="card-header form-group">
                <div class="row ml-2 justify-content-between">
                    <div class="row">
                        <div class="form-group mr-2">
                            <label for="field">@lang('cms.Field')</label>
                            <select id="field" name="field" class="form-control select2">
                                <option selected value="">@lang('cms.Select One')</option>
                                <option value="name" @if($field == 'name') selected @endif>@lang('cms.Name')</option>
                                <option value="client_id" @if($field == 'client_id') selected @endif>@lang('cms.Client ID')</option>
                                <option value="client_secret" @if($field == 'client_secret') selected @endif>@lang('cms.Client Secret')</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label for="search">@lang('cms.Keyword')</label>
                            <input type="text" id="keyword" name="keyword" class="form-control" value="{{ $keyword }}" placeholder="@lang('cms.Keyword')" onchange='if(this.value != 0) { this.form.submit(); }'>
                        </div>
                        <div class="form-group mr-2">
                            <label for="status">@lang('cms.Status')</label>
                            <select id="status" name="status" class="form-control select2" onchange='if(this.value != null) { this.form.submit(); }'>
                                <option selected value="">@lang('cms.Select One')</option>
                                <option value="0" @if($status == '0') selected @endif>@lang('cms.Active')</option>
                                <option value="1" @if($status == '1') selected @endif>@lang('cms.Inactive')</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label for="type">@lang('cms.Entity Type')</label>
                            <select id="type" name="type" class="form-control select2" onchange='if(this.value != null) { this.form.submit(); }'>
                                <option selected value="">@lang('cms.Select One')</option>
                                <option value="BENEFICIARY" @if($entity == 'BENEFICIARY') selected @endif>@lang('cms.Beneficiary')</option>
                                <option value="PARTNER" @if($entity == 'PARTNER') selected @endif>@lang('cms.Partner')</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mr-2">
                        <a href="{{ route('client_credential.create') }}">
                            <button class="btn btn-primary form-control" type="button"><i class="fas fa-plus mr-1"></i>@lang("cms.Create")</button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('cms.Entity Type')</th>
                            <th class="text-center">@lang('cms.Entity Name')</th>
                            <th class="text-center">@lang('cms.Client ID')</th>
                            <th class="text-center">@lang('cms.Client Secret')</th>
                            <th class="text-center">@lang('cms.Permission')</th>
                            <th class="text-center">@lang('cms.Status')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($client_list as $client)
                        <tr>
                            <td class="text-center">{{ @$client['user_type'] }}</td>
                            <td class="text-center">{{ @$client['name'] }}</td>
                            <td class="text-center">{{ @$client['id'] }}</td>
                            <td class="text-center">{{ @$client['secret'] }}</td>
                            <td class="text-center">
                                @if($client['owner'])
                                    @php($permissions = collect($client['owner']['permissions'])->pluck('name'))
                                    @php($permission_first_arr = collect([]))
                                    @foreach($permissions as $permission)
                                        @php($test = explode('.', $permission))
                                        @php($permission_first_arr[] = $test[0])
                                    @endforeach
                                    @foreach($permission_first_arr->unique() as $permission_first)
                                        <span class="badge badge-primary">
                                            {{ str_replace('_', ' & ',strtoupper($permission_first)) }}
                                        </span>
                                        <br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ @$client['revoked'] ? 'badge-danger' : 'badge-success' }}">
                                    {{ @$client['revoked'] ? 'Inactive' : 'Active' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('client_credential.detail', @$client['id']) }}" class="dropdown-item"><i class="icon-info22"></i>
                                                @lang("cms.Detail")
                                            </a>
                                            <a href="{{ route('client_credential.edit', @$client['id']) }}" class="dropdown-item"><i class="icon-pencil7"></i>
                                                @lang("cms.Edit")
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

            <div class="card-footer">
                <div class="row">
                    <div class="form-group ml-2 mt-1">
                        {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                    </div>
                    <div class="col-1">
                        <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                            <option @if($per_page == 10) selected @endif>10</option>
                            <option @if($per_page == 25) selected @endif>25</option>
                            <option @if($per_page == 50) selected @endif>50</option>
                            <option @if($per_page == 100) selected @endif>100</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-flat justify-content-end">
                            @php($plus_minus_range = 3)
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('client_credential.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                        <a href="{{ route('client_credential.index', array_merge(request()->all(), ['page' => $i])) }}"
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
                                    <a href="{{ route('client_credential.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                       class="page-link">
                                        <i class="icon-arrow-right13"></i>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </form>
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
        });
    </script>
@endsection
