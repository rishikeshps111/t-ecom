<div class="card">

    {{-- Body --}}
    <div class="card-body">

        {{-- Subject --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Subject:</label>
            <div>{{ $companyMessage->subject ?? '-' }}</div>
        </div>

        {{-- Message --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Message:</label>
            <div style="white-space: pre-wrap;">{{ $companyMessage->message ?? '-' }}</div>
        </div>

        @php
            $priorityColor = match (strtolower($companyMessage->priority ?? '')) {
                'high' => 'danger',   // Red
                'medium' => 'warning', // Orange
                'low' => 'success',   // Green
                default => 'secondary',
            };
        @endphp

        {{-- Priority --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Priority:</label>
            <div>
                <span class="badge bg-{{ $priorityColor }}">
                    {{ ucfirst($companyMessage->priority ?? 'N/A') }}
                </span>
            </div>
        </div>

        {{-- Sent Date --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Sent At:</label>
            <div>{{ $companyMessage->created_at->format('d M, Y H:i') }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>