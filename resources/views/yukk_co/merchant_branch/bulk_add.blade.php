@extends('layouts.master')

@section('html_head')
    <style type="text/css">
        #myMap {
            margin: 10px;
            width: 100%;
            height: 400px;
            padding: 10px;
        }

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 0px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 330px;
            margin-top: 13px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }
    </style>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">@lang('cms.Add Branches')</h5>
            <hr>
        </div>
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.merchant_branch.bulk.preview') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Import Merchant Branches")</label>
                                <div class="col-9">
                                   <input type="file" name="merchant_branch_list" id="merchant_branch_list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-5">
                    <button class="btn btn-primary btn-block col-3" type="submit">@lang("cms.Submit")</button>
                </div>
            </form>
        </div>
    </div>
@endsection

