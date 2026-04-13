<style>
    .img-thumbnail {
        border: 1px dashed #1d2467;
    }
</style>

<form
    action="{{ isset($billerProfile) ? route('admin.biller-profiles.update', $billerProfile->id) : route('admin.biller-profiles.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @isset($billerProfile)
        @method('PUT')
    @endisset

    <h5 class="mb-3 container-title">Biller Profile</h5>

    <div class="row">

        {{-- Company --}}
        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Customer <span class="text-danger">*</span></label>
            <select name="company_id" class="form-select search-select">
                <option value="">-- Select Customer --</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" data-total-group="{{ $company->total_group_id }}"
                        {{ old('company_id', $billerProfile->company_id ?? '') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Total Group --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" class="form-select search-select" disabled>
                <option value="">-- Select Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}"
                        {{ old('total_group_id', $billerProfile->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="total_group_id" value="{{ $billerProfile->total_group_id ?? '' }}" />
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Address <span class="text-danger">*</span></label>
            <textarea name="address" class="form-control editor" rows="4">
                {{ old('address', $billerProfile->address ?? '') }}
            </textarea>
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-12">
            {{-- Work Order --}}
            <div class="card-cs mb-4">
                <div class="card-header-cs fw-semibold">1. Work Order</div>
                <div class="card-body-cs row">

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Header',
                        'name' => 'work_plan_header',
                        'model' => $billerProfile ?? null,
                    ])

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Footer',
                        'name' => 'work_plan_footer',
                        'model' => $billerProfile ?? null,
                    ])

                </div>
            </div>
        </div>
        <div class="col-lg-12">

            {{-- Quotation --}}
            <div class="card-cs mb-4">
                <div class="card-header-cs fw-semibold">2. Quotation</div>
                <div class="card-body-cs row">

                    {{-- T & C --}}
                    <div class="col-lg-12 mb-3">
                        <label>T & C <span class="text-danger">*</span></label>
                        <textarea name="quotation_tc" class="form-control editor" rows="4">
                {{ old('quotation_tc', $billerProfile->quotation_tc ?? '') }}
            </textarea>
                    </div>

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Header',
                        'name' => 'quotation_header',
                        'model' => $billerProfile ?? null,
                    ])

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Footer',
                        'name' => 'quotation_footer',
                        'model' => $billerProfile ?? null,
                    ])

                </div>
            </div>
        </div>
        {{-- Invoice --}}
        <div class="col-lg-12">

            <div class="card-cs mb-4">
                <div class="card-header-cs fw-semibold">3. Invoice</div>
                <div class="card-body-cs row">

                    {{-- Payment Terms --}}
                    <div class="col-lg-12 mb-3">
                        <label>Payment Terms <span class="text-danger">*</span></label>
                        <textarea name="invoice_payment_terms" class="form-control editor" rows="4">
                {{ old('invoice_payment_terms', $billerProfile->invoice_payment_terms ?? '') }}
            </textarea>
                    </div>

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Header',
                        'name' => 'invoice_header',
                        'model' => $billerProfile ?? null,
                    ])

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Footer',
                        'name' => 'invoice_footer',
                        'model' => $billerProfile ?? null,
                    ])

                </div>
            </div>
        </div>

        {{-- Receipts --}}
        <div class="col-lg-12">

            <div class="card-cs mb-4">
                <div class="card-header-cs fw-semibold">4. Receipts</div>
                <div class="card-body-cs row">

                    {{-- T & C --}}
                    <div class="col-lg-12 mb-3">
                        <label>T & C <span class="text-danger">*</span></label>
                        <textarea name="receipt_tc" class="form-control editor" rows="4">
                {{ old('receipt_tc', $billerProfile->receipt_tc ?? '') }}
            </textarea>
                    </div>

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Header',
                        'name' => 'receipt_header',
                        'model' => $billerProfile ?? null,
                    ])

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Footer',
                        'name' => 'receipt_footer',
                        'model' => $billerProfile ?? null,
                    ])

                </div>
            </div>
        </div>
          <div class="card-cs mb-4">
                <div class="card-header-cs fw-semibold">5. Report</div>
                <div class="card-body-cs row">

                    {{-- T & C --}}
                    <div class="col-lg-12 mb-3">
                        <label>T & C <span class="text-danger">*</span></label>
                        <textarea name="report_tc" class="form-control editor" rows="4">
                {{ old('report_tc', $billerProfile->report_tc ?? '') }}
            </textarea>
                    </div>

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Header',
                        'name' => 'report_header',
                        'model' => $billerProfile ?? null,
                    ])

                    @include('admin.biller-profile.partials.image-field', [
                        'label' => 'Footer',
                        'name' => 'report_footer',
                        'model' => $billerProfile ?? null,
                    ])

                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="btn-sub-box">
        <a href="{{ route('admin.biller-profiles.index') }}" class="btn-back-cs">Back</a>
        <button type="submit" class="submit-btn">
            {{ isset($billerProfile) ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
