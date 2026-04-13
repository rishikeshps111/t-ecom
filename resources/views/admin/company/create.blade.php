@extends('admin.layouts.app')
@section('title')
    Customer Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Add Customer </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    @include('admin.company.form')
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @include('admin.scripts.script')
    <script>
        $(document).ready(function () {

            function loadCities(stateId, selectedCity = null) {
                let citySelect = $('#city_id');
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
            $('#state_id').on('change', function () {
                loadCities($(this).val());
            });

            // EDIT PAGE AUTO LOAD
            let editState = $('#state_id').val();
            let editCity = "{{ old('city_id', $company->address->city_id ?? '') }}";

            if (editState) {
                loadCities(editState, editCity);
            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.editor').forEach((el) => {
                ClassicEditor
                    .create(el, {
                        toolbar: [
                            'heading',
                            '|',
                            'bold', 'italic', 'underline',
                            '|',
                            'bulletedList', 'numberedList',
                            '|',
                            'link',
                            '|',
                            'undo', 'redo'
                        ]
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection