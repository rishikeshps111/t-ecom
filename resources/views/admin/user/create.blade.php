@extends('admin.layouts.app')
@section('style')
    @include('admin.scripts.css')
    <style>
        .select2-selection {
            max-height: 120px !important;
            overflow-y: auto !important;
        }

        .list-of-company {
            max-height: 120px !important;
            overflow-y: auto !important;
        }
    </style>
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3 class="d-flex justify-content-between align-items-center">
                        @if (request()->get('type') == 'production')
                            Production
                        @else
                            Management
                        @endif
                        Staff Management <span>Add</span>
                    </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- @if (request()->get('type') == 'management')
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Staff Id <span class="text-danger">*</span></label>
                                    <input type="text" name="user_id" class="form-control shadow-none" readonly
                                        value="{{ old('user_id', $userId) }}">
                                    @error('user_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @else --}}
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <input type="hidden" name="user_id" value="{{ old('user_id', $userId) }}">
                                      <label>User Code <span class="text-danger">*</span></label>
                                    <input type="text" name="user_code" class="form-control shadow-none"
                                        value="{{ old('user_code') }}" required>
                                    @error('user_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            {{-- @endif --}}

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control shadow-none"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" name="email" class="form-control shadow-none"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="hidden" name="country_code" value="+60">
                                <div class="input-group">
                                    <span class="input-group-text">+60</span>
                                    <input type="text" name="phone" class="form-control shadow-none"
                                        placeholder="Enter mobile number" maxlength="10" value="{{ old('phone') }}"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @if (request()->get('type') == 'management')
                                <div class="col-lg-4 mb-3 o-f-inp d-none">
                                    <label for="">Role <span class="text-danger">*</span> </label>
                                    <select name="role" id="role" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Date of Joining <span class="text-danger">*</span></label>
                                <input type="date" name="joining_date" class="form-control shadow-none"
                                    value="{{ old('joining_date') }}">
                                @error('joining_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Relieving Date <span class="text-danger">*</span></label>
                                <input type="date" name="relived_at" class="form-control shadow-none"
                                    value="{{ old('relived_at') }}">
                                @error('relived_at')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <input type="hidden" name="user_type" value="{{ request()->get('type', 'production') }}">

                            {{-- Password --}}
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="text" name="password" class="form-control shadow-none">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @if (request()->get('type') == 'production')
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Production C % <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="production_c_percentage"
                                        value="{{ old('production_c_percentage') }}" class="form-control shadow-none">
                                    @error('production_c_percentage')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp d-none">
                                    <label>Planner C % <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="planner_c_percentage"
                                        value="{{ old('planner_c_percentage') }}" class="form-control shadow-none">
                                    @error('planner_c_percentage')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            @if (request()->get('type') == 'management')
                                <div class="col-lg-12 mb-3 o-f-inp">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="companies" class="mb-0">Select Customers</label>
                                        <button type="button" id="toggleCompanies"
                                            class="btn btn-sm btn-outline-primary">
                                            Select All
                                        </button>
                                    </div>

                                    <select name="companies[]" id="companies"
                                        class="form-select shadow-none search-select scrollable-select" multiple>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ collect(old('companies'))->contains($company->id) ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('companies')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-lg-12 mb-3 o-f-inp">
                                <label class="form-label label-between">Select Total Groups <button type="button"
                                        id="checkAllBtn" class="btn btn-sm btn-primary">Check All</button></label>


                                <!-- Scrollable container -->
                                <div class="container-check-box" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($customers as $customer)
                                        <div class="form-check">
                                            <input type="checkbox" name="total_groups[]" value="{{ $customer->id }}"
                                                class="pt-2 total-group-checkbox" id="customer_{{ $customer->id }}"
                                                {{ in_array($customer->id, old('total_groups', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label " for="customer_{{ $customer->id }}">
                                                {{ $customer->customer_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @error('total_groups')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            {{-- <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control shadow-none">
                                @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div> --}}

                            <div class="col-lg-4 mb-3 o-f-inp d-none">
                                <label>Status</label>
                                <select name="status" class="form-select shadow-none">
                                    {{-- <option value="">--- Select ---</option> --}}
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Profile Image </label>
                                <input type="file" name="profile_image" class="form-control shadow-none">
                            </div> --}}
                            <div class="col-lg-12">
                                <button type="submit" class="submit-btn">Submit</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>
@endsection
@section('scripts')
    @include('admin.scripts.script')
    <script>
        const checkAllBtn = document.getElementById('checkAllBtn');
        checkAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.total-group-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Check All' : 'Uncheck All';
        });
    </script>
    <script>
        document.getElementById('toggleCompanies')?.addEventListener('click', function() {
            const select = document.getElementById('companies');
            const options = select.options;
            const allSelected = Array.from(options).every(opt => opt.selected);

            for (let i = 0; i < options.length; i++) {
                options[i].selected = !allSelected;
            }

            this.textContent = allSelected ? 'Select All' : 'Deselect All';

            // If using Select2 / Choices / other plugins
            $(select).trigger('change');
        });
    </script>
@endsection
