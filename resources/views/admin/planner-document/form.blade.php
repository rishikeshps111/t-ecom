<form
    action="{{ isset($plannerDocument) ? route('admin.planner-documents.update', $plannerDocument->id) : route('admin.planner-documents.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($plannerDocument))
        @method('PUT')
    @endif

    <h5 class="mb-3">Planner Details</h5>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select search-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $plannerDocument->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        @if(!auth()->user()->hasRole('Planner'))
            <div class="col-lg-4 mb-3 o-f-inp">
                <label>Planner <span class="text-danger">*</span></label>
                <select name="planner_id" id="planner_id" class="form-select search-select">
                    <option value="">-- Select Planner --</option>
                    @foreach ($planners as $user)
                        <option value="{{ $user->id }}" {{ old('planner_id', $plannerDocument->planner_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('planner_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="planner_id" value="{{ Auth::id() }}">
        @endif
        @if(Auth::user()->hasRole('Super Admin'))
            <div class="col-lg-4 mb-3 o-f-inp d-none">
                <label>Corp User <span class="text-danger">*</span></label>
                <select name="business_user_id" id="business_user_id" class="form-select search-select">
                    <option value="">-- Select Corp User --</option>
                    @foreach ($corpUsers as $user)
                        <option value="{{ $user->id }}" {{ old('business_user_id', $plannerDocument->business_user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('business_user_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="business_user_id" value="{{ Auth::id() }}">
        @endif

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select search-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $plannerDocument->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        {{-- Company --}}
        {{-- <div class="col-lg-6 mb-3 o-f-inp">
            <label>Company <span class="text-danger">*</span></label>
            <select name="company_id" class="form-select search-select">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ old('company_id', isset($plannerDocument) ? $plannerDocument->
                    company_id : '') == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
                @endforeach
            </select>
            @error('company_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        {{-- plannerDocument Name --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Title<span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                value="{{ old('title', isset($plannerDocument) ? $plannerDocument->title : '') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Financial Year <span class="text-danger">*</span></label>
            <select name="financial_year_id" class="form-select search-select">
                <option value="">-- Select year --</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}" {{ old('financial_year_id', isset($plannerDocument) ? $plannerDocument->financial_year_id : '') == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                @endforeach
            </select>
            @error('financial_year_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select search-select">
                @php
                    $statuses = ['active' => 'Active', 'inactive' => 'InActive'];
                @endphp
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', isset($plannerDocument) ? $plannerDocument->status : '') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Start Date --}}
        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control"
                value="{{ old('start_date', isset($plannerDocument) ? $plannerDocument->start_date?->format('Y-m-d') : '') }}">
            @error('start_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- End Date --}}
        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>End Date <span class="text-danger">*</span></label>
            <input type="date" name="end_date" class="form-control"
                value="{{ old('end_date', isset($plannerDocument) ? $plannerDocument->end_date?->format('Y-m-d') : '') }}">
            @error('end_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Description --}}
        <div class="col-lg-12 mb-3 o-f-inp d-none">
            <label>Description </label>
            <textarea name="description" class="form-control"
                rows="4">{{ old('description', isset($plannerDocument) ? $plannerDocument->description : '') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>

    <hr>

    <hr>

    <h5 class="mb-3">Document Files</h5>

    <div class="mb-3 o-f-inp file-input">
        <label>Upload Documents</label>
        <input type="file" name="documents[]" class="form-control" id="documentFiles" multiple
            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        <small class="text-muted">You can upload multiple files</small>

        @error('documents')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>

    {{-- Preview Section --}}
    <div class="row" id="filePreview"></div>

    {{-- Existing Files (Edit Mode) --}}
    @if(isset($plannerDocument) && $plannerDocument->files->count())
        <h6 class="mt-3">Existing Files</h6>
        <div class="row">
            @foreach($plannerDocument->files as $file)
                @php
                    $ext = pathinfo($file->document, PATHINFO_EXTENSION);
                    $typeOptions = [
                        'Image' => in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']),
                        'PDF' => strtolower($ext) === 'pdf',
                        'Word' => in_array(strtolower($ext), ['doc', 'docx']),
                        'Excel' => in_array(strtolower($ext), ['xls', 'xlsx']),
                        'Other' => !in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']),
                    ];
                @endphp

                <div class="col-md-3 mb-3">
                    <div class="border p-2 rounded text-center">
                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset($file->document) }}" class="img-fluid mb-2" style="height:100px;object-fit:cover;">
                        @else
                            <i class="bi bi-file-earmark-text fs-1 mb-2"></i>
                        @endif

                        <div class="small text-truncate mb-1">{{ basename($file->document) }}</div>

                        <select name="document_types_existing[{{ $file->id }}]" class="form-select form-select-sm">
                            @foreach($typeOptions as $label => $selected)
                                <option value="{{ $label }}" {{ $selected ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_files[]" value="{{ $file->id }}">
                            <label class="form-check-label text-danger">Remove</label>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif

    <div class="flex-btns-bottom mt-3">
        <a href="{{ route('admin.planner-documents.index') }}" class="btn-back-cs">
            Back
        </a>
        <button type="submit" class="submit-btn">
            {{ isset($plannerDocument) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>