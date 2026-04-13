<form action="{{ isset($company) ? route('admin.company.update', $company->id) : route('admin.company.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($company))
        @method('PUT')
    @endif

    <div class="row">

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Company ID <span class="text-danger">*</span></label>
            <input type="text" name="company_code" class="form-control" value="{{$company->company_code ?? $code}}"
                readonly>
            @error('company_code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Cus User <span class="text-danger">*</span></label>
            <select name="business_user_id" id="business_user_id" class="form-control search-select">
                <option value="">Select Cus User</option>
                @foreach ($corpUsers as $corpUser)
                    <option value="{{ $corpUser->id }}" {{ old('business_user_id', $company->business_user_id ?? '') == $corpUser->id ? 'selected' : '' }}>
                        {{ $corpUser->name }}
                    </option>
                @endforeach
            </select>
            @error('business_user_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Company Type<span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-control search-select">
                <option value="">Select Company Type</option>
                @foreach ($companyTypes as $companyType)
                    <option value="{{ $companyType->id }}" {{ old('company_type_id', $company->company_type_id ?? '') == $companyType->id ? 'selected' : '' }}>
                        {{ $companyType->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>



        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Company Name <span class="text-danger">*</span></label>
            <input type="text" name="company_name" class="form-control"
                value="{{ old('company_name', $company->company_name ?? '') }}" required>
            @error('company_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group<span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-control search-select">
                <option value="">Select Total Group</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('total_group_id', $company->total_group_id ?? '') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner<span class="text-danger">*</span></label>
            <select name="planner_id" id="planner_id" class="form-control search-select">
                <option value="">Select Planner</option>
                @foreach ($planners as $planner)
                    <option value="{{ $planner->id }}" {{ old('planner_id', $company->planner_id ?? '') == $planner->id ? 'selected' : '' }}>
                        {{ $planner->name }}
                    </option>
                @endforeach
            </select>
            @error('planner_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Production Staff<span class="text-danger">*</span></label>
            <select name="production_staff_id" id="production_staff_id" class="form-control search-select">
                <option value="">Select Production Staff</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ old('production_staff_id', $company->production_staff_id ?? '') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
            @error('production_staff_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Phone No <span class="text-danger">*</span></label>
            <input type="text" name="mobile_no" class="form-control"
                value="{{ old('mobile_no', $company->mobile_no ?? '') }}">
            @error('mobile_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Email <span class="text-danger">*</span></label>
            <input type="text" name="email_address" class="form-control"
                value="{{ old('email_address', $company->email_address ?? '') }}">
            @error('email_address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Country</label>
            <select class="form-control" disabled>
                <option selected>Malaysia</option>
            </select>
            <input type="hidden" name="business_country" value="{{ $company->address->country ?? 'Malaysia' }}">
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>State <span class="text-danger">*</span></label>
            <select name="state_id" id="state_id" class="form-control">
                <option value="">Select State</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id', $company->address->state_id ?? '') == $state->id ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
            @error('state_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>City <span class="text-danger">*</span></label>
            <select name="city_id" id="city_id" class="form-control">
                <option value="">Select City</option>
            </select>
            @error('city_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
                <option value="active" {{ old('status', $company->status ?? '') == 'active' ? 'selected' : '' }}>
                    Active</option>
                <option value="draft" {{ old('status', $company->status ?? '') == 'draft' ? 'selected' : '' }}>
                    Draft</option>
                <option value="inactive" {{ old('status', $company->status ?? '') == 'inactive' ? 'selected' : '' }}>
                    Inactive</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Address</label>
            <textarea name="address" class="form-control editor"
                rows="3">{{ old('address', $company->address ?? '') }}</textarea>
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>

    <div class="btn-sub-box">
        <a href="{{ route('admin.manage.company') }}" class="btn-back-cs">
            Back
        </a>
        <button type="submit" class="submit-btn">
            {{ isset($company) ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>