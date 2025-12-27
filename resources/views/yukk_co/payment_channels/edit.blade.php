@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Payment Channel")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.payment_channel.list") }}" class="breadcrumb-item">@lang("cms.Payment Channel")</a>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Payment Channel")</h5>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route("cms.yukk_co.payment_channel.update", $payment_channel->id) }}">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.ID")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$payment_channel->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="category_id">@lang("cms.Category")</label>
                            <div class="col-lg-8">
                                @csrf
                                <select class="form-control select2" name="category_id" id="category_id">
                                    @foreach ($payment_channel_categories as $payment_channel_category)
                                        <option value="{{ $payment_channel_category->id }}" @if($payment_channel_category->id == old("category_id", @$payment_channel->category_id)) selected @endif>{{ $payment_channel_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="code">@lang("cms.Code")</label>
                            <div class="col-lg-8">
                                <input type="text" name="code" id="code" class="form-control" value="{{ old("code", @$payment_channel->code) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="name">@lang("cms.Name")</label>
                            <div class="col-lg-8">
                                <input type="text" name="name" id="name" class="form-control" value="{{ old("name", @$payment_channel->name) }}">
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">@lang("cms.Submit")</button>
                    </div>
                </div>
            </form>
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