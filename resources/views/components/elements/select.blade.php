<div class="mb-3">
    @if ($label ?? false)
        <label for="{{ $id ?? $name }}" class="fw-semibold fs-6 mb-2 required">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-select {{ $class ?? '' }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        @if(isset($disabled) && $disabled === 'true') disabled @endif
    >
        <option></option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ $value == $selected  ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
