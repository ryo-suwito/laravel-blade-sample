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
                    <a href="{{ route("cms.yukk_co.partner_has_merchant_branch.list", @$partner->id) }}" class="breadcrumb-item">@lang("cms.Merchant Branch List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
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
                    <h5 class="card-title">{{ @$partner->name }}</h5>
                </div>

                <div class="card-body">
                    <form id="form-filter" action="{{ route("cms.yukk_co.partner_has_merchant_branch.create", $partner->id) }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="form-control-plaintext">@lang("cms.Merchant Branch Name")</span>
                                <input name="merchant_branch_name" type="text" class="form-control" placeholder="@lang("cms.Branch Name")" value="{{ $filter_branch_name }}">
                            </div>
                            <div class="col-md-3">
                                <span class="form-control-plaintext">@lang("cms.Company Status Legal")</span>
                                <select name="company_status_legal" type="text" class="form-control">
                                    <option value="ALL">@lang("cms.All")</option>
                                    <option value="APPROVED" @if($filter_company_status_legal == "APPROVED") selected @endif>@lang("cms.Approved")</option>
                                    <option value="NEW" @if($filter_company_status_legal == "NEW") selected @endif>@lang("cms.New")</option>
                                    <option value="IN_REVIEW" @if($filter_company_status_legal == "IN_REVIEW") selected @endif>@lang("cms.In Review")</option>
                                    <option value="REJECTED" @if($filter_company_status_legal == "REJECTED") selected @endif>@lang("cms.Rejected")</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <span class="form-control-plaintext">@lang("cms.Merchant Branch Status")</span>
                                <select name="active" type="text" class="form-control">
                                    <option value="-1" @if($filter_active == -1) selected @endif>@lang("cms.All")</option>
                                    <option value="1" @if($filter_active == 1) selected @endif>@lang("cms.Active")</option>
                                    <option value="0" @if($filter_active == 0) selected @endif>@lang("cms.Inactive")</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <span class="form-control-plaintext">&nbsp;</span>
                                <button class="btn btn-block btn-primary" type="submit">@lang("cms.Submit")</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th>@lang("cms.ID")</th>
                                    <th>@lang("cms.Name")</th>
                                    <th>@lang("cms.Company Status")</th>
                                    <th>@lang("cms.Status")</th>
                                    <th>@lang("cms.Actions")</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach(@$merchant_branch_list as $index => $merchant_branch)
                                    <tr>
                                        <td>{{ @$merchant_branch->id }}</td>
                                        <td>{{ @$merchant_branch->name }}</td>
                                        <td>{{ @$merchant_branch->status_legal }}</td>
                                        <td>
                                            {{ @$merchant_branch->active ? __("cms.Active") : __("cms.Inactive") }}
                                            @if (@$merchant_branch->status_legal != "APPROVED")
                                                <br>
                                                @lang("cms.Company is not yet APPROVED by Legal")
                                            @endif
                                        </td>
                                        <td>
                                            @if (@$merchant_branch->status_legal == "APPROVED")
                                                <form action="{{ route("cms.yukk_co.partner_has_merchant_branch.store", @$partner->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="partner_id" value="{{ @$partner->id }}">
                                                    <input type="hidden" name="merchant_branch_ids[]" value="{{ @$merchant_branch->id }}">
                                                    <button class="btn btn-primary add-merchant-branch" type="submit">@lang("cms.Add")</button>
                                                </form>
                                            @else
                                                <button class="btn btn-light" type="button" disabled title="@lang("cms.Company is not yet APPROVED by Legal")">@lang("cms.Add")</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="alert alert-danger">
                                @lang("cms.Please Submit your work before continue")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="pagination pagination-flat justify-content-end">
                                @php($plus_minus_range = 3)
                                @if ($current_page == 1)
                                    <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route("cms.yukk_co.partner_has_merchant_branch.create", array_merge([@$partner->id], array_merge(request()->all(), ["page" => $current_page-1]))) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                                    </li>
                                @endif
                                @if ($current_page - $plus_minus_range > 1)
                                    <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                                @endif
                                @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                                    @if ($i == $current_page)
                                        <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                                    @else
                                        <li class="page-item">
                                            <a href="{{ route("cms.yukk_co.partner_has_merchant_branch.create", array_merge([@$partner->id], array_merge(request()->all(), ["page" => $i]))) }}" class="page-link">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                                @if ($current_page + $plus_minus_range < $last_page)
                                    <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                                @endif
                                @if ($current_page == $last_page)
                                    <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route("cms.yukk_co.partner_has_merchant_branch.create", array_merge([@$partner->id], array_merge(request()->all(), ["page" => $current_page+1]))) }}" class="page-link">
                                            <i class="icon-arrow-right13"></i>
                                        </a>
                                    </li>
                                @endif

                            </ul>
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

            $(".add-merchant-branch").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
