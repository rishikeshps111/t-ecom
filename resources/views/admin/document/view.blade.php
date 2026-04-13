<div class="planner-details">

    {{-- Planner Info --}}
    <div class="card mb-4">
        <div class="card-header bg-light text-white">
            <h5 class="mb-0" style="color: black;">{{ $document->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6"><strong>Company:</strong> {{ $document->company?->company_name ?? '-' }}
                </div>
                <div class="col-md-6"><strong>Status:</strong> {{ ucfirst($document->status) }}</div>


            </div>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Document Issued:</strong>
                    {{ $document->valid_from?->format('d M, Y') ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Document Expiry:</strong>
                    {{ $document->valid_to?->format('d M, Y') ?? 'N/A' }}</div>
                <div class="col-md-6 mt-2"><strong>Financial Year:</strong> {{ $document->financialYear->year ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light text-white">
            <h6 class="mb-0" style="color: black;">Document</h6>
        </div>
        <div class="card-body">
            @if($document && $document->document)
                @php
                    $fileName = basename($document->document);
                    $fileUrl = asset('storage/' . $document->document);
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                @endphp
                <div class="card text-center" style="width: 200px; margin:auto;">
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ $fileUrl }}" class="card-img-top" style="height:150px; object-fit:cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height:150px;">
                            <i class="bi bi-file-earmark-text fs-1"></i>
                        </div>
                    @endif
                    <div class="card-body p-2 mb-2">
                        <div class="small text-truncate" title="{{ $fileName }}">{{ $fileName }}</div>
                    </div>
                    <div class="card-footer d-flex justify-content-center gap-2 p-2">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ $fileUrl }}" download class="btn btn-sm btn-success">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            @else
                <p><em>No document uploaded.</em></p>
            @endif
        </div>
    </div>
</div>