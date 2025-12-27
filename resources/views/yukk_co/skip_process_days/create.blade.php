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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Skip Process Day")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.skip_process_day.store") }}" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label for="date" class="col-lg-4 col-form-label @if ($errors->has("date")) text-danger @endif">@lang("cms.Date")</label>
                            <div class="col-lg-8">
                                @csrf
                                <input type="text" name="date" id="date" class="form-control @if ($errors->has("date")) is-invalid @endif" value="{{ old("date") }}" required>
                                @if ($errors->has("date"))
                                    <span class="invalid-feedback">{{ $errors->first("date") }}</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="title" class="col-lg-4 col-form-label @if ($errors->has("title")) text-danger @endif">@lang("cms.Title")</label>
                            <div class="col-lg-8">
                                <input type="text" name="title" id="title" class="form-control @if ($errors->has("title")) is-invalid @endif" value="{{ old("title") }}" required>
                                @if ($errors->has("title"))
                                    <span class="invalid-feedback">{{ $errors->first("title") }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="description" class="col-lg-4 col-form-label @if ($errors->has("description")) text-danger @endif">@lang("cms.Description")</label>
                            <div class="col-lg-8">
                                <textarea type="text" name="description" id="description" class="form-control @if ($errors->has("description")) is-invalid @endif" required>{{ old("description") }}</textarea>
                                @if ($errors->has("description"))
                                    <span class="invalid-feedback">{{ $errors->first("description") }}</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-lg-12 text-center">
                                <button class="btn btn-primary" type="submit">@lang("cms.Submit")</button>
                            </div>
                        </div>
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


            $("#date").daterangepicker({
                singleDatePicker: true,
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

        });
    </script>
@endsection