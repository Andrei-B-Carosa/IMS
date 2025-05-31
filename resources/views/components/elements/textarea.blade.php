@if ($label ?? false)
<label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
@endif

<textarea
    class="form-control {{ $class ?? '' }}"
    rows="5"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'form-select']) }}
>
</textarea>
