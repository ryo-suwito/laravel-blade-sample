@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Client Credential Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("client_credential.index") }}" class="breadcrumb-item">@lang("cms.Client Credential")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">{{ @$client->name }} &#9;&#9; | &#9;&#9; {{ @$client->user_type }}</h3>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">@lang("cms.Entity Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" value="{{ @$client->name }}" readonly>
                </div>

                <label class="col-lg-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" @if(@$client->revoked == 0) value="ACTIVE" @else value="INACTIVE" @endif readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">@lang("cms.Entity Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" value="{{ @$client->user_type }}" readonly>
                </div>

                <label class="col-lg-2">@lang("cms.Created At")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" value="{{ @$client->created_at }}" readonly>
                </div>
            </div>
        </div>

        <hr class="mx-3 font-weight-bold">

        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">@lang('cms.Authentication')</h3>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">@lang("cms.Client ID")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="client_id" name="client_id" value="{{ @$client->id }}" readonly>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">@lang("cms.Client Secret")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ @$client->secret }}" readonly>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">@lang("cms.Public Key")</label>
                <div class="col-sm-4">
                    <textarea class="form-control" id="public_key" name="public_key" rows="15" readonly>{{ @$client->owner['rsa_key']['public_key'] }}</textarea>
                </div>
            </div>
        </div>

        <hr class="mx-3 font-weight-bold">

        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">@lang('cms.Permissions')</h3>
            </div>

            @foreach($permissions as $permission)
                <div class="form-group row">
                    <label class="col-sm-2 font-weight-bold">{{ str_replace('_', ' & ',strtoupper(@$permission[0]->service)) }}</label>
                </div>
                <div class="form-group row">
                    @foreach($permission as $service_name)
                        <input type="checkbox" class="form-group ml-2 mt-1" id="permission-{{ @$service_name->id }}" name="permission-{{ @$service_name->id }}" value="{{ @$service_name->id }}"
                               @if(in_array(@$service_name->id, $permission_ids)) checked @endif disabled
                        >
                        <label class="col-sm-2 font-weight-bold">{{ str_replace('_', ' ',strtoupper(@$service_name->name)) }}</label>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#btn-copy-client-id").click(function() {
                var copyText = $("#client_id");
                copyText.select();
                navigator.clipboard.writeText(copyText.val());
            });

            $("#btn-copy-client-secret").click(function() {
                var copyText = $("#client_secret");
                copyText.select();
                navigator.clipboard.writeText(copyText.val());
            });
        });
    </script>
@endsection

