<div class="planner-details">

    {{-- Planner Info --}}
    {{-- <div class="card mb-4">
        <div class="card-header bg-light text-white">
            <h5 class="mb-0" style="color: black;">{{ $plannerDocument->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6"><strong>Company:</strong> {{ $plannerDocument->company?->company_name ?? '-' }}
                </div>
                <div class="col-md-6"><strong>Status:</strong> {{ ucfirst($plannerDocument->status) }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Start Date:</strong>
                    {{ $plannerDocument->start_date?->format('d M, Y') ?? '-' }}</div>
                <div class="col-md-6"><strong>End Date:</strong>
                    {{ $plannerDocument->end_date?->format('d M, Y') ?? '-' }}</div>
                <div class="col-md-6 mt-2"><strong>Financial Year:</strong>
                    {{ $plannerDocument->financialYear->year ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-12"><strong>Description:</strong> {!! nl2br(e($plannerDocument->description)) !!}</div>
            </div>
        </div>
    </div> --}}

    {{-- Documents --}}
    <div class="card">
        <div class="card-header bg-light text-white">
            <h6 class="mb-0" style="color: black;">Documents</h6>
        </div>
        <div class="card-body">
            @if($plannerDocument->files->count())
                <div class="row g-3">
                    @foreach($plannerDocument->files as $file)
                        @php
                            $fileName = basename($file->document);
                            $fileUrl = asset($file->document);
                            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                        @endphp

                        <div class="col-md-3">
                            <div class="card h-100 text-center">
                                {{-- File preview --}}
                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ $fileUrl }}" class="card-img-top" style="height:150px; object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="height:150px;">
                                        <i class="bi bi-file-earmark-text fs-1"></i>
                                    </div>
                                @endif

                                {{-- File name --}}
                                <div class="card-body p-2 mb-2">
                                    <div class="small text-truncate" title="{{ $fileName }}">{{ $fileName }}</div>
                                </div>

                                {{-- Actions --}}
                                <div class="card-footer d-flex justify-content-center gap-2 p-2">
                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ $fileUrl }}" download class="btn btn-sm btn-success">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mb-0"><em>No documents uploaded.</em></p>
            @endif
        </div>
    </div>
</div>