<x-app-layout>
    <x-page.header :title="__('cms.Roles')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :text="__('cms.Roles')" :link="route('store.roles.index')" />
            <x-breadcrumb.active>@lang('cms.Create')</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Create Role')">
        <div id="role-form">
            <form action="{{ route('store.roles.store') }}" method="POST">
                @csrf

                <x-form.item :label="__('cms.Name')" label-position="left">
                    <x-form.input name="name" value="{{ old('name') }}" />
                </x-form.item>

                <x-form.item :label="__('cms.Description')" label-position="left">
                    <x-form.input name="description" value="{{ old('description') }}" type="textarea" />
                </x-form.item>

                <x-form.item :label="__('cms.Target Type')" label-position="left">
                    <x-form.select v-model="targetType" name="target_type" @change="handleChangeTargetType">
                        @foreach ($targetTypes as $type)
                            <option value="{{ $type }}" @if (old('target_type') == $type) selected @endif>{{ $type }}</option>
                        @endforeach
                    </x-form-select>
                </x-form.item>

                <x-form.item :label="__('cms.Status')" label-position="left">
                    <x-form.select name="active">
                        <<option value="0" @if (old('active') == 0) selected @endif>@lang('cms.Inactive')</option>
                        <option value="1" @if (old('active') == 1) selected @endif>@lang('cms.Active')</option>
                    </x-form-select>
                </x-form.item>

                @include('store.role.partials.access-control')

                <x-form.actions :cancel="route('store.roles.index')" />
            </form>
        </div>
    </x-page.content>

    @include('store.role.partials.access-control-js')
</x-app-layout>
