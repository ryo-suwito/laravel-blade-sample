@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner Has Merchant Branch")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.partner.list") }}" class="breadcrumb-item">@lang("cms.Partner")</a>
                    <a href="{{ route("cms.yukk_co.partner.item", @$partner->id) }}" class="breadcrumb-item">{{ @$partner->name }}</a>
                    <span class="breadcrumb-item active">@lang("cms.Merchant Branch List")</span>
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
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Partner has MerchantBranches")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <h3>{{ @$partner->name }}</h3>
                            <h4>@lang("cms.Partner Has Merchant Branch")</h4>
                        </div>

                        <div class="col-sm-4">
                            <form id="form-filter" action="{{ route("cms.yukk_co.partner_has_merchant_branch.list", $partner->id) }}" method="get">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input name="merchant_branch_name" type="text" class="form-control form-control-sm" placeholder="@lang("cms.Branch Name")" value="{{ $filter_branch_name }}">
                                            <div class="form-control-feedback form-control-feedback-sm">
                                                <i class="icon-search4"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="active" type="text" class="form-control form-control-sm" onchange="document.getElementById('form-filter').submit();">
                                            <option value="-1" @if($filter_active == -1) selected @endif>@lang("cms.All")</option>
                                            <option value="1" @if($filter_active == 1) selected @endif>@lang("cms.Active")</option>
                                            <option value="0" @if($filter_active == 0) selected @endif>@lang("cms.Inactive")</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th>@lang("cms.ID")</th>
                                    <th>@lang("cms.Name")</th>
                                    <th>@lang("cms.MID")</th>
                                    <th>@lang("cms.Status")</th>
                                    <th>@lang("cms.Actions")</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach(@$partner->partner_has_merchant_branches as $index => $partner_has_merchant_branch)
                                    <tr>
                                        <td>{{ @$partner_has_merchant_branch->merchant_branch->id }}</td>
                                        <td>{{ @$partner_has_merchant_branch->merchant_branch->name }}</td>
                                        {{-- Don't add Enter or break this below td, it will cause white space then copied. --}}
                                        <td><span style="word-break:break-all;">{{ @$partner_has_merchant_branch->mid }}</span></td>
                                        <td>{{ @$partner_has_merchant_branch->merchant_branch->active ? __("cms.Active") : __("cms.Inactive") }}</td>
                                        <td>
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("MERCHANT_BRANCHES_VIEW", "AND"))
                                                            <a href="{{ route("cms.yukk_co.merchant_branch_pg.item", @$partner_has_merchant_branch->merchant_branch_id) }}?from_partner_id={{ $partner->id }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                        @endif
                                                        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("MERCHANT_BRANCHES_ITEM_PC_UPDATE", "AND"))
                                                            <a href="{{ route("cms.yukk_co.merchant_branch_pg.edit", @$partner_has_merchant_branch->merchant_branch_id) }}?from_partner_id={{ $partner->id }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
                "ordering": false,
                "info": false,
                "searching": false,
            });
        });
    </script>
@endsection