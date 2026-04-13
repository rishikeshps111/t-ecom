@extends('admin.layouts.app')
@section('title')
    Biller Profile
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Add Biller Profiler</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    @include('admin.biller-profile.form')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        document.querySelectorAll('.image-input').forEach(input => {
            input.addEventListener('change', function () {
                const previewId = this.dataset.preview;
                const previewImg = document.getElementById(previewId);

                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
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