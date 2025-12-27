@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Dashboard")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a class="breadcrumb-item">@lang("cms.Disbursement")</a>
                    <span class="breadcrumb-item active">@lang("cms.Dashboard")</span>
                </div>

            </div>

        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Dashboard")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-4 alert mr-2" style="border: 1px black solid;">
                    <h4>@lang("cms.Total Settlement"): <br/> {{  @\App\Helpers\H::formatNumber($disbursement_response->total_all_settlement,2) }} </h4>
                    <h4>@lang("cms.Date Range"): <br/>{{ @\App\Helpers\H::formatDateTimeWithoutTime($start_time) }} - {{ @\App\Helpers\H::formatDateTimeWithoutTime($end_time) }} </h4>
                </div>
                <div class="col-sm-4 alert" style="border: 1px black solid;">
                    <h4>Total yang belum di Disbursement: <br/>  {{  @\App\Helpers\H::formatNumber($disbursement_response->total_not_complete_settlement,2) }} </h4>
                </div>
            </div>

            <hr>
            <form action="{{ route("cms.beneficiary.disbursement.list") }}" method="get">
                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                        </div>
                    </div>      
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>@lang("cms.Type")</label>
                            <select class="form-control" name="type" id="type">
                                <option value="ALL" SELECTED>@lang("cms.All")</option>
                                <option value="QRIS" @if($old_type == "QRIS")selected @endif>@lang("cms.QRIS")</option>
                                <option value="PG" @if($old_type == "PG")selected @endif>@lang("cms.PG")</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                        </div>
                    </div>
                </div>
            </form>

            <h5 class="mt-2"><u>@lang("cms.Table Settlement QRIS")</u></h5>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.No")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Merchant Portion")</th>
                    <th>@lang("cms.Settlement Date")</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($disbursement_response->settlement_master_list as $settlement_master)
                    <tr>
                        <td>{{ $index_qris++ }}</td>
                        <td>{{ @$settlement_master->ref_code }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($settlement_master->total_merchant_portion,2) }}</td>
                        <td>{{ @$settlement_master->settlement_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h5 class="mt-2"><u>@lang("cms.Table Settlement PG")</u></h5>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.No")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Merchant Portion")</th>
                    <th>@lang("cms.Settlement Date")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($disbursement_response->settlement_pg_master_list as $settlement_master)
                    <tr>
                        <td>{{ $index_pg++ }}</td>
                        <td>{{ @$settlement_master->ref_code }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($settlement_master->total_merchant_portion,2) }}</td>
                        <td>{{ @$settlement_master->settlement_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
