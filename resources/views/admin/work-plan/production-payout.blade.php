<form id="commonFormTwo" class="row" method="POST" action="{{ route('admin.work-orders.production.payout.store') }}">
    @csrf

    <input type="hidden" name="id" value="{{ $record->id }}">

    @php
        $amountToPay = $record->amount * ($record->invoice->quotation->workPlan->productionStaff->production_c_percentage / 100);
    @endphp

    <div class="modal-card-cs-announse">
        <div class="row">
            <input type="hidden" id="base_amount" value="{{ $record->amount }}">

            <!-- Receipt details (unchanged) -->

            <div class="mb-3 col-lg-6">
                <div class="md-dt-panel">
                    <label class="form-label fw-bold">Payout Amount</label>
                    <input type="text" class="form-control shadow-none" value="{{ number_format($amountToPay, 2) }} MYR"
                        id="amount_display" readonly>
                    <input type="hidden" id="amount" name="amount" value="{{ $amountToPay }}">
                </div>
            </div>



            <!-- Paid date -->
            <div class="mb-3 col-lg-6">
                <div class="md-dt-panel">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" class="form-control shadow-none" name="paid_date"
                        value="{{ now()->toDateString() }}" readonly>
                </div>
            </div>

            <div class="mb-3 col-lg-12">
                <div class="md-dt-panel">
                    <label class="form-label fw-bold">
                        Payment Method <span class="text-danger">*</span>
                    </label>
                    <select name="payment_method" class="form-control shadow-none" required>
                        <option value="">Select Payment Method</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 col-lg-12">
                <div class="md-dt-panel">
                    <label class="form-label fw-bold">
                        Production Staff <span class="text-danger">*</span>
                    </label>
                    <select name="production_staff_id" id="production_staff_id" class="form-control shadow-none"
                        required>
                        <option value="">Select Production Staff</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ old('production_staff_id', $record->invoice->quotation->workPlan->production_staff_id ?? '') == $staff->id ? 'selected' : '' }}
                             data-percentage="{{ $staff->production_c_percentage ?? 0 }}">
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Paid checkbox -->
            <div class="mb-3 col-lg-6 d-flex align-items-end">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" id="is_paid" name="is_paid" value="1" required>
                    <label class="form-check-label fw-bold" for="is_paid">
                        Mark as Paid
                    </label>
                </div>
            </div>

            <!-- Remarks -->
            <div class="mb-3 col-lg-12">
                <div class="md-dt-panel">
                    <label class="form-label fw-bold">Remarks</label>
                    <textarea name="remarks" class="form-control shadow-none" rows="3"
                        placeholder="Enter remarks (optional)"></textarea>
                </div>
            </div>

        </div>
    </div>

    <div class="btn-sub-box">
        <button type="submit" class="submit-btn">
            Pay
        </button>
        <button type="button" class="btn-back-cs" data-bs-dismiss="modal">
            Close
        </button>
    </div>
</form>