<form id="commonForm" class="row" method="POST" action="{{ route('admin.knowledge-bases.status') }}">
    @csrf
    <input type="hidden" name="knowledge_base_id" id="knowledge_base_id" value="{{ $knowledgeBase->id }}">
    <div class="col-lg-12 mb-3 o-f-inp">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select search-select">
            @php
                $statuses = [
                    'draft' => 'Draft',
                    'unpublished' => 'Unpublished',
                    'published' => 'Published',
                ];
            @endphp
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}"
                    {{ old('status', isset($knowledgeBase) ? $knowledgeBase->status : '') == $key ? 'selected' : '' }}>
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
