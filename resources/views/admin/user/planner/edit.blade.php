@extends('admin.layouts.app')
@section('style')
    @include('admin.scripts.css')
     <style>
        .list-of-company {
            max-height: 250px !important;
            overflow-y: auto !important;
        }
    </style>
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3 class="d-flex justify-content-between align-items-center">Planner <span>Edit</span></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <form action="{{ route('admin.planner.update', $planner->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                           <div class="col-lg-4 mb-3 o-f-inp">
                                    <input type="hidden" name="planner_code" value="{{ old('planner_code', $planner->user_id) }}">
                                      <label>User Code <span class="text-danger">*</span></label>
                                    <input type="text" name="user_code" class="form-control shadow-none"
                                        value="{{ old('user_code',$planner->user_code) }}" required>
                                    @error('user_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control shadow-none"
                                    value="{{ old('name', $planner->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control shadow-none"
                                    value="{{ old('email', $planner->email) }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3 o-f-inp">
                                <label class="form-label label-between">Select Total Groups   <button type="button" id="checkAllGroupsBtn" class="btn btn-sm btn-primary">Check
                                        All</button></label>
                                

                                <!-- Scrollable checkbox container -->
                                <div class="container-check-box" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($totalGroups as $customer)
                                        <div class="form-check">
                                            <input type="checkbox" name="total_groups[]" value="{{ $customer->id }}"
                                                class="total-group-checkbox" id="customer_{{ $customer->id }}"
                                                {{ in_array($customer->id, old('total_groups', $selectedGroups ?? [])) ? 'checked' : '' }}>
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
                                <label for="companies">Select Customers</label>
                                <select name="companies[]" id="companies" class="form-select shadow-none search-select"
                                    multiple>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ in_array($company->id, old('companies', $selectedCompanies)) ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('companies')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Mobile Number</label>
                                <input type="hidden" name="country_code" value="+60">
                                <div class="input-group">
                                    <span class="input-group-text">+60</span>
                                    <input type="text" name="phone" class="form-control shadow-none"
                                        placeholder="Enter mobile number" maxlength="10"
                                        value="{{ old('phone', $planner->phone) }}"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Department <span class="text-danger">*</span></label>
                                <input type="text" name="department" class="form-control shadow-none"
                                    value="{{ old('department', $planner->department) }}">
                                @error('department')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <input type="hidden" name="role" value="5" />

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control shadow-none"
                                    value="{{ old('designation', $planner->designation) }}">
                                @error('designation')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Task Assignment Access</label>
                                <select name="task_access" class="form-select shadow-none">
                                    <option value="">--- Select ---</option>
                                    <option value="yes" {{ old('task_access', $planner->task_access) == 'yes' ?
                                        'selected' :
                                        '' }}>Yes</option>
                                    <option value="no" {{ old('task_access', $planner->task_access) == 'no' ? 'selected'
                                        :
                                        '' }}>No</option>
                                </select>
                                @error('task_access')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Document Upload Permission</label>
                                <select name="document_upload" class="form-select shadow-none">
                                    <option value="">--- Select ---</option>
                                    <option value="yes" {{ old('document_upload', $planner->document_upload) == 'yes' ?
                                        'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('document_upload', $planner->document_upload) == 'no' ?
                                        'selected' : '' }}>No</option>
                                </select>
                                @error('document_upload')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Document Edit Permission</label>
                                <select name="document_edit" class="form-select shadow-none">
                                    <option value="">--- Select ---</option>
                                    <option value="yes" {{ old('document_edit', $planner->document_edit) == 'yes' ?
                                        'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('document_edit', $planner->document_edit) == 'no' ?
                                        'selected'
                                        : '' }}>No</option>
                                </select>
                                @error('document_edit')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Project / Task Scope</label>
                                <input type="text" name="task_scope" class="form-control shadow-none"
                                    value="{{ old('task_scope', $planner->task_scope) }}">
                                @error('task_scope')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div> --}}
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Joining Date <span class="text-danger">*</span></label>
                                <input type="date" name="joining_date" class="form-control shadow-none"
                                    value="{{ old('joining_date', isset($planner->joining_date) ? $planner->joining_date->format('Y-m-d') : '') }}">
                                @error('joining_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Relieving Date <span class="text-danger">*</span></label>
                                <input type="date" name="relived_at" class="form-control shadow-none"
                                    value="{{ old('relived_at', isset($planner->relived_at) ? $planner->relived_at->format('Y-m-d') : '') }}">
                                @error('relived_at')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Sequence No <span class="text-danger">*</span></label>
                                <input type="number" name="sequence_number" class="form-control shadow-none"
                                    value="{{ old('sequence_number', $planner->sequence_number) }}">
                                @error('sequence_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp d-none">
                                <label>IV % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="iv" class="form-control shadow-none"
                                    value="{{ old('iv', $planner->iv) }}">
                                @error('iv')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Planner C % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="planner_c_percentage"
                                    value="{{ old('planner_c_percentage',$planner->planner_c_percentage) }}" class="form-control shadow-none">
                                @error('planner_c_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Password <small class="text-muted">(Leave blank if not changing)</small>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control shadow-none">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp d-none">
                                <label>
                                    Confirm Password <small class="text-muted">(Leave blank if not changing)</small>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control shadow-none">
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
                                    <option value="1" {{ old('status', $planner->status) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('status', $planner->status) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                    <div class="list-of-company">
                                        <label class="fw-bold mb-2">List of Customers involved:</label>
                                        @if ($planner->customers->count() > 0)
                                            <ul class="company-list p-0 m-0">
                                                @foreach ($planner->customers as $company)
                                                    <li>{{ $company->company_name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fst-italic">No Customers assigned.</p>
                                        @endif
                                    </div>
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
        const checkAllGroupsBtn = document.getElementById('checkAllGroupsBtn');
        checkAllGroupsBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.total-group-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Check All' : 'Uncheck All';
        });
    </script>
@endsection
