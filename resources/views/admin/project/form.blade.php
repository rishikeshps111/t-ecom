<form action="{{ isset($project) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($project))
        @method('PUT')
    @endif

    <h5 class="mb-3">Project Details</h5>
    <div class="row">
        {{-- Project Category --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Category <span class="text-danger">*</span></label>
            <select name="project_category_id" class="form-select search-select">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('project_category_id', isset($project) ? $project->project_category_id : '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('project_category_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Company --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Company <span class="text-danger">*</span></label>
            <select name="company_id" class="form-select search-select">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', isset($project) ? $project->company_id : '') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Project Name --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Project Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                value="{{ old('name', isset($project) ? $project->name : '') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select search-select">
                @php
                    $statuses = ['open' => 'Open', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'on_hold' => 'On Hold'];
                @endphp
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', isset($project) ? $project->status : '') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Start Date --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control"
                value="{{ old('start_date', isset($project) ? $project->start_date?->format('Y-m-d') : '') }}">
            @error('start_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- End Date --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>End Date <span class="text-danger">*</span></label>
            <input type="date" name="end_date" class="form-control"
                value="{{ old('end_date', isset($project) ? $project->end_date?->format('Y-m-d') : '') }}">
            @error('end_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Description --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control"
                rows="4">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-danger">
            Back
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($project) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>