<div class="mt-4 d-flex justify-content-end">
    @if (isset($cancel))
        <x-form.cancel :url="$cancel">@lang('cms.Cancel')</x-form.cancel>
    @endif
    @if (empty($slot->toHtml()))
        <x-form.submit>@lang('cms.Save')</x-form.submit>
    @else
        {{ $slot }}
    @endif
</div>
