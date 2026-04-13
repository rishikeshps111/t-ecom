<div class="col-lg-4 mb-3 o-f-inp">
    <label>{{ $label }}</label>

    <input type="file" name="{{ $name }}" class="form-control image-input" data-preview="{{ $name }}_preview"
        accept="image/*">

    {{-- Existing Image --}}
    <div class="mt-2">
        <img id="{{ $name }}_preview"
            src="{{ isset($model) && $model->$name ? asset('storage/' . $model->$name) : '' }}" class="img-thumbnail"
            style="max-height: 80px; {{ empty($model->$name ?? null) ? 'display:none;' : '' }}">
    </div>

    @error($name)
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>