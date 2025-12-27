@props([
    'label' => null,
    'labelPosition' => 'top',
])

@if ($labelPosition == 'top')
    <div class="form-group">
        @if (isset($label))
            <label for="{{ isset($for) ? $for : null }}">{{ $label }}</label>
        @endif

        {{ $slot }}
    </div>
@endif

@if (in_array($labelPosition, ['left', 'right']))
    <div class="form-group row">
        @if (isset($label))
            <div class="col-lg-3 col-form-label @if ($labelPosition == 'right') text-right @endif">
                <label for="{{ isset($for) ? $for : null }}">{{ $label }}</label>
            </div>
        @endif

        <div class="col-lg-9">
            {{ $slot }}
        </div>
    </div>
@endif
