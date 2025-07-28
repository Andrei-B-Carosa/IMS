@props([
    'label' => null,
    'id' => null,
    'name',
    'class' => '',
])

@if ($label ?? false)
<label for="{{ $id ?? $name }}" class="fw-semibold fs-6 mb-2">{{ $label }}</label>
@endif

<textarea
    class="form-control {{ $class ?? '' }}"
    rows="5"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'form-select']) }}
>
</textarea>
