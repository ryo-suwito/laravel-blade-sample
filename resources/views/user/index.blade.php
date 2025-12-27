@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.User")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="breadcrumb-elements-item dropdown p-0">
                    <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center" data-toggle="dropdown" style="width: 100px; height: 40px">
                        <i class="icon-add mr-1"></i>@lang("cms.Add")
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">Add User</a>
                        <a href="{{ route('store.users.import_form') }}" class="dropdown-item">Bulk User</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.User")</span>
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
            <form action="#" method="get">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input class="form-control" placeholder="Search"></input>
                                </div>
                                <div class="col-sm-3">
                                    <button class="form-control dropdown-toggle" data-toggle="dropdown">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th></th>
                    <th>@lang("cms.Email")</th>
                    <th>@lang("cms.Full Name")</th>
                    <th>@lang("cms.Phone")</th>
                    <th>@lang("cms.Role")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <div>
                                <input type="checkbox" id="scales" name="scales"
                                       checked>
                            </div>
                        </td>
                        <td>kevinpratama175@gmail.com</td>
                        <td>kevinpratama</td>
                        <td>087881033627</td>
                        <td>Super Admin</td>
                        <td><button type="button" class="badge badge-success" data-toggle="modal" data-target="#demoModal">Active</button></td>
                        <td>
                            <div class="breadcrumb-elements-item dropdown justify-content-center">
                                <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center" data-toggle="dropdown" style="width: 100px; height: 40px">
                                    <i class="icon-gear text-center"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item">Detail</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item">Set Active/Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <input type="checkbox" id="scales" name="scales"
                                       checked>
                            </div>
                        </td>
                        <td>uhuy@gmail.com</td>
                        <td>uhhuuuyy</td>
                        <td>122222111111</td>
                        <td>Merchant</td>
                        <td><button type="button" class="badge badge-danger" data-toggle="modal" data-target="#demoModal">Inactive</button></td>
                        <td>
                            <div class="breadcrumb-elements-item dropdown justify-content-center">
                                <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center" data-toggle="dropdown" style="width: 100px; height: 40px">
                                    <i class="icon-gear text-center"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item">Detail</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item">Set Active/Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <input type="checkbox" id="scales" name="scales"
                                       checked>
                            </div>
                        </td>
                        <td>wkwkw@gmail.com</td>
                        <td>wekaweka</td>
                        <td>08123123212</td>
                        <td>Merchant</td>
                        <td><button type="button" class="badge badge-success" data-toggle="modal" data-target="#demoModal">Active</button></td>
                        <td>
                            <div class="breadcrumb-elements-item dropdown justify-content-center">
                                <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center" data-toggle="dropdown" style="width: 100px; height: 40px">
                                    <i class="icon-gear text-center"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item">Detail</a>
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item">Set Active/Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Hello</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Welcome
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

{{--        Untuk Next Prev Page--}}
{{--        <div class="card-footer">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <ul class="pagination pagination-flat justify-content-end">--}}
{{--                        @php($plus_minus_range = 3)--}}
{{--                        @if ($current_page == 1)--}}
{{--                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>--}}
{{--                        @else--}}
{{--                            <li class="page-item">--}}
{{--                                <a href="{{ route("cms.yukk_co.payment_channel.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                        @if ($current_page - $plus_minus_range > 1)--}}
{{--                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>--}}
{{--                        @endif--}}
{{--                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)--}}
{{--                            @if ($i == $current_page)--}}
{{--                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>--}}
{{--                            @else--}}
{{--                                <li class="page-item">--}}
{{--                                    <a href="{{ route("cms.yukk_co.payment_channel.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                        @endfor--}}
{{--                        @if ($current_page + $plus_minus_range < $last_page)--}}
{{--                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>--}}
{{--                        @endif--}}
{{--                        @if ($current_page == $last_page)--}}
{{--                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>--}}
{{--                        @else--}}
{{--                            <li class="page-item">--}}
{{--                                <a href="{{ route("cms.yukk_co.payment_channel.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">--}}
{{--                                    <i class="icon-arrow-right13"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
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
    </script>
@endsection
