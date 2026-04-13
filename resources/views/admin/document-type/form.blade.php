<form id="commonForm"
    action="{{ isset($record) ? route('admin.document-types.update', $record->id) : route('admin.document-types.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($record))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Type<span class="text-danger">*</span></label>
            <input type="text" name="type" class="form-control" value="{{ $record->type ?? '' }}">
            <span class="text-danger error-text type_error"></span>
        </div>
    </div>
    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="submit" class="btn btn-success">
            {{ isset($record) ? 'Update' : 'Add' }}
        </button>
    </div>
</form>