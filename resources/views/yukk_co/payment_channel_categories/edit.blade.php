@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Payment Channel Category")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.payment_channel_category.list") }}" class="breadcrumb-item">@lang("cms.Payment Channel Category")</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
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
    <form method="post" action="{{ route("cms.yukk_co.payment_channel_category.update", @$payment_channel_category->id) }}">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Payment Channel Category")</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.ID")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$payment_channel_category->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="name">@lang("cms.Name")</label>
                            <div class="col-lg-8">
                                @csrf
                                <input type="text" class="form-control" name="name" id="name" value="{{ old("name", @$payment_channel_category->name) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="description">@lang("cms.Description")</label>
                            <div class="col-lg-8">
                                <textarea type="text" class="form-control" name="description" id="description">{{ old("description", @$payment_channel_category->description) }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-block">@lang("cms.Submit")</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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