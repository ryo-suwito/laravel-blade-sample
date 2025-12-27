<x-app-layout>
    <x-page.header :title="__('cms.Setting')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('yukk_co.general.settings.index')" :text="__('cms.Setting')" />
            <x-breadcrumb.active>
                {{ __('cms.Detail') }}
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label"
                                                    for="name">@lang('cms.Label')</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('cms.Label')" value="{{ $item['label'] }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label"
                                                    for="name">@lang('cms.Name')</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('cms.Name')" value="{{ $item['name'] }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label"
                                                    for="name">@lang('cms.Value')</label>
                                                <div class="col-lg-9">
                                                    <textarea class="form-control" name="value" cols="30" rows="5" disabled>{{ $item['value'] }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label"
                                                    for="name">@lang('cms.Active')</label>
                                                <div class="col-lg-9">
                                                    <label><input class="form-control" type="checkbox" name="active"
                                                            @if ($item['active']) checked="checked" @endif
                                                            disabled></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm col-lg">
                            <a href="{{ route('yukk_co.general.settings.index') }}" class="btn btn-block btn-warning">
                                @lang('cms.Go Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>
    @swal
</x-app-layout>
