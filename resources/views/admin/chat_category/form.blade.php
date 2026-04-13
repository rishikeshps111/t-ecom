<form
    action="{{ isset($chatCategory) ? route('admin.chat-categories.update', $chatCategory->id) : route('admin.chat-categories.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($chatCategory))
        @method('PUT')
    @endif

    <h5 class="mb-3">Category Details</h5>
    <div class="row">
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', isset($chatCategory) ? $chatCategory->name : '') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="is_active" class="form-select">
                <option value="1"
                    {{ old('is_active', isset($chatCategory) ? $chatCategory->is_active : '') == '1' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0"
                    {{ old('is_active', isset($chatCategory) ? $chatCategory->is_active : '') == '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
            @error('is_active')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', isset($chatCategory) ? $chatCategory->description : '') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.chat-categories.index') }}" class="btn btn-danger">
            Back
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($chatCategory) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>
