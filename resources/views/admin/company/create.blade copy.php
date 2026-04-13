@extends('admin.layouts.app')
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Company Management <span>Add </span></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <form action="{{ route('admin.company.store') }}" method="POST">
                        @csrf

                        {{-- STEP 1 --}}
                        <div class="step step-1">
                            <h5 class="mb-3">1. Company Basic Information</h5>

                            <div class="row">
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Company Id <span class="text-danger">*</span></label>
                                    <input type="text" name="company_code" class="form-control" value="{{$code}}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Company Type</label>
                                    <select name="company_type" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="Sdn Bhd">Sdn Bhd</option>
                                        <option value="Bhd">Bhd</option>
                                        <option value="Enterprise">Enterprise</option>
                                        <option value="LLP">LLP</option>
                                        <option value="Partnership">Partnership</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Proposed Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control" required>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Alternative Company Name</label>
                                    <input type="text" name="alt_company_name" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Business Nature / Industry</label>
                                    <input type="text" name="industry" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Category</label>
                                    <select name="category_id" id="category" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>SubCategory</label>
                                    <select name="sub_category_id" id="sub_category" class="form-select">
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Planner</label>
                                    <select name="planner_id" id="planner" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach ($planners as $planner)
                                            <option value="{{ $planner->id }}">{{ $planner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Planner Code</label>
                                    <input type="text" name="planner_code" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="draft">Draft</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-3 o-f-inp">
                                    <label>Business Description </label>
                                    <textarea name="description" id="description_editor" class="form-control"
                                        rows="5"></textarea>
                                </div>


                            </div>

                            <button type="button" class="btn btn-success next">Next</button>
                        </div>

                        {{-- STEP 2 --}}
                        <div class="step step-2 d-none">
                            <h5 class="mb-3">2. Registration Details</h5>

                            <div class="row">
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>SSM Registration No</label>
                                    <input type="text" name="ssm_number" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Date of Incorporation</label>
                                    <input type="date" name="incorporation_date" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Business Commencement Date</label>
                                    <input type="date" name="commencement_date" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Paid-up Capital</label>
                                    <input type="number" name="paid_up_capital" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Authorized Capital</label>
                                    <input type="number" name="authorized_capital" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>No. of Employees</label>
                                    <input type="number" name="employees" class="form-control">
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="button" class="btn btn-success next">Next</button>
                        </div>

                        {{-- STEP 3 --}}
                        <div class="step step-3 d-none">
                            <h5 class="mb-3">3. Registered Office Address</h5>

                            <div class="row">
                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Address Line 1</label>
                                    <input type="text" name="address1" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Address Line 2</label>
                                    <input type="text" name="address2" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>State</label>
                                    <select name="state" id="state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state', $company->address->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>City</label>
                                    <select name="city" id="city" class="form-control">
                                        <option value="">Select City</option>
                                        {{-- Loaded via AJAX --}}
                                    </select>
                                </div>


                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Postcode</label>
                                    <input type="text" name="postcode" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Country</label>
                                    <select class="form-control" disabled>
                                        <option selected>Malaysia</option>
                                    </select>
                                    <input type="hidden" name="country" value="Malaysia">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Office Phone</label>
                                    <input type="text" name="office_phone" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Office Email</label>
                                    <input type="email" name="office_email" class="form-control">
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="button" class="btn btn-success next">Next</button>
                        </div>
                        {{-- STEP 4 --}}
                        <div class="step step-4 d-none">
                            <h5 class="mb-3">4.Business Address (if different)</h5>

                            <div class="row">
                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Address Line 1 </label>
                                    <input type="text" name="business_address1" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Address Line 2</label>
                                    <input type="text" name="business_address2" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>State</label>
                                    <select name="business_state" id="business_state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>City</label>
                                    <select name="business_city" id="business_city" class="form-control">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Postcode </label>
                                    <input type="text" name="business_postcode" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Country</label>
                                    <select class="form-control" disabled>
                                        <option selected>Malaysia</option>
                                    </select>
                                    <input type="hidden" name="business_country" value="Malaysia">
                                </div>

                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="button" class="btn btn-success next">Next</button>
                        </div>

                        {{-- STEP 5 --}}
                        <div class="step step-5 d-none">
                            <h5 class="mb-3">5.Contact Information</h5>

                            <div class="row">
                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Primary Contact Name </label>
                                    <input type="text" name="primary_contact_name" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Designation </label>
                                    <input type="text" name="designation" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Mobile Number </label>
                                    <input type="text" name="mobile_no" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Email Address </label>
                                    <input type="text" name="email_address" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Company Website (optional) </label>
                                    <input type="text" name="company_website" class="form-control">
                                </div>

                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="button" class="btn btn-success next">Next</button>
                        </div>

                        {{-- STEP 6 --}}
                        <div class="step step-6 d-none">
                            <h5 class="mb-3">6.Director(s) Information</h5>

                            <div class="row">
                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Full Name (as per IC/Passport) </label>
                                    <input type="text" name="director_name" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Identification Type (MyKad / Passport)</label>
                                    <input type="text" name="identification_type" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Identification Number</label>
                                    <input type="text" name="identification_number" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Nationality</label>
                                    <input type="text" name="director_nationality" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Date of Birth</label>
                                    <input type="date" name="director_date" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Residential Address </label>
                                    <input type="text" name="director_address" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Email Address</label>
                                    <input type="text" name="director_email" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Mobile Number</label>
                                    <input type="text" name="director_mobile" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Director Position </label>
                                    <input type="text" name="director_position" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Appointment Date </label>
                                    <input type="date" name="director_appointment" class="form-control">
                                </div>

                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="button" class="btn btn-success next">Next</button>
                        </div>

                        {{-- STEP 7 --}}
                        <div class="step step-7 d-none">
                            <h5 class="mb-3">7.Shareholder(s) Information</h5>

                            <div class="row">
                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Shareholder Type</label>
                                    <select name="shareholder_type" class="form-select">
                                        <option value="">Select</option>
                                        <option value="individual">Individual</option>
                                        <option value="corporate">Corporate</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3 o-f-inp">
                                    <label>Full Name / Company Name</label>
                                    <input type="text" name="shareholder_name" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Identification / Registration Number</label>
                                    <input type="text" name="shareholder_identification" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Nationality / Country of Incorporation</label>
                                    <input type="text" name="shareholder_nationality" class="form-control">
                                </div>

                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Number of Shares</label>
                                    <input type="number" name="shareholder_shares" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Percentage of Ownership </label>
                                    <input type="text" name="shareholder_ownership" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-3 o-f-inp">
                                    <label>Share Class (if applicable)</label>
                                    <input type="text" name="shareholder_class" class="form-control">
                                </div>

                            </div>

                            <button type="button" class="btn btn-secondary prev">Previous</button>
                            <button type="submit" class="btn btn-success">Submit Company</button>
                        </div>


                    </form>


                </div>
            </div>
        </div>

    </section>

@endsection


@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description_editor'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.step').length;

            function showStep(step) {
                document.querySelectorAll('.step').forEach((el, index) => {
                    el.classList.toggle('d-none', index + 1 !== step);
                });
            }

            function validateStep(step) {
                let isValid = true;
                const stepDiv = document.querySelector('.step-' + step);
                const requiredFields = stepDiv.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                return isValid;
            }

            document.querySelectorAll('.next').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (validateStep(currentStep)) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll('.prev').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentStep--;
                    showStep(currentStep);
                });
            });

        });
    </script>

    <script>
        $(document).ready(function () {

            $('#category').on('change', function () {
                let categoryId = $(this).val();
                let subCategory = $('#sub_category');

                subCategory.html('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: '/get-subcategories/' + categoryId,
                        type: 'GET',
                        success: function (data) {
                            subCategory.empty();
                            subCategory.append('<option value="">-- Select --</option>');

                            $.each(data, function (key, value) {
                                subCategory.append(
                                    '<option value="' + value.id + '">' + value.name + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    subCategory.html('<option value="">-- Select --</option>');
                }
            });

        });
    </script>
    <script>
        $(document).ready(function () {

            function loadCities(stateId, selectedCity = null) {
                let citySelect = $('#city');
                citySelect.html('<option value="">Loading...</option>');

                if (!stateId) {
                    citySelect.html('<option value="">Select City</option>');
                    return;
                }

                $.ajax({
                    url: '/get-locations/' + stateId,
                    type: 'GET',
                    success: function (data) {
                        citySelect.empty();
                        citySelect.append('<option value="">Select City</option>');

                        $.each(data, function (key, value) {
                            let selected = selectedCity == value.id ? 'selected' : '';
                            citySelect.append(
                                '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>'
                            );
                        });
                    }
                });
            }

            // On state change
            $('#state').on('change', function () {
                loadCities($(this).val());
            });

            // EDIT PAGE AUTO LOAD
            let editState = $('#state').val();
            let editCity = "{{ old('city', $company->address->city_id ?? '') }}";

            if (editState) {
                loadCities(editState, editCity);
            }

        });
    </script>

    <script>
        $(document).ready(function () {

            function loadCitiesBusiness(stateId, selectedCity = null) {
                let citySelect = $('#business_city');
                citySelect.html('<option value="">Loading...</option>');

                if (!stateId) {
                    citySelect.html('<option value="">Select City</option>');
                    return;
                }

                $.ajax({
                    url: '/get-locations/' + stateId,
                    type: 'GET',
                    success: function (data) {
                        citySelect.empty();
                        citySelect.append('<option value="">Select City</option>');

                        $.each(data, function (key, value) {
                            let selected = selectedCity == value.id ? 'selected' : '';
                            citySelect.append(
                                '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>'
                            );
                        });
                    }
                });
            }

            // On state change
            $('#business_state').on('change', function () {
                loadCitiesBusiness($(this).val());
            });

            // EDIT PAGE AUTO LOAD
            let editStateBusiness = $('#business_state').val();
            let editCityBusiness = "{{ old('city', $company->address->business_city_id ?? '') }}";

            if (editStateBusiness) {
                loadCitiesBusiness(editStateBusiness, editCityBusiness);
            }

        });
    </script>


@endsection