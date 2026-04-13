@extends('admin.layouts.app')
@section('title')
    Planner Documents
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Add Planner Document</h3>
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
            preview.innerHTML = ''; // clear previous preview

            Array.from(e.target.files).forEach((file, index) => {
                const col = document.createElement('div');
                col.classList.add('col-md-3', 'mb-3');

                const card = document.createElement('div');
                card.classList.add('border', 'p-2', 'rounded', 'text-center');

                // Preview: Image or Icon
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.classList.add('img-fluid', 'mb-2');
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    card.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.classList.add('bi', 'bi-file-earmark-text', 'fs-1', 'mb-2');
                    card.appendChild(icon);
                }

                // File name
                const name = document.createElement('div');
                name.classList.add('small', 'text-truncate', 'mb-1');
                name.innerText = file.name;
                card.appendChild(name);

                // File type select
                const typeSelect = document.createElement('select');
                typeSelect.name = 'document_types[]';
                typeSelect.classList.add('form-select', 'form-select-sm');

                // Determine default type
                const ext = file.name.split('.').pop().toLowerCase();
                const typeOptions = {
                    'Image': ['jpg', 'jpeg', 'png', 'gif'].includes(ext),
                    'PDF': ext === 'pdf',
                    'Word': ['doc', 'docx'].includes(ext),
                    'Excel': ['xls', 'xlsx'].includes(ext),
                    'Other': !['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(ext)
                };

                for (let label in typeOptions) {
                    const option = document.createElement('option');
                    option.value = label;
                    option.text = label;
                    if (typeOptions[label]) option.selected = true;
                    typeSelect.appendChild(option);
                }

                card.appendChild(typeSelect);

                col.appendChild(card);
                preview.appendChild(col);
            });
        });
    </script>

@endsection