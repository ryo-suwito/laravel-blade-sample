<x-app-layout>
    <x-page.header :title="__('cms.Roles')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :text="__('cms.Roles')" :link="route('store.roles.index')" />
            <x-breadcrumb.active>@lang('cms.Details')</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Role Details')">
        <x-slot name="actions">
            @hasaccess('STORE.ROLES_EDIT')
                <a class="btn btn-primary" href="{{ route('store.roles.edit', ['id' => $role['id']]) }}">@lang('cms.Edit')</a>
            @endhasaccess
        </x-slot>

        <div class="row">
            <div class="col-md-6">
                <x-form.item :label="__('cms.Name')" label-position="left">
                    <x-form.input value="{{ $role['name'] }}" readonly />
                </x-form.item>

                <x-form.item :label="__('cms.Description')" label-position="left">
                    <x-form.input value="{{ $role['description'] }}" type="textarea" readonly />
                </x-form.item>

                <x-form.item :label="__('cms.Target Type')" label-position="left">
                    <x-form.input value="{{ $role['target_type'] }}" readonly />
                </x-form.item>
            </div>
            <div class="col-md-6">
                <x-form.item :label="__('cms.Status')" label-position="left">
                    <x-form.input value="{{ $role['active'] == 1 ? __('cms.ACTIVE') : __('cms.INACTIVE') }}" readonly />
                </x-form.item>
            </div>
        </div>

        <div class="mt-4">
            <h5 @error('access_control')  class="text-danger" @enderror>@lang('cms.Access Controls')</h5>

            @error('access_control')
                <p class="text-danger mb-2">{{ $message }}</p>
            @enderror

            @foreach ($accessControls as $group)
                <div class="mb-4">
                    <div class="access_control__group-name">
                        <h5 class="font-weight-bold h5">{{ $group['name'] }}</h5>
                    </div>
                    <div class="row">
                        @foreach ($group['access_controls'] as $access)
                            <div class="col-md-4 mb-2">
                                <div class="font-weight-bold mb-1 access-control__name" style="user-select: none;">{{ $access['name'] }}</div>
                                <div class="px-1 access-control__action-list">
                                    @foreach ($access['actions'] as $action)
                                        <label class="d-flex align-items-center" style="user-select: none;">
                                            <input
                                                class="access-control__action-input @if (in_array($action['value'], $role['access_control'])) active @endif"
                                                type="checkbox"
                                                name="access_control[]"
                                                value="{{ $action['value'] }}"
                                                @if (in_array($action['value'], $role['access_control'])) checked @endif
                                                onclick="return false" />

                                            <span class="ml-2">
                                                {{ $action['name'] }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </table>
                </div>
            @endforeach
        </div>
    </x-page.content>

    @push('styles')
        <style>
            .access_control__group-name {
                overflow: hidden;
            }

            .access_control__group-name h5::after {
                background-color: rgba(255, 255, 255, 0.2);
                content: "";
                display: inline-block;
                height: 1px;
                position: relative;
                vertical-align: middle;
                width: 100%;
            }
        
            .access_control__group-name h5::after {
                left: 0.5rem;
                margin-right: -100%;
            }
            .access-control__action-input {
                -webkit-appearance: none;
                appearance: none;
                width: 13px;
                height: 13px;
                background-color: #cc4744;
                border-radius: 100%;
            }

            .access-control__action-input.active {
                background-color: transparent;
            }

            .access-control__action-input.active::before {
                content: '';
                width: 13px;
                height: 13px;
                background-color: #43a76f;
                position: absolute;
                border-radius: 100%;
            }
        </style>
    @endpush
</x-app-layout>
