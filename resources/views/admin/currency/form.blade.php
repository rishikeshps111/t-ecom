<form id="commonForm"
    action="{{ isset($record) ? route('admin.currencies.update', $record->id) : route('admin.currencies.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($record))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Currency Name <span class="text-danger">*</span></label>
            <input type="text" name="currency_name" class="form-control" value="{{ $record->currency_name ?? '' }}">
            <span class="text-danger error-text currency_name_error"></span>
        </div>
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Currency Code <span class="text-danger">*</span></label>
            <input type="text" name="currency_code" class="form-control" value="{{ $record->currency_code ?? '' }}">
            <span class="text-danger error-text currency_code_error"></span>
        </div>
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Symbol <span class="text-danger">*</span></label>
            <input type="text" name="symbol" class="form-control" value="{{ $record->symbol ?? '' }}">
            <span class="text-danger error-text symbol_error"></span>
        </div>
    </div>
    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="submit" class="btn btn-success">
            {{ isset($record) ? 'Update' : 'Add' }}
        </button>
    </div>
</form>