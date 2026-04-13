@extends('admin.layouts.app')
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3 class="d-flex justify-content-between align-items-center">Planner <span>Add</span></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <form action="{{ route('admin.planner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <input type="hidden" name="planner_code" value="{{ old('planner_code', $userId) }}">
                                <label>User Code <span class="text-danger">*</span></label>
                                <input type="text" name="user_code" class="form-control shadow-none"
                                    value="{{ old('user_code') }}" required>
                                @error('user_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('planner_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control shadow-none"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Email / User Name</label>
                                <input type="text" name="email" class="form-control shadow-none"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3 o-f-inp">
                                <label class="form-label label-between">Select Total Groups <span
                                        class="text-danger">*</span> <button type="button" id="checkAllBtn"
                                        class="btn btn-sm btn-primary">Check
                                        All</button></label>


                                <!-- Scrollable checkbox container -->
                                <div class="container-check-box" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($totalGroups as $customer)
                                        <div class="form-check">
                                            <input type="checkbox" name="total_groups[]" value="{{ $customer->id }}"
                                                class="total-group-checkbox" id="customer_{{ $customer->id }}"
                                                {{ collect(old('total_groups'))->contains($customer->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="customer_{{ $customer->id }}">
                                                {{ $customer->customer_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @error('total_groups')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp d-none">
                                <label for="companies">Select Customers <span class="text-danger">*</span></label>
                                <select name="companies[]" id="companies" class="form-select shadow-none search-select"
                                    multiple>
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


                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Mobile Number </label>
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

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Sequence No <span class="text-danger">*</span></label>
                                <input type="number" name="sequence_number" class="form-control shadow-none"
                                    value="{{ old('sequence_number') }}">
                                @error('sequence_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp d-none">
                                <label>IV % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="iv" class="form-control shadow-none"
                                    value="{{ old('iv') }}">
                                @error('iv')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Planner C % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="planner_c_percentage"
                                    value="{{ old('planner_c_percentage') }}" class="form-control shadow-none">
                                @error('planner_c_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Password
                                    {!! isset($business_user) ? '(Leave blank if not changing)' : '<span class="text-danger">*</span>' !!}
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
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
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="password_confirmation">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

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
        // Toggle password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const passwordIcon = document.querySelector('#passwordIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            passwordIcon.classList.toggle('bi-eye');
            passwordIcon.classList.toggle('bi-eye-slash');
        });

        // Toggle confirm password
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#password_confirmation');
        const confirmPasswordIcon = document.querySelector('#confirmPasswordIcon');

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            confirmPasswordIcon.classList.toggle('bi-eye');
            confirmPasswordIcon.classList.toggle('bi-eye-slash');
        });
    </script>
    <script>
        const checkAllBtn = document.getElementById('checkAllBtn');
        checkAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.total-group-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Check All' : 'Uncheck All';
        });
    </script>
@endsection
