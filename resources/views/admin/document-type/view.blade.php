<div class="card">

    {{-- Body --}}
    <div class="card-body">

        {{-- Recipient --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Recipient:</label>
            <div>{{ $message->user ? $message->user->name . ' (' . $message->user->email . ')' : 'N/A' }}</div>
        </div>

        {{-- Subject --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Subject:</label>
            <div>{{ $message->subject ?? '-' }}</div>
        </div>

        {{-- Message --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Message:</label>
            <div style="white-space: pre-wrap;">{{ $message->message ?? '-' }}</div>
        </div>

        @php
            $priorityColor = match (strtolower($message->priority ?? '')) {
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
                    {{ ucfirst($message->priority ?? 'N/A') }}
                </span>
            </div>
        </div>



        {{-- Sent Date --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Sent At:</label>
            <div>{{ $message->created_at->format('d M, Y H:i') }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>