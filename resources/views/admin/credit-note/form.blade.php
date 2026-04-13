<form action="{{ route('admin.credit-notes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}" />
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Invoice Number<span class="text-danger">*</span></label>
            <input type="text" name="invoice_number" class="form-control" value="{{ $invoice->invoice_number ?? '-' }}"
                readonly>
            @error('invoice_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Credit Note Number<span class="text-danger">*</span></label>
            <input type="text" name="credit_note_number" class="form-control" value="{{ $code }}" readonly>
            @error('credit_note_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Credit Note Date <span class="text-danger">*</span></label>
            <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}">
            @error('date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Customer Name</label>
            <select class="form-select" disabled>
                <option>
                    {{ $invoice->quotation->workPlan->company->company_name ?? '' }}
                </option>
            </select>
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Customer Phone</label>
            <select class="form-select" disabled>
                <option>
                    {{ $invoice->quotation->workPlan->company->mobile_no ?? '' }}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner</label>
            <select class="form-select" disabled>
                <option>
                    {{ $invoice->quotation->workPlan->company->planner->name ?? '' }}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Production Staff</label>
            <select class="form-select" disabled>
                <option>
                    {{ $invoice->quotation->workPlan->company->productionStaff->name ?? ''}}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group</label>
            <select class="form-select" disabled>
                <option>
                    {{ $invoice->quotation->workPlan->company->totalGroup->customer_name ?? '' }}
                </option>
            </select>
        </div>
    </div>

    <hr>
    <h5 class="mb-3">Invoice Items</h5>
    <div id="items-container">
        @php
            $oldItems = old('items', isset($invoice) ? $invoice->items->toArray() : [0 => []]);
        @endphp

        @foreach ($oldItems as $i => $item)
            <div class="row item-row mb-3">
                <div class="col-lg-auto mb-3 d-flex align-items-center d-none">
                    <div class="form-check mt-4">
                        <input type="hidden" name="items[{{ $i }}][is_selected]" value="0">
                        <input type="checkbox" name="items[{{ $i }}][is_selected]" class="form-check-input"
                            id="itemCheck{{ $i }}" value="1" {{ isset($item['is_selected']) && $item['is_selected'] ? 'checked' : '' }} readonly>
                        <label class="form-check-label" for="itemCheck{{ $i }}"></label>
                    </div>
                </div>
                <div class="col-lg-2 mb-3 o-f-inp">
                    <label>Item <span class="text-danger">*</span></label>
                    <select name="items[{{ $i }}][item_id]" class="form-select" disabled>
                        <option value="">-- Select Item --</option>
                        @foreach ($items as $it)
                            <option value="{{ $it->id }}" {{ ($item['item_id'] ?? '') == $it->id ? 'selected' : '' }}
                                data-price="{{ $it->suggested_price ?? 0 }}" data-sst="{{ $it->stt ?? 0 }}"
                                data-iv="{{ $it->planner_iv_percentage ?? 0 }}">
                                {{ $it->item_name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="items[{{ $i }}][item_id]" value="{{ $item['item_id'] ?? '' }}">
                    @error("items.$i.item_id")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3 o-f-inp d-none">
                    <label>Description <span class="text-danger">*</span></label>
                    <input type="text" name="items[{{ $i }}][description]" class="form-control"
                        value="{{ $item['description'] ?? '' }}">
                    @error("items.$i.description")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-1 mb-3 o-f-inp">
                    <label>Unit Price<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control"
                        value="{{ $item['unit_price'] ?? '' }}" readonly>
                    @error("items.$i.unit_price")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-1 mb-3 o-f-inp">
                    <label>Quantity<span class="text-danger">*</span></label>
                    <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                        value="{{ $item['quantity'] ?? 1 }}" readonly>
                    @error("items.$i.quantity")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3 o-f-inp">
                    <label>Total <span class="text-danger">*</span></label>
                    <input type="number" name="items[{{ $i }}][sum_amount]" class="form-control"
                        value="{{ $item['sum_amount'] ?? '' }}" readonly>
                    @error("items.$i.sum_amount")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3 o-f-inp">
                    <label>Planner IV (%)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][planner_iv]" class="form-control"
                        value="{{ $item['planner_iv'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-1 mb-3 o-f-inp d-none">
                    <label>UMO <span class="text-danger">*</span></label>
                    <input type="text" name="items[{{ $i }}][umo]" class="form-control" value="{{ $item['umo'] ?? '' }}">
                    @error("items.$i.umo")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-lg-1 mb-3 o-f-inp">
                    <label>SST (%)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][tax_percentage]" class="form-control"
                        value="{{ $item['tax_percentage'] ?? '' }}" readonly>
                    @error("items.$i.tax_percentage")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-1 mb-3 o-f-inp">
                    <label>Discount(%)</label>
                    <input type="number" min="0" max="100" step="0.01" name="items[{{ $i }}][discount_amount]"
                        class="form-control" value="{{ $item['discount_amount'] ?? '' }}" readonly>
                    @error("items.$i.discount_amount")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3 o-f-inp">
                    <label>Sub Total</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][total_amount]" class="form-control"
                        value="{{ $item['total_amount'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-12 mb-3 o-f-inp d-flex justify-content-end">
                    <button type="button" class="btn btn-danger remove-item d-none">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-primary mb-3 d-none" id="add-item">Add Item</button>
    <hr>
    <div id="items-summary" class="mt-3 p-3 border rounded bg-light shadow-sm">
        <div class="row text-center fw-bold">
            <div class="col-lg-4 mb-2 mb-lg-0">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">Planner Commission:</span>
                        <span class="detail-value" id="totalPlannerCommission">{{ $invoice->planner_commission }}</span>
                        <input type="hidden" name="planner_commission" value="0.00" id="totalPlannerCommissionHidden">
                    </div>
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">Bill to P %:</span>
                        <span class="detail-value"
                            id="totalPlannerCommissionPercentage">{{ $invoice->p_bill_percentage }}</span>%
                        <input type="hidden" name="p_bill_percentage" value="0.00" id="billToP">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-2 mb-lg-0">
            </div>

            <div class="col-lg-4 mb-2 mb-lg-0">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">Total:</span>
                        <span class="detail-value" id="sub_total">00.0</span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">SST:</span>
                        <span class="detail-value" id="tax_total">0.00</span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">Discount:</span>
                        <span class="detail-value" id="discount_total">0.00</span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <span class="detail-label fw-bold">Grand Total:</span>
                        <span class="detail-value" id="grand_total">0.00</span>
                        <input type="hidden" name="grand_total" value="0.00" id="hiddenGrandTotal">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select" required>
                <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>
                    Credit</option>
                <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>
                    Debit</option>
            </select>
            @error('type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Enter the credit note Amount <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Remarks</label>
            <textarea name="remark" id="remark" class="form-control editor" rows="4">{{ old('remark') }}</textarea>
            @error('remark')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.work-orders.show', $invoice->quotation->workPlan->id) }}" class="btn btn-danger">
            Back
        </a>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Save
            </button>
        </div>
    </div>
</form>