<form id="commonForm" action="{{  route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">

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

        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select multi-select">
                <option value="">
                    Select Type
                </option>
                <option value="public">
                    Public
                </option>
                <option value="private">
                    Private
                </option>
            </select>
            <span class="text-danger error-text type_error"></span>
        </div>

        <div class="col-lg-12 mb-3 o-f-inp" id="customersBlockTwo" style="display: none;">
            <label>User Type <span class="text-danger">*</span></label>
            <select name="user_type" class="form-select multi-select">
                <option value="">
                    Select User Type
                </option>
                <option value="planner">
                    Planner
                </option>
                <option value="customer">
                    Customer
                </option>
            </select>
            <span class="text-danger error-text user_type_error"></span>
        </div>

        {{-- Users --}}
        {{-- Customers --}}
        <div class="col-lg-12 mb-3 o-f-inp" id="customersBlock" style="display: none;">
            <label class="form-label label-between">
                Customers <span class="text-danger">*</span>
                <button type="button" class="btn btn-sm btn-primary check-all" data-target="customer">
                    Check All
                </button>
            </label>

            <div class="container-check-box" style="max-height: 200px; overflow-y: auto;">
                @foreach ($users as $user)
                    <div class="form-check ms-4">
                        <input type="checkbox" name="user_id[]" value="{{ $user->id }}"
                            class="form-check-input customer-checkbox" id="customer_{{ $user->id }}">
                        <label class="form-check-label" for="customer_{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </label>
                    </div>
                @endforeach
            </div>

            <span class="text-danger error-text user_id_error"></span>
        </div>

        <div class="col-lg-12 mb-3 o-f-inp" id="customersBlockThree" style="display: none;">
            <label class="form-label label-between">
                Planners <span class="text-danger">*</span>
                <button type="button" class="btn btn-sm btn-primary check-all" data-target="planner">
                    Check All
                </button>
            </label>

            <div class="container-check-box" style="max-height: 200px; overflow-y: auto;">
                @foreach ($planners as $user)
                    <div class="form-check ms-4">
                        <input type="checkbox" name="user_id[]" value="{{ $user->id }}"
                            class="form-check-input planner-checkbox" id="planner_{{ $user->id }}">
                        <label class="form-check-label" for="planner_{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </label>
                    </div>
                @endforeach
            </div>

            <span class="text-danger error-text user_id_error"></span>
        </div>


        {{-- Subject --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" class="form-control" value="">
            <span class="text-danger error-text subject_error"></span>
        </div>

        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Schedule Date <span class="text-danger">*</span></label>
            <input type="date" name="schedule_date" class="form-control" value="">
            <span class="text-danger error-text schedule_date_error"></span>
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
            Send Announcement
        </button>
    </div>
</form>