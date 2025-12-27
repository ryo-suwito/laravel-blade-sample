@if ($active === 1)
    <span class="badge badge-success">@lang('cms.ACTIVE')</span>
@elseif ($active === 0)
    <span class="badge badge-danger">@lang('cms.INACTIVE')</span>
@endif
