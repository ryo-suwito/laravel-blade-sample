@extends('layouts.master')

@section('header')
<!-- Page header -->
<style>
    .bootstrap-select .no-results {
        background: #2c2d33 !important;
    }

    .bootstrap-select .dropdown-menu.inner {
        max-height: 300px;
    }

    div.dropdown-menu.show {
        max-width: 240px !important;
        max-height: 364px !important;
    }

    a.dropdown-item.selected {
        color: #65bbf9 !important;
    }

    li > a > span.text {
        white-space: break-spaces;
        margin-right: 10px !important;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        right: 15px !important;
        top: 25% !important;
    }
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
            <h4>Providers</h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <span class="breadcrumb-item active">Provider</span>
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
        <div class="card-header d-sm-flex" style="margin-bottom: -35px;">
            <div>
                <h4>Providers</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable mb-4">
                    <thead>
                    <tr>
                        <th class="w-50">@lang("cms.Provider Name")</th>
                        <th class="w-50">@lang("cms.Code")</th>
                        <th class="w-auto">@lang("cms.Actions")</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $p)
                            <tr>
                                <td>{{ $p["name"] }}</td>
                                <td>{{ $p["code"] ?? "-" }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('money_transfer.providers.edit', ['id' => $p['id']]) }}" class="dropdown-item"><i class="icon-cog"></i> @lang("cms.Setting")</a>
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

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">


                    </ul>
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
                "ordering": true,
                "info": false,
                "searching": true,
            });

            $('.partner').on("change", function() {
                if($(this).is(':checked')) {
                    $('#selectedPartners').append('<input type="hidden" id="partner-'+$(this).data('id')+'" name="selectedPartners[]" value="'+$(this).data('id')+'">')
                } else {
                    $('#partner-'+$(this).data('id')).remove();
                }
            })

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
