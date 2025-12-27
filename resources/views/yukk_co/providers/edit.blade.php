@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Provider")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.provider.list") }}" class="breadcrumb-item">@lang("cms.Provider List")</a>
                    <a href="{{ route("cms.yukk_co.provider.item", $provider->id) }}" class="breadcrumb-item">{{ $provider->name }}</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
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
    <form action="{{ route("cms.yukk_co.provider.update", $provider->id) }}" method="post">
        @csrf

        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Provider")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="name" class="col-lg-4 col-form-label @if ($errors->has("name")) text-danger @endif">@lang("cms.Name")</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="name" id="name" class="form-control @if ($errors->has("name")) is-invalid @endif" value="{{ @$provider->name }}">
                                        @if ($errors->has("name"))
                                            <span class="invalid-feedback">{{ $errors->first("name") }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Payment Channel List")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PROVIDER_UPDATE", "AND"))
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="{{ route("cms.yukk_co.provider_has_payment_channel.create", $provider->id) }}" class="btn btn-primary">@lang("cms.New")</a>
                                        </div>
                                    </div>
                                @endif

                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                    <tr>
                                        <th>@lang("cms.Name")</th>
                                        <th>@lang("cms.Status")</th>
                                        <th>@lang("cms.Actions")</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($provider->provider_has_payment_channels as $index => $provider_has_payment_channel)
                                        <tr>
                                            <td>{{ @$provider_has_payment_channel->payment_channel->name }}</td>
                                            <td>
                                                <select class="form-control" name="provider_has_payment_channel[{{ $provider_has_payment_channel->provider_id }}][{{ $provider_has_payment_channel->payment_channel_id }}]">
                                                    <option value="1" @if($provider_has_payment_channel->active) selected @endif>@lang("cms.Active")</option>
                                                    <option value="0" @if(! $provider_has_payment_channel->active) selected @endif>@lang("cms.Inactive")</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons">
                                                    <div class="dropdown">
                                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="{{ route("cms.yukk_co.provider_has_payment_channel.item", [@$provider->id, @$provider_has_payment_channel->payment_channel->id]) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PROVIDER_UPDATE", "AND"))
                                                                <a href="{{ route("cms.yukk_co.provider_has_payment_channel.edit", [@$provider->id, @$provider_has_payment_channel->payment_channel->id]) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                                            @endif
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
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary btn-block" type="submit">@lang("cms.Submit")</button>
            </div>
        </div>

    </form>
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
        });
    </script>
@endsection