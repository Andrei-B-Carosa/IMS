@props([
    'label' => null,
    'id' => null,
    'name',
    'class' => '',
])

@if ($label ?? false)
<label for="{{ $id ?? $name }}" class="form-label fw-bold">{{ $label }}</label>
@endif

<textarea
    class="form-control {{ $class ?? '' }}"
    rows="5"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'form-select']) }}
>
</textarea>
