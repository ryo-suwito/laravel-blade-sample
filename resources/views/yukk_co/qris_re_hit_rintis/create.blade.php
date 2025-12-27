@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.QRIS ReHit RINTIS")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.qris_re_hit_rintis.list") }}" class="breadcrumb-item">@lang("cms.QRIS ReHit RINTIS")</a>
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.QRIS ReHit RINTIS")</h5>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route("cms.yukk_co.qris_re_hit_rintis.store") }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.JSON")</label>
                                    <div class="col-lg-10">
                                        <textarea type="text" class="form-control" id="json" name="json" rows="10">{{ old("json") }}</textarea>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary" id="btn-submit">@lang("cms.Submit")</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
            $("#btn-submit").click(function(e) {
                let jsonString = $("#json").val();
                let jsonObject = null;
                try {
                    jsonObject = JSON.parse(jsonString);
                } catch (ex) {
                    alert("@lang("cms.qris_re_hit_json_format_error")");
                    e.preventDefault();
                    return;
                }

                /*console.log(jsonObject);

                if (jsonObject.processingCode === undefined) {
                    alert("@lang("cms.qris_re_hit_processing_code_not_found")");
                    e.preventDefault();
                    return;
                }

                console.log(jsonObject.transactionData.processingCode);
                console.log(! jsonObject.transactionData.processingCode.startsWith("2"));

                if (! jsonObject.processingCode.startsWith("2")) {
                    if (confirm("@lang("cms.qris_re_hit_processing_code_not_starts_with_2")")) {

                    } else {
                        e.preventDefault();
                        return;
                    }
                }*/

                setInterval(function() {
                    // Need interval because if disable first, then the button is not included on the form
                    $("#btn-submit").attr("disabled", "disabled");
                }, 50);
            });
        });
    </script>
@endsection