@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Activity Logs Detail")</h4>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("activity.log.index") }}" class="breadcrumb-item">@lang("cms.Activity Logs")</a>
                    <span class="breadcrumb-item active">@lang("cms.Activity Logs Detail")</span>
                </div>
            </div>

        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 20%">@lang("cms.Field")</th>
                        <th class="text-center" style="width: 40%">@lang("cms.Old Value")</th>
                        <th class="text-center" style="width: 40%">@lang("cms.New Value")</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $index => $log)
                        <tr>
                            <th class="text-center">{{ @$index }}</th>
                            @if(@$log[1]->url)
                                <th class="text-center" style="word-break: break-all">{{ @$log[1]->url }}</th>
                            @else
                                <th class="text-center">{{ @$log[1] }}</th>
                            @endif
                            @if(@$log[0]->url)
                                <th class="text-center" style="word-break: break-all">{{ @$log[0]->url }}</th>
                            @else
                                <th class="text-center">{{ @$log[0] }}</th>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('style')
    <style>
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
