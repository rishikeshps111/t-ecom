<form
    action="{{ isset($workOrder) ? route('admin.work-orders.update', $workOrder->id) : route('admin.work-orders.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($workOrder))
        @method('PUT')
    @endif

    @if($customerID)
        <input type="hidden" name="custom" value="1">
    @endif

    <div class="row">

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Customer Name <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-select search-select" {{ $customerID ? 'disabled' : '' }}>
                <option value="">-- Select Customer Name --</option>

                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old(
                        'company_id',
                        $workOrder->company_id
                        ?? $customerID
                        ?? ''
                    ) == $customer->id ? 'selected' : '' }} data-type="{{ $customer->company_type_id }}"
                                    data-total-group="{{ $customer->total_group_id }}" data-planner="{{ $customer->planner_id }}"
                                    data-name="{{ $customer->company_name }}" data-phone="{{ $customer->mobile_no }}"
                                    data-staff="{{ $customer->production_staff_id ?? '' }}" data-email="{{ $customer->email_address }}">
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
            <label>Work Order Number<span class="text-danger">*</span></label>
            <input type="text" name="workplan_number" class="form-control"
                value="{{ old('workplan_number', $workOrder->workplan_number ?? '') }}" readonly>
            @error('workplan_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Email</label>
            <input type="email" id="customer_email" class="form-control" readonly>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner <span class="text-danger">*</span></label>
            <select name="planner_id" id="planner_id" class="form-select search-select">
                <option value="">-- Select Planner --</option>
                @foreach ($planners as $planner)
                    <option value="{{ $planner->id }}" {{ old('planner_id', $workOrder->planner_id ?? '') == $planner->id ? 'selected' : '' }}>
                        {{ $planner->name }}
                    </option>
                @endforeach
            </select>
            @error('planner_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Phone</label>
            <input type="text" id="customer_phone" class="form-control" readonly>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Production Staff <span class="text-danger">*</span></label>
            <select name="production_staff_id" id="production_staff_id" class="form-select search-select">
                <option value="">-- Select Production Staff --</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ old('production_staff_id', $workOrder->production_staff_id ?? '') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
            @error('production_staff_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control"
                value="{{ old('date', isset($workOrder) ? $workOrder->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                @if(!isset($workOrder)) min="{{ now()->format('Y-m-d') }}" @endif>
            @error('date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select search-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $workOrder->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>WO Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select search-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $workOrder->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>







        <div class="col-12 mb-3 o-f-inp">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control"
                rows="5">{{ old('description', $workOrder->description ?? '') }}</textarea>

            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- @php
        $fileTypes = [
        'image' => 'Image',
        'word' => 'Word',
        'pdf' => 'PDF',
        'excel' => 'Excel',
        'power_point' => 'Power Point',
        ];
        @endphp

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>File Type</label>
            <select name="file_type" id="file_type" class="form-select search-select">
                <option value="">-- Select File Type --</option>

                @foreach ($fileTypes as $value => $label)
                <option value="{{ $value }}" {{ old('file_type', $workOrder->file_type ?? '') === $value ? 'selected' :
                    '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>

            @error('file_type')
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
                @if(isset($workOrder) && $workOrder->attachment)
                @php
                $fileExt = pathinfo($workOrder->attachment, PATHINFO_EXTENSION);
                @endphp

                @if(in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ asset('storage/' . $workOrder->attachment) }}" alt="Attachment"
                    style="max-width: 150px; max-height: 150px;">
                @elseif(in_array($fileExt, ['pdf']))
                <a href="{{ asset('storage/' . $workOrder->attachment) }}" target="_blank">View PDF</a>
                @else
                <a href="{{ asset('storage/' . $workOrder->attachment) }}" target="_blank">View File</a>
                @endif
                @endif
            </div>
        </div> --}}

        <div class="col-12 o-f-inp">
            <label class="mb-2">Attachments</label>

            <div id="attachment-wrapper">

                {{-- EDIT MODE: existing attachments --}}
                @if(isset($workOrder) && $workOrder->attachments->count())
                    @foreach($workOrder->attachments as $index => $attachment)
                        <div class="row g-2 attachment-row align-items-end mb-2">
                            <input type="hidden" name="existing_attachment_ids[]" value="{{ $attachment->id }}">
                            <div class="col-lg-4">
                                <label>File Type</label>
                                <select name="attachments[{{ $index }}][type]" class="form-select shadow-none">
                                    <option value="">-- Select Type --</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->type }}" {{ $attachment->type == $type->type ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Keep existing file --}}
                                <input type="hidden" name="attachments[{{ $index }}][existing]" value="{{ $attachment->id }}">
                            </div>

                            <div class="col-lg-4">
                                <label>File</label>
                                <input type="file" name="attachments[{{ $index }}][file]"
                                    class="form-control attachment-file shadow-none">
                            </div>

                            <div class="col-lg-3">
                                <label>Preview</label>
                                <div class="attachment-preview">
                                    <a href="{{ asset('storage/' . $attachment->file) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">

                                        @if(Str::startsWith(mime_content_type(storage_path('app/public/' . $attachment->file)), 'image'))
                                            <img src="{{ asset('storage/' . $attachment->file) }}"
                                                style="width:28px;height:28px;object-fit:cover;border-radius:4px;">
                                            <span>View Image</span>
                                        @else
                                            <i class="bi bi-paperclip"></i>
                                            <span>View File</span>
                                        @endif
                                    </a>
                                </div>
                            </div>


                            <div class="col-lg-1 btn-field-rows">
                                @if($loop->first)
                                    <button type="button" class="btn btn-success add-row">+</button>
                                @else
                                    <button type="button" class="btn btn-danger remove-row">×</button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- CREATE MODE: empty row --}}
                @else
                    <div class="row g-2 attachment-row align-items-end">
                        <div class="col-lg-4">
                            <label>File Type</label>
                            <select name="attachments[0][type]" class="form-select shadow-none">
                                <option value="">-- Select Type --</option>
                                @foreach ($documentTypes as $type)
                                    <option value="{{ $type->type }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label>File</label>
                            <input type="file" name="attachments[0][file]" class="form-control attachment-file shadow-none">
                        </div>

                        <div class="col-lg-3">
                            <label>Preview</label>
                            <div class="attachment-preview"></div>
                        </div>

                        <div class="col-lg-1 btn-field-rows">
                            <button type="button" class="btn btn-success add-row">+</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select">
                <option value="pending" {{ old('status', $workOrder->status ?? '') == 'pending' ? 'selected' : '' }}>
                    Pending</option>
                <option value="approved" {{ old('status', $workOrder->status ?? '') == 'approved' ? 'selected' : '' }}>
                    Approved
                </option>
                <option value="cancelled" {{ old('status', $workOrder->status ?? '') == 'cancelled' ? 'selected' : ''
                    }}>
                    Cancelled</option>
            </select>
            @error('status')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

    </div>
    <hr>
    <div class="flex-btns-bottom">
        <a href="{{ route_with_query('admin.work-orders.index', [
    'customer_id' => $customerID ?? null,
]) }}" class="btn-back-cs">
            Back
        </a>
        <div class="d-flex gap-2">
            <button type="submit" name="status" value="submitted" class="submit-btn">
                {{ isset($workOrder) ? 'Update' : 'Save' }}
            </button>
        </div>
    </div>
</form>