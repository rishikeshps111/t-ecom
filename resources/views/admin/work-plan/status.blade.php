<form id="commonForm" class="row" method="POST" action="{{ route('admin.work-orders.status') }}">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $record->id }}">
    <div class="col-lg-12 mb-3 o-f-inp">
        <label>Status {{ $record->status }}<span class="text-danger">*</span></label>
        <select name="status" class="form-select search-select">
            @php
                $statuses = [
                    'approved' => 'Approved',
                    'cancelled' => 'Cancelled',
                ];
                $currentStatus = isset($record) ? $record->status : '';
            @endphp
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>
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