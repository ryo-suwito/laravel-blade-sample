@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Client Credential Create")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("client_credential.index") }}" class="breadcrumb-item">@lang("cms.Client Credential")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('client_credential.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Type")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="type" id="type" required>
                            <option value="" selected disabled>@lang("cms.Select One")</option>
                            <option value="BENEFICIARY">@lang("cms.Beneficiary")</option>
                            <option value="PARTNER">@lang("cms.Partner")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Name")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="entity_id" id="entity_id" required>
                            <option selected disabled>Choose Entity Type First!</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2">@lang("cms.Status")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="status" id="status" required>
                            <option value="0">@lang("cms.Active")</option>
                            <option value="1">@lang("cms.Inactive")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Public Key")</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" id="public_key" name="public_key" rows="15"></textarea>
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
                    <div class="form-group col">
                        @foreach($permission as $service_name)
                            <input type="checkbox" class="form-group mt-1" id="permission-{{ $service_name->id }}" name="permission[]" value="{{ $service_name->id }}">
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

@section('post_scripts')
    <script>
        $(document).ready(function() {
            $('#type').change(function () {
                let type = document.getElementById('type').value;

                $('#entity_id').empty();
                if (type == 'BENEFICIARY'){
                    $('#entity_id').select2({
                        ajax: {
                            url: "{{ route('credential.customer.select2') }}",
                            type: "GET",
                            dataType: 'json',
                            delay: 500,
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page
                                };
                            },
                            processResults: function(data, params) {
                                params.page = params.page || 1;

                                return {
                                    pagination: {
                                        more: true
                                    },
                                    results: data
                                };
                            },
                            cache: true,
                        },
                        placeholder: 'Search Customer',
                    })
                }else if (type == 'PARTNER'){
                    $('#entity_id').select2({
                        ajax: {
                            url: "{{ route('credential.partner.select2') }}",
                            type: "GET",
                            dataType: 'json',
                            delay: 500,
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page
                                };
                            },
                            processResults: function(data, params) {
                                params.page = params.page || 1;

                                return {
                                    pagination: {
                                        more: true
                                    },
                                    results: data
                                };
                            },
                            cache: true,
                        },
                        placeholder: 'Search Partner',
                    })
                }
            });
        });
    </script>
@endsection

