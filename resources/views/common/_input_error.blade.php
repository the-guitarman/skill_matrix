@if (!empty($errors->has($validation_key)))
    <div class="invalid-feedback">
        {{ $errors->first($validation_key) }}
    </div>
@endif