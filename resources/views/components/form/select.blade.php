@props([
    'name' => '',
    'value' => '',
])

<select {{ $attributes->merge([
    'class' => $errors->has($name) ? 'form-control is-invalid' : 'form-control',
    'name' => $name,
]) }}>
    {{ $slot }}
</select>

@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
