<form id="commonForm" class="row" method="POST" action="{{ route('admin.planner-documents.status') }}">
    @csrf
    <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
    <div class="col-lg-12 mb-3 o-f-inp">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select search-select">
            @php
                $statuses = [
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ];
            @endphp
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" {{ (isset($project) ? $project->status : '' == $key) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <span class="text-danger error-text status_error"></span>
    </div>
    <div class="col-lg-12 modal-btn-group d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            Update Status
        </button>
    </div>
</form>