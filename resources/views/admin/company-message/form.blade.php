<form id="commonForm" action="{{  route('admin.company-messages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        {{-- comapny --}}
        <input type="hidden" name="company_id" class="form-control" value="{{ $company_id }}">

        {{-- Subject --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" class="form-control" value="">
            <span class="text-danger error-text subject_error"></span>
        </div>

        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Priority <span class="text-danger">*</span></label>
            <select name="priority" class="form-select multi-select">
                <option value="">
                    Select Priority
                </option>
                <option value="low">
                    Low
                </option>
                <option value="medium">
                    Medium
                </option>
                <option value="high">
                    High
                </option>
            </select>
            <span class="text-danger error-text priority_error"></span>
        </div>

        {{-- Message --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Message <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control" rows="6"></textarea>
            <span class="text-danger error-text message_error"></span>
        </div>
    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="submit" class="btn btn-success">
            Send
        </button>
    </div>
</form>