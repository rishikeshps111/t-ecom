<form
    action="{{ isset($business_user) ? route('admin.business-users.update', $business_user->id) : route('admin.business-users.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($business_user))
        @method('PUT')
    @endif

    <h5 class="mb-3">customer Details</h5>
    <div class="row">

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>User Code <span class="text-danger">*</span></label>
            <input type="text" name="user_code" class="form-control shadow-none"
                value="{{ old('user_code', $business_user->user_code ?? '') }}" required>
            @error('user_code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Customer Name --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Name of Corp User <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $business_user->name ?? '') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Company <span class="text-danger">*</span></label>

            <select name="company_id[]" class="form-select search-select" multiple>
                @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ in_array(
                        $company->id,
                        old('company_id', $customerCompanies ?? [])
                    ) ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                @endforeach
            </select>

            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            @error('company_id.*')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Email --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Email <span class="text-danger">*</span>(Login User Name)</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', $business_user->email ?? '') }}">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Phone <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">+60</span>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $business_user->phone ?? '') }}">
            </div>
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Alternate Phone --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Alternate Phone</label>
            <div class="input-group">
                <span class="input-group-text">+60</span>
                <input type="text" name="alternate_phone" class="form-control"
                    value="{{ old('alternate_phone', $business_user->alternate_phone ?? '') }}">
            </div>
            @error('alternate_phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Whats App</label>
            <div class="input-group">
                <span class="input-group-text">+60</span>
                <input type="text" name="whats_app" class="form-control"
                    value="{{ old('whats_app', $business_user->whats_app ?? '') }}">
            </div>
            @error('whats_app')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- User Name --}}
        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>User Name <span class="text-danger">*</span></label>
            <input type="text" name="user_name" class="form-control"
                value="{{ old('user_name', $business_user->user_name ?? '') }}">
            @error('user_name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        {{-- Password --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Password
                {!! isset($business_user) ? '(Leave blank if not changing)' : '<span class="text-danger">*</span>' !!}
            </label>
            <div class="input-group">
                <input type="text" name="password" class="form-control" id="password">
                <button class="btn btn-outline-secondary d-none" type="button" id="togglePassword">
                    <i class="bi bi-eye" id="passwordIcon"></i>
                </button>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Confirm Password
                {!! isset($business_user) ? '(Leave blank if not changing)' : '<span class="text-danger">*</span>' !!}
            </label>
            <div class="input-group">
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                    <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                </button>
            </div>
        </div>

        {{-- Billing Address --}}
        {{-- <div class="col-lg-6 mb-3 o-f-inp">
            <label>Description</label>
            <textarea name="description" class="form-control"
                rows="4">{{ old('description', $business_user->description ?? '') }}</textarea>
            @error('description')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        {{-- Billing Address --}}
        <div class="col-lg-12 mb-3 o-f-inp d-none">
            <label>Billing Address <span class="text-danger">*</span></label>
            <textarea name="billing_address" class="form-control"
                rows="4">{{ old('billing_address', $business_user->billing_address ?? '') }}</textarea>
            @error('billing_address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Country --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Country <span class="text-danger">*</span></label>
            <select name="country" class="form-select search-select">
                <option value="">-- Select Country --</option>
                <option value="malaysia" {{ old('country', $business_user->country ?? '') == 'malaysia' ? 'selected' : '' }}>
                    Malaysia
                </option>
                {{-- <option value="india" {{ old('country', $business_user->country ?? '') == 'india' ? 'selected' : ''
                    }}>
                    India
                </option> --}}
            </select>
            @error('country')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- State --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>State <span class="text-danger">*</span></label>
            <select name="state_id" class="form-select search-select">
                <option value="">-- Select State --</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id', $business_user->state_id ?? '') == $state->id ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
            @error('state_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- City --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>City <span class="text-danger">*</span></label>
            <select name="city_id" class="form-select search-select">
                <option value="">-- Select City --</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ old('city_id', $business_user->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            @error('city_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- GST --}}
        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>GST/VAT Number<span class="text-danger">*</span></label>
            <input type="text" name="gst" class="form-control" value="{{ old('gst', $business_user->gst ?? '') }}">
            @error('gst')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        {{-- Tax ID --}}
        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>PAN / Tax ID</label>
            <input type="text" name="tax_id" class="form-control"
                value="{{ old('tax_id', $business_user->tax_id ?? '') }}">
            @error('tax_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        @if (isset($business_user))
            <div class="col-lg-12 ">
                <div class="list-of-company">
                    <label class="fw-bold mb-2">List of Companies involved:</label>

                    @php
                        $assignedCompanies = $companies->whereIn('id', old('companies', $customerCompanies ?? []));
                    @endphp

                    @if($assignedCompanies->count() > 0)
                        <ul class="company-list p-0 m-0">
                            @foreach($assignedCompanies as $company)
                                <li>{{ $company->company_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted fst-italic">No companies assigned.</p>
                    @endif
                </div>

            </div>
        @endif


    </div>
    <div class="btn-sub-box">
        <a href="{{ route('admin.business-users.index') }}" class="btn-back-cs">
            Back
        </a>
        <button type="submit" class="submit-btn">
            {{ isset($business_user) ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>