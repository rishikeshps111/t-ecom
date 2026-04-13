@extends('admin.layouts.app')
@section('title')Planner Documents
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Edit Planner Document</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    @include('admin.planner-document.form')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        document.getElementById('documentFiles').addEventListener('change', function (e) {
            const preview = document.getElementById('filePreview');
            preview.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                const col = document.createElement('div');
                col.classList.add('col-md-3', 'mb-3');

                const card = document.createElement('div');
                card.classList.add('border', 'p-2', 'rounded', 'text-center');

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.classList.add('img-fluid', 'mb-2');
                    card.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.classList.add('bi', 'bi-file-earmark-text', 'fs-1');
                    card.appendChild(icon);
                }

                const name = document.createElement('div');
                name.classList.add('small', 'text-truncate');
                name.innerText = file.name;

                card.appendChild(name);
                col.appendChild(card);
                preview.appendChild(col);
            });
        });
    </script>
@endsection