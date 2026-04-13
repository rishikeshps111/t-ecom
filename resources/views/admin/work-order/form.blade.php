<form
    action="{{ isset($workPlan) ? route('admin.work-plans.update', $workPlan->id) : route('admin.work-plans.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($workPlan))
        @method('PUT')
    @endif

    @if($customerID)
        <input type="hidden" name="custom" value="1">
    @endif

    <div class="row">
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Work Plan Number<span class="text-danger">*</span></label>
            <input type="text" name="workplan_number" class="form-control"
                value="{{ old('workplan_number', $workPlan->workplan_number ?? $code) }}" readonly>
            @error('workplan_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control"
                value="{{ old('date', isset($workPlan) ? $workPlan->date->format('Y-m-d') : now()->format('Y-m-d')) }}">
            @error('date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Customer Name <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-select search-select" {{ $customerID ? 'disabled' : '' }}>
                <option value="">-- Select Customer Name --</option>

                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old(
                        'company_id',
                        $workPlan->company_id
                        ?? $customerID
                        ?? ''
                    ) == $customer->id ? 'selected' : '' }} data-type="{{ $customer->company_type_id }}"
                                    data-total-group="{{ $customer->total_group_id }}" data-name="{{ $customer->company_name }}"
                                    data-phone="{{ $customer->mobile_no }}" data-email="{{ $customer->email_address }}">
                                    {{ $customer->company_name }}
                                </option>
                @endforeach
            </select>

            @if($customerID)
                <input type="hidden" name="company_id" value="{{ $customerID }}">
            @endif

            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Contact Name</label>
            <input type="text" id="customer_name" class="form-control" readonly>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Email</label>
            <input type="email" id="customer_email" class="form-control" readonly>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Phone</label>
            <input type="text" id="customer_phone" class="form-control" readonly>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>WP Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select search-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $workPlan->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner <span class="text-danger">*</span></label>
            <select name="planner_id" id="planner_id" class="form-select search-select">
                <option value="">-- Select Planner --</option>
                @foreach ($planners as $planner)
                    <option value="{{ $planner->id }}" {{ old('planner_id', $workPlan->planner_id ?? '') == $planner->id ? 'selected' : '' }}>
                        {{ $planner->name }}
                    </option>
                @endforeach
            </select>
            @error('planner_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select search-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $workPlan->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-12 mb-3 o-f-inp">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control"
                rows="5">{{ old('description', $workPlan->description ?? '') }}</textarea>

            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Attachment</label>
            <input type="file" name="attachment" id="attachment" class="form-control">

            @error('attachment')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <div class="mt-2" id="attachment_preview">
                @if(isset($workPlan) && $workPlan->attachment)
                    @php
                        $fileExt = pathinfo($workPlan->attachment, PATHINFO_EXTENSION);
                    @endphp

                    @if(in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $workPlan->attachment) }}" alt="Attachment"
                            style="max-width: 150px; max-height: 150px;">
                    @elseif(in_array($fileExt, ['pdf']))
                        <a href="{{ asset('storage/' . $workPlan->attachment) }}" target="_blank">View PDF</a>
                    @else
                        <a href="{{ asset('storage/' . $workPlan->attachment) }}" target="_blank">View File</a>
                    @endif
                @endif
            </div>
        </div>


        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select">
                <option value="pending" {{ old('status', $workPlan->status ?? '') == 'pending' ? 'selected' : '' }}>
                    Pending</option>
                <option value="approved" {{ old('status', $workPlan->status ?? '') == 'approved' ? 'selected' : '' }}>
                    Approved
                </option>
                <option value="cancelled" {{ old('status', $workPlan->status ?? '') == 'cancelled' ? 'selected' : ''
                    }}>
                    Cancelled</option>
            </select>
            @error('status')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

    </div>
    <hr>
    <div class="d-flex justify-content-between">
        <a href="{{ route_with_query('admin.work-plans.index', [
    'customer_id' => $customerID ?? null,
]) }}" class="btn btn-danger">
            Back
        </a>
        <div class="d-flex gap-2">
            <button type="submit" name="status" value="submitted" class="btn btn-success">
                {{ isset($workPlan) ? 'Update' : 'Save' }}
            </button>
        </div>
    </div>
</form>