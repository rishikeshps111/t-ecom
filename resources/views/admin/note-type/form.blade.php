<form id="commonForm"
    action="{{ isset($record) ? route('admin.note-types.update', $record->id) : route('admin.note-types.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($record))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Note Type<span class="text-danger">*</span></label>
            <input type="text" name="note" class="form-control" value="{{ $record->note ?? '' }}">
            <span class="text-danger error-text note_error"></span>
        </div>
    </div>
    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="submit" class="btn btn-success">
            {{ isset($record) ? 'Update' : 'Add' }}
        </button>
    </div>
</form>