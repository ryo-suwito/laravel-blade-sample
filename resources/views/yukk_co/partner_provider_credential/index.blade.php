<x-app-layout>
    <x-page.header :title="__('cms.Partner')">
        @hasaccess('PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.CREATE')
            <x-slot name="actions">
                <a type="button" class="btn btn-primary w-100 w-sm-auto" href="{{ route('cms.yukk_co.partners.credentials.create', ['partner_id' => $partner['id']]) }}">
                    {{ __('cms.Create Credential') }}
                </a>
            </x-slot>
        @endhasaccess

        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.list')" :text="__('cms.Partner')"/>
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.item', ['partner_id' => $partner['id']])" :text="$partner['name']"/>
            <x-breadcrumb.active>{{ __("cms.Manage Credentials") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Partner')">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __("cms.Provider") }}</th>
                        <th>{{ __("cms.Payment Channel") }}</th>
                        <th>{{ __("cms.Status") }}</th>
                        <th>{{ __("cms.Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($providers as $provider)
                        <tr>
                            <td>{{ $provider['name'] }}</td>
                            <td>{{ str_replace(",",", ", $provider['pivot']['payment_channels']) }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switch-{{ $provider['id'] }}" 
                                    @if($provider['pivot']['active'] ?? 0)
                                        checked 
                                    @endif
                                    disabled>
                                    <label class="custom-control-label" for="switch-{{ $provider['id'] }}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route("cms.yukk_co.partners.credentials.show", ["partner_id" => $partner["id"], "token" => $provider["token"]]) }}" class="dropdown-item"><i class="icon-search4"></i> {{ __("cms.Detail") }}</a>
                                            @hasaccess("PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.UPDATE")
                                                <a href="{{ route("cms.yukk_co.partners.credentials.edit", ["partner_id" => $partner["id"], "token" => $provider["token"]]) }}" class="dropdown-item"><i class="icon-pencil7"></i> {{ __("cms.Edit") }}</a>
                                            @endhasaccess
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-page.content>

    @swal
</x-app-layout>
