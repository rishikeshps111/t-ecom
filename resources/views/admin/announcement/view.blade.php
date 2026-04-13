<div class="modal-card-cs-announse">

    {{-- Body --}}
    <div class="row">

        {{-- Type --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel">
                <label class="form-label fw-bold">Type:</label>
                <span class="badge bg-{{ $announcement->type === 'public' ? 'primary' : 'secondary' }}">
                    {{ ucfirst($announcement->type ?? 'N/A') }}
                </span>
            </div>
        </div>

        {{-- Recipients --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel">
                <label class="form-label fw-bold">Recipients:</label>
                @if($announcement->type === 'public')
                    All Customers
                @elseif($announcement->users && $announcement->users->count())
                    @foreach($announcement->users as $user)
                        <span class="d-block">{{ $user->name }} ({{ $user->email }})</span>
                    @endforeach
                @else
                    N/A
                @endif
            </div>
        </div>

        {{-- Subject --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel"><label class="form-label fw-bold">Subject:</label>
                {{ $announcement->subject ?? '-' }}</div>
        </div>

        {{-- Message --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel">
                <label class="form-label fw-bold">Message:</label>
                {{ $announcement->message ?? '-' }}
            </div>
        </div>

        @php
            $priorityColor = match (strtolower($announcement->priority ?? '')) {
                'high' => 'danger',    // Red
                'medium' => 'warning', // Orange
                'low' => 'success',    // Green
                default => 'secondary',
            };
        @endphp

        {{-- Priority --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel">
                <label class="form-label fw-bold">Priority:</label>
                <span class="badge bg-{{ $priorityColor }}">
                    {{ ucfirst($announcement->priority ?? 'N/A') }}
                </span>
            </div>
        </div>

        {{-- Sent Date --}}
        <div class="mb-3 col-lg-6">

            <div class="md-dt-panel"> <label class="form-label fw-bold">Sent At:</label>
                {{ $announcement->created_at->format('d M, Y H:i') }}</div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="">
        <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
    </div>
</div>