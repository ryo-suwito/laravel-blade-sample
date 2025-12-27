@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Skip Process Day")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.skip_process_day.list") }}" class="breadcrumb-item">@lang("cms.Skip Process Day")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
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
            <h5 class="card-title">@lang("cms.Skip Process Day")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($skip_process_day->date, "d-M-Y") }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Title")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$skip_process_day->title }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$skip_process_day->description }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($skip_process_day->created_at) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Updated At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($skip_process_day->updated_at) }}">
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