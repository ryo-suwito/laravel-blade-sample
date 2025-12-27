@props([
    'label' => null,
    'value' => null,
    'selected' => false,
])

<option value="{{ $value }}" @if ($selected) selected @endif>{{ $label }}</option>
