<form id="commonForm1" action="{{  route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @php
        $selectedUserIds = collect(old('user_id', isset($userId) ? [$userId] : []))
            ->map(fn ($value) => (string) $value)
            ->all();
        $selectedUserType = old('user_type', $userType ?? '');
    @endphp
    <div class="row">
        {{-- Users --}}
        @if($companyId)
            <input type="hidden" name="company_id" value="{{ $companyId ?? null }}">
        @endif
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>User Type<span class="text-danger">*</span></label>
            <select name="user_type" id="message-user-type" class="form-select">
                <option value="">Select User Type</option>
                <option value="customer" {{ $selectedUserType === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="planner" {{ $selectedUserType === 'planner' ? 'selected' : '' }}>Planner</option>
                <option value="production_staff" {{ $selectedUserType === 'production_staff' ? 'selected' : '' }}>Production Staff</option>
            </select>
            <span class="text-danger error-text user_type_error"></span>
        </div>

        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Cus User<span class="text-danger">*</span></label>
            <select
                name="user_id[]"
                id="message-user-id"
                class="form-select multi-select"
                multiple
                data-placeholder="Search and select users"
                data-url="{{ route('admin.messages.recipients') }}"
                {{ $selectedUserType ? '' : 'disabled' }}
            >
                @foreach(($selectedUsers ?? collect()) as $user)
                    <option
                        value="{{ $user->id }}"
                        {{ in_array((string) $user->id, $selectedUserIds, true) ? 'selected' : '' }}
                    >
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <span class="text-danger error-text user_id_error"></span>
        </div>


        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Priority <span class="text-danger">*</span></label>
            <select name="priority" id="priority" class="form-select priority-select">
                <option value="">
                    Select Priority
                </option>
                <option value="low" class="low">Low</option>
                <option value="medium" class="medium">Medium</option>
                <option value="high" class="high">High</option>
            </select>
            <span class="text-danger error-text priority_error"></span>
        </div>


        {{-- Subject --}}
        <div class="col-lg-12 mb-3 o-f-inp d-none">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" class="form-control" value="">
            <span class="text-danger error-text subject_error"></span>
        </div>

        {{-- Message --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Message <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control" rows="6" id="message"></textarea>
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
