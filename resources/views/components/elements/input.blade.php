@props([
    'label' => null,
    'id' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'class' => '',
    'required' => false,
    'disabled' => false,
])


@if ($label ?? false)
    <label for="{{ $id ?? $name }}" class="form-label text-capitalize @if(isset($required)) required @endif">{{ $label }}</label>
@endif
<input
    type="{{ $type ?? 'text' }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ $value ?? '' }}"
    class="form-control {{ $class ?? '' }}"
    @if(isset($disabled) && $disabled === 'true') disabled @endif
    {{ $attributes->merge(['class' => 'form-control']) }}

/>
