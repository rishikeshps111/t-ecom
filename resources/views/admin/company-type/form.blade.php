<form id="commonForm"
    action="{{ isset($record) ? route('admin.company-types.update', $record->id) : route('admin.company-types.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($record))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Service Type<span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $record->name ?? '' }}">
            <span class="text-danger error-text name_error"></span>
        </div>
    </div>
    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="submit" class="btn btn-success">
            {{ isset($record) ? 'Update' : 'Add' }}
        </button>
    </div>
</form>