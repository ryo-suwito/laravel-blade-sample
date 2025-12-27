@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
])

@if ($type == 'text')
    <input {{ $attributes->merge([
        'class' => $errors->has($name) ? 'form-control is-invalid' : 'form-control',
        'name' => $name,
        'value' => $value,
    ]) }} />
@endif

@if ($type == 'textarea')
    <textarea {{ $attributes->merge([
        'class' => $errors->has($name) ? 'form-control is-invalid' : 'form-control',
        'name' => $name,
    ]) }}>{{ $value }}</textarea>
@endif

@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
