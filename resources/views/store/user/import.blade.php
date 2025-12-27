<x-app-layout>
    <x-slot name="header">
        <x-page.header :title="__('cms.Import Users')">
            <x-slot name="breadcrumb">
                <x-breadcrumb.home />
                <x-breadcrumb.link link="/" :text="__('cms.Users')" />
                <x-breadcrumb.active>@lang('cms.Import')</x-breadcrumb.active>
            </x-slot>
        </x-page.header>
    </x-slot>

    <x-page.content :title="__('cms.Import Users')">
        <div class="border p-4">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <form action="{{ route('store.users.import_preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <input class="{{ $errors->has('file') ? 'is-invalid' : null }}" type="file" name="file" />
                            @error('file')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4 text-center">
                            <button class="btn btn-primary" type="submit">@lang('cms.Submit')</button>
                        </div>
                    </form>
                    <div class="mt-5">
                        <p>Don't have a template? download <a href="{{ asset('storage/templates/import-users.csv') }}">here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>
</x-app-layout>
