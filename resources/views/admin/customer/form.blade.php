<form action="{{ isset($customer) ? route('admin.customers.update', $customer->id) : route('admin.customers.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($customer))
        @method('PUT')
    @endif

    <h5 class="mb-3">Total Group Details</h5>
    <div class="row">

        {{-- Company User --}}
        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Company User <span class="text-danger">*</span></label>
            <select name="user_id" class="form-select search-select">
                <option value="">-- Select User --</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $customer->user_id ?? '') == $user->id ? 'selected' :
                    '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('user_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Company</label>
            <select name="company_id" class="form-select search-select">
                <option value="">-- Select Company --</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $customer->company_id ?? '') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Customer Name --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="customer_name" class="form-control"
                value="{{ old('customer_name', $customer->customer_name ?? '') }}">
            @error('customer_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Email --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Phone <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">+60</span>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
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
                    value="{{ old('alternate_phone', $customer->alternate_phone ?? '') }}">
            </div>
            @error('alternate_phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Currency --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Currency <span class="text-danger">*</span></label>
            <select name="currency_id" class="form-select search-select">
                <option value="">-- Select Currency --</option>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{ old('currency_id', $customer->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                        {{ $currency->currency_code }} - {{ $currency->currency_name }}
                    </option>
                @endforeach
            </select>
            @error('currency_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- - --}}

        {{-- Billing Address --}}
        {{-- <div class="col-lg-6 mb-3 o-f-inp">
            <label>Billing Address <span class="text-danger">*</span></label>
            <textarea name="billing_address" class="form-control"
                rows="4">{{ old('billing_address', $customer->billing_address ?? '') }}</textarea>
            @error('billing_address')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        {{-- Shipping Address --}}
        {{-- <div class="col-lg-6 mb-3 o-f-inp">
            <label>Shipping Address</label>
            <textarea name="shipping_address" class="form-control"
                rows="4">{{ old('shipping_address', $customer->shipping_address ?? '') }}</textarea>
            @error('shipping_address')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        <div class="col-lg-12 mb-3 o-f-inp d-none">
            <label>Remarks </label>
            <textarea name="remarks" class="form-control"
                rows="4">{{ old('remarks', $customer->remarks ?? '') }}</textarea>
            @error('remarks')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Country --}}
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Country <span class="text-danger">*</span></label>
            <select name="country" class="form-select search-select">
                {{-- <option value="">-- Select Country --</option> --}}
                <option value="malaysia" {{ old('country', $customer->country ?? '') == 'malaysia' ? 'selected' : '' }}>
                    Malaysia
                </option>
                {{-- <option value="india" {{ old('country', $customer->country ?? '') == 'india' ? 'selected' : ''
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
            <label>State</label>
            <select name="state_id" class="form-select search-select">
                <option value="">-- Select State --</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id', $customer->state_id ?? '') == $state->id ? 'selected' : '' }}>
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
            <label>City </label>
            <select name="city_id" class="form-select search-select">
                <option value="">-- Select City --</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ old('city_id', $customer->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            @error('city_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- GST --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>TIN</label>
            <input type="text" name="gst" class="form-control" value="{{ old('gst', $customer->gst ?? '') }}">
            @error('gst')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Tax ID --}}
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Tax ID</label>
            <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $customer->tax_id ?? '') }}">
            @error('tax_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>TSS</label>
            <input type="text" name="tss" class="form-control" value="{{ old('tss', $customer->tss ?? '') }}">
            @error('tss')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Banner</label>
            <input type="text" name="banner" class="form-control" value="{{ old('banner', $customer->banner ?? '') }}">
            @error('banner')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Service Type</span>
                    <div>
                        <button type="button" id="select-all-types" class="btn btn-sm btn-primary me-2">Select
                            All</button>
                        <button type="button" id="deselect-all-types" class="btn btn-sm btn-secondary">Deselect
                            All</button>
                    </div>
                </div>
                <div class="card-body d-flex flex-wrap gap-3">
                    @php
                        $selectedTypes = old('company_type_id', isset($customer) ? json_decode($customer->company_type_id, true) : []);
                    @endphp

                    @foreach ($companyTypes as $companyType)
                        <div class="form-check">
                            <input class="form-check-input company-type-checkbox" type="checkbox" name="company_type_id[]"
                                value="{{ $companyType->id }}" id="company_type_{{ $companyType->id }}"
                                @if(in_array($companyType->id, $selectedTypes)) checked @endif>
                            <label class="form-check-label" for="company_type_{{ $companyType->id }}">
                                {{ $companyType->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('company_type_id')
                    <div class="card-footer text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label for="logo">Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="form-control shadow-none">
            <div class="mt-2">
                <img id="logo-preview"
                    src="{{ isset($customer) ? asset('storage/' . $customer->logo) : asset('assets/img/user.png') }}"
                    alt="Logo Preview" class="rounded-circle" width="80" height="80">
            </div>
            @error('logo')<span class="text-danger small">{{ $message }}</span>@enderror
        </div>

    </div>
    <div class="btn-sub-box">
        <a href="{{ route('admin.customers.index') }}" class="btn-back-cs">
            Back
        </a>
        <button type="submit" class="submit-btn">
            {{ isset($customer) ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>