@extends('layouts.master')

@section("html_head")

    <script type="text/javascript" src="{{asset('assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
@endsection

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement Debt Input From Dispute")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_debt.list") }}" class="breadcrumb-item">@lang("cms.Settlement Debt List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Settlement Debt Input From Dispute")</span>
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
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Input via Dispute Form")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" href="#"><i class="icon-file-excel" title="@lang("cms.Download Template")"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.settlement_debt.input_dispute.summary") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <input type="file" name="disputes[]" class="file-input" multiple="multiple" data-fouc accept=".xlsx">
                    </div>

                </div>
            </form>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();

            // Modal template
            var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
                '  <div class="modal-content">\n' +
                '    <div class="modal-header align-items-center">\n' +
                '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
                '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
                '    </div>\n' +
                '    <div class="modal-body">\n' +
                '      <div class="floating-buttons btn-group"></div>\n' +
                '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>\n';

            $('.file-input').fileinput({
                browseLabel: 'Browse',
                browseClass: 'btn btn-light',
                removeClass: 'btn btn-light',
                uploadClass: 'btn btn-success',
                browseIcon: '<i class="icon-file-plus mr-2"></i>',
                uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
                removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>',
                    main1: "{preview}\n" +
                        "<div class='input-group {class}'>\n" +
                        "   <div class='input-group-prepend'>\n" +
                        "       {browse}\n" +
                        "   </div>\n" +
                        "   {caption}\n" +
                        "   <div class='input-group-append'>\n" +
                        "       {upload}\n" +
                        "       {remove}\n" +
                        "   </div>\n" +
                        "</div>",
                    modal: modalTemplate
                },
                initialCaption: "No file selected",
                allowedFileExtensions: ["xlsx"],
                previewZoomButtonClasses: {
                    toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
                    fullscreen: 'btn btn-light btn-icon btn-sm',
                    borderless: 'btn btn-light btn-icon btn-sm',
                    close: 'btn btn-light btn-icon btn-sm'
                },
                previewZoomButtonIcons: {
                    prev: $('html').attr('dir') == 'rtl' ? '<i class="icon-arrow-right32"></i>' : '<i class="icon-arrow-left32"></i>',
                    next: $('html').attr('dir') == 'rtl' ? '<i class="icon-arrow-left32"></i>' : '<i class="icon-arrow-right32"></i>',
                    toggleheader: '<i class="icon-menu-open"></i>',
                    fullscreen: '<i class="icon-screen-full"></i>',
                    borderless: '<i class="icon-alignment-unalign"></i>',
                    close: '<i class="icon-cross2 font-size-base"></i>'
                },
                fileActionSettings: {
                    zoomClass: '',
                    zoomIcon: '<i class="icon-zoomin3"></i>',
                    dragClass: 'p-2',
                    dragIcon: '<i class="icon-three-bars"></i>',
                    removeClass: '',
                    removeErrorClass: 'text-danger',
                    removeIcon: '<i class="icon-bin"></i>',
                    indicatorNew: '<i class="icon-file-plus text-success"></i>',
                    indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
                    indicatorError: '<i class="icon-cross2 text-danger"></i>',
                    indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
                }
            });
        });
    </script>
@endsection
