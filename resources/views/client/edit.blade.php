@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Client Credential Edit")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("client_credential.index") }}" class="breadcrumb-item">@lang("cms.Client Credential")</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('client_credential.update', $client->id) }}" method="POST" id="update-oauth">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-12 font-weight-bold">{{ @$client->name }} &#9;&#9; | &#9;&#9; {{ @$client->user_type }}</h3>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="name" name="name" value="{{ @$client->name }}" readonly>
                    </div>

                    <label for="revoked" class="col-lg-2">@lang("cms.Status")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="revoked" id="revoked">
                            <option value="0" @if(@$client->revoked == 0) selected @endif>@lang("cms.Active")</option>
                            <option value="1" @if(@$client->revoked == 1) selected @endif>@lang("cms.Inactive")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Type")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="type" name="type" value="{{ @$client->user_type }}" readonly>
                    </div>

                    <label class="col-lg-2">@lang("cms.Created At")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="name" name="name" value="{{ @$client->created_at }}" readonly>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="model_id" name="model_id" value="{{ $client->user_id }}" readonly>
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
                        <button class="btn btn-primary" type="button" id="btn-generate-client-id">@lang("cms.Generate")</button>
                        <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Client Secret")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ @$client->secret }}" readonly>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-primary" type="button" id="btn-generate-client-secret">@lang("cms.Generate")</button>
                        <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Public Key")</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" id="public_key" name="public_key" rows="15">{{ @$client->owner['rsa_key']['public_key'] }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="mx-3 font-weight-bold">

            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-12 font-weight-bold">@lang('cms.Permissions')</h3>
                </div>

                @foreach($permissions as $index => $permission)
                    <div class="form-group row">
                        <label class="col-sm-2 font-weight-bold">{{ str_replace('_', ' & ',strtoupper(@$index)) }}</label>
                    </div>
                    <div class="form-group row">
                        @foreach($permission as $service_name)
                            <input type="checkbox" class="form-group ml-2 mt-1" id="permission-{{ $service_name->id }}" name="permissions[]" value="{{ $service_name->id }}"
                                   @if(in_array($service_name->id, $permission_ids)) checked @endif>
                            <label class="col-sm-2 font-weight-bold">{{ str_replace('_', ' ',strtoupper(@$service_name->name)) }}</label>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="justify-content-center row my-3">
                <button type="submit" class="col-sm-2 btn btn-primary justify-content-center mr-4">@lang("cms.Submit")</button>
                <a class="btn btn-secondary col-sm-2" href="{{ route('client_credential.index') }}">@lang("cms.Cancel")</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function uuid() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'
                .replace(/[xy]/g, function (c) {
                    const r = Math.random() * 16 | 0, 
                        v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function generateClientSecret() {
                var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
                var result           = [];
                var charactersLength = chars.length;
                for ( var i = 0; i < 40; i++ ) {
                    result.push(chars.charAt(Math.floor(Math.random() * charactersLength)));
                }
                return result.join('');
            }

            $("#btn-generate-client-id").click(function(e) {
                var clientId = uuid();
                $("#client_id").val(clientId);
                e.preventDefault();
            });

            $("#btn-copy-client-id").click(function() {
                var copyText = $("#client_id");
                copyText.select();
                navigator.clipboard.writeText(copyText.val());
            });

            $("#btn-generate-client-secret").click(function(e) {
                var clientSecret = generateClientSecret();
                $("#client_secret").val(clientSecret);
                e.preventDefault();
            });

            $("#btn-copy-client-secret").click(function() {
                var copyText = $("#client_secret");
                copyText.select();
                navigator.clipboard.writeText(copyText.val());
            });
        });
    </script>
@endsection

