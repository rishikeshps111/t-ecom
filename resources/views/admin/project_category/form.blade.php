<form
    action="{{ isset($projectCategory) ? route('admin.project-categories.update', $projectCategory->id) : route('admin.project-categories.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($projectCategory))
        @method('PUT')
    @endif

    <h5 class="mb-3">Category Details</h5>
    <div class="row">
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', isset($projectCategory) ? $projectCategory->name : '') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="is_active" class="form-select">
                <option value="1"
                    {{ old('is_active', isset($projectCategory) ? $projectCategory->is_active : '') == '1' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0"
                    {{ old('is_active', isset($projectCategory) ? $projectCategory->is_active : '') == '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
            @error('is_active')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', isset($projectCategory) ? $projectCategory->description : '') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.project-categories.index') }}" class="btn btn-danger">
            Back
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($projectCategory) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>
