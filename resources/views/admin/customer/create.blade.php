@extends('admin.layouts.app')
@section('title')
    Total Group Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Add Total Group</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    @include('admin.customer.form')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        document.getElementById('logo').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('logo-preview').src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
    <script>
        document.getElementById('select-all-types').addEventListener('click', function () {
            document.querySelectorAll('.company-type-checkbox').forEach(function (cb) {
                cb.checked = true;
            });
        });

        document.getElementById('deselect-all-types').addEventListener('click', function () {
            document.querySelectorAll('.company-type-checkbox').forEach(function (cb) {
                cb.checked = false;
            });
        });
    </script>
@endsection