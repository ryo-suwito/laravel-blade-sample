@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Change Target")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="#" class="breadcrumb-item">@lang("cms.My Profile")</a>
                    <span class="breadcrumb-item active">@lang("cms.Change Target")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>

            {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <!-- Basic datatable -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.User Role")</h5>
        </div>

        <table class="table datatable-basic">
            <thead>
            <tr>
                <th>@lang("cms.Target Type")</th>
                <th>@lang("cms.Target Name")</th>
                <th>@lang("cms.Role")</th>
                <th class="text-center">@lang("cms.Actions")</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($user_role_list as $user_role)
                <tr>
                    <td>
                        <span class="badge badge-primary">{{ $user_role->target_type }}</span>
                    </td>
                    <td>{{ $user_role->target_name }}</td>
                    <td>{{ $user_role->role->name }}</td>
                    <td class="text-center">
                        @if ($user_role->id == \App\Helpers\S::getUserRole()->id)
                            <button type="button" class="btn btn-primary" disabled="">@lang("cms.Current")</button>
                        @else
                            <button type="button" class="btn btn-success">@lang("cms.Select")</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /basic datatable -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".datatable-basic").DataTable();
        });
    </script>
@endsection