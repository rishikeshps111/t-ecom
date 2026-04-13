<form action="{{ isset($invoice) ? route('admin.invoices.update', $invoice->id) : route('admin.invoices.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($invoice))
        @method('PUT')
    @endif
    @if ($workPlanData)
        <input type="hidden" value="1" name="work_plan" />
    @endif
    <h5 class="mb-3">Invoice Details</h5>

    @if ($workPlanData)
        <div class="row mb-3 border rounded bg-light p-3 m-2">
            <div class="col-lg-4 d-none">
                <span class="detail-label fw-bold">Work Order No:</span>
                <span class="detail-value">{{ $workPlanData->workplan_number }}</span>
            </div>
            <div class="col-lg-6">
                <span class="detail-label fw-bold">Customer Name:</span>
                <span class="detail-value">{{ $workPlanData->company->company_name }}</span>
            </div>
            <div class="col-lg-6">
                <span class="detail-label fw-bold">Work Order Date:</span>
                <span class="detail-value">{{ $workPlanData->date->format('d M Y') }}</span>
            </div>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-3 mb-3 o-f-inp">
            <label>Invoice Number<span class="text-danger">*</span></label>
            <input type="text" name="invoice_number" class="form-control"
                value="{{ old('invoice_number', $invoice->invoice_number ?? $code) }}" readonly>
            @error('invoice_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-3 mb-3 o-f-inp">
            <label>Invoice Date <span class="text-danger">*</span></label>
            <input type="date" name="invoice_date" class="form-control"
                value="{{ old('invoice_date', isset($invoice) ? $invoice->invoice_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                @if(!isset($invoice)) min="{{ now()->format('Y-m-d') }}" @endif>
            @error('invoice_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-3 mb-3 o-f-inp">
            <label>Quotation Ref No<span class="text-danger">*</span></label>
            <select name="quotation_id" class="form-select" disabled>
                <option value="">-- Select Quotation --</option>
                @foreach ($quotations as $quotation)
                    <option value="{{ $quotation->id }}" {{ old('quotation_id', $invoice->quotation_id ?? ($workPlanData->quotation->id ?? '')) == $quotation->id ? 'selected' : '' }}>
                        {{ $quotation->custom_quotation_id }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="quotation_id"
                value="{{ $invoice->quotation_id ?? ($workPlanData->quotation->id ?? '') }}">
            @error('quotation_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-3 mb-3 o-f-inp">
            <label>Invoice Expiry Date <span class="text-danger">*</span></label>
            <input type="date" id="due_date" name="due_date" class="form-control"
                value="{{ old('due_date', isset($invoice) ? $invoice->due_date->format('Y-m-d') : '') }}"
                @if(!isset($invoice)) min="{{ now()->format('Y-m-d') }}" @endif>
            @error('due_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->planner->name ?? $invoice->quotation->company->planner->name ?? '' }}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Production Staff</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->productionStaff->name ?? $invoice->quotation->workPlan->company->productionStaff->name ?? ''}}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->totalGroup->customer_name ?? $invoice->quotation->workPlan->company->totalGroup->customer_name ?? '' }}
                </option>
            </select>
        </div>

        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $invoice->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        @if(Auth::user()->hasRole('Super Admin'))
            <div class="col-lg-3 mb-3 o-f-inp d-none">
                <label>Corp User <span class="text-danger">*</span></label>
                <select name="business_user_id" id="business_user_id" class="form-select">
                    <option value="">-- Select Corp User --</option>
                    @foreach ($corpUsers as $user)
                        <option value="{{ $user->id }}" {{ old('business_user_id', $invoice->business_user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('business_user_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="business_user_id" id="business_user_id" value="{{ Auth::id() }}">
        @endif

        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Customer <span class="text-danger">*</span></label>
            <select name="customer_id" class="form-select">
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id ?? '') == $customer->id
                    ? 'selected' : '' }}>
                    {{ $customer->customer_name }}
                </option>
                @endforeach
            </select>
            @error('customer_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Company <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-select">
                <option value="">-- Select Company --</option>
            </select>
            @error('company_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $invoice->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>



        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Currency <span class="text-danger">*</span></label>
            <select name="currency_id" id="currency_id" class="form-select">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{ old('currency_id', $invoice->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                        {{ $currency->currency_code }}
                    </option>
                @endforeach
            </select>
            @error('currency_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>



    </div>

    <hr>
    <h5 class="mb-3">Invoice Items</h5>
    <input type="hidden" name="planner_commission" id="plannerCommission"
        value=" {{ $workPlanData->company->planner->planner_c_percentage ?? $invoice->quotation->workPlan->company->planner->planner_c_percentage ?? 0  }}">
    <input type="hidden" name="production_commission" id="productionCommission"
        value=" {{ $workPlanData->company->productionStaff->production_c_percentage ?? ($invoice->quotation->workPlan->company->productionStaff->production_c_percentage ?? 0) }}">
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
                <div class="col-lg-3 mb-3 o-f-inp">
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
                <div class="col-lg-2 mb-3 o-f-inp">
                    <label>Unit Price<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control"
                        value="{{ $item['unit_price'] ?? '' }}" readonly>
                    @error("items.$i.unit_price")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-3 o-f-inp">
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
                <div class="col-lg-3 mb-3 o-f-inp">
                    <label>Planner IV (%)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][planner_iv]" class="form-control"
                        value="{{ $item['planner_iv'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Production IV (%)</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][production_iv]" class="form-control shadow-none"
                        value="{{ $item['production_iv'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-1 mb-3 o-f-inp d-none">
                    <label>UMO <span class="text-danger">*</span></label>
                    <input type="text" name="items[{{ $i }}][umo]" class="form-control" value="{{ $item['umo'] ?? '' }}">
                    @error("items.$i.umo")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-lg-3 mb-3 o-f-inp">
                    <label>SST (%)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][tax_percentage]" class="form-control"
                        value="{{ $item['tax_percentage'] ?? '' }}" readonly>
                    @error("items.$i.tax_percentage")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-3 o-f-inp">
                    <label>Discount(%)</label>
                    <input type="number" min="0" max="100" step="0.01" name="items[{{ $i }}][discount_amount]"
                        class="form-control" value="{{ $item['discount_amount'] ?? '' }}" readonly>
                    @error("items.$i.discount_amount")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-3 o-f-inp">
                    <label>Sub Total</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][total_amount]" class="form-control"
                        value="{{ $item['total_amount'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-12 mb-2 o-f-inp">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea name="items[{{ $i }}][description]" class="form-control auto-expand shadow-none" rows="2"
                        style="resize: none; overflow: hidden;">{{ $item['description'] ?? '' }}</textarea>
                    @error("items.$i.description")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
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
        <div class="row">
            <div class="col-lg-12">
                <div class="plan-commision-cs">
                    <ul>
                        <li class="detail-label fw-bold">Planner Commission : <span class="detail-value"
                                id="totalPlannerCommission">00.0</span> <input type="hidden" name="planner_commission"
                                value="0.00" id="totalPlannerCommissionHidden"></li>
                        <li class="detail-label fw-bold">Bill to P % : <span class="detail-value"
                                id="totalPlannerCommissionPercentage">0.00</span>% <input type="hidden"
                                name="p_bill_percentage" value="0.00" id="billToP"></li>
                        <li class="detail-label fw-bold">Production Commission % : <span
                                class="detail-value">{{ $workPlanData->company->productionStaff->production_c_percentage ?? ($invoice->quotation->workPlan->company->productionStaff->production_c_percentage ?? 0) }}</span>%
                        </li>
                        <li class="detail-label fw-bold">Production Commission : <span class="detail-value"
                                id="totalProductionCommission">00.0</span></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="plan-commision-2-cs">
                    <ul>
                        <li class="detail-label fw-bold">Total: <span class="detail-value" id="sub_total">00.0</span>
                        </li>
                        <li class="detail-label fw-bold">SST: <span class="detail-value" id="tax_total">0.00</span></li>
                        <li class="detail-label fw-bold">Discount: <span class="detail-value"
                                id="discount_total">0.00</span></li>
                        <li class="detail-label fw-bold">Grand Total: <span class="detail-value"
                                id="grand_total">0.00</span> <input type="hidden" name="grand_total" value="0.00"
                                id="hiddenGrandTotal"></li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Payment Terms</label>
            <textarea name="terms" id="terms" class="form-control editor"
                rows="4">{{ old('terms', $invoice->terms ?? $workPlanData->totalGroup->billerProfile->invoice_payment_terms) }}</textarea>
            @error('terms')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" class="form-control"
                rows="4">{{ old('remarks', $invoice->remarks ?? '') }}</textarea>
            @error('remarks')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        @if ($workPlanData)
            <a href="{{ route('admin.work-orders.show', $workPlanData->id) }}" class="btn btn-danger">
                Back
            </a>
        @else
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-danger">
                Back
            </a>
        @endif
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                {{ isset($invoice) ? 'Update' : 'Save' }}
            </button>
            {{-- @if (!isset($invoice))
            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                Save as Draft
            </button>

            <button type="submit" name="status" value="submitted" class="btn btn-success">
                Submit
            </button>
            @else
            @if ($invoice->status === 'draft')
            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                Save as Draft
            </button>
            <button type="submit" name="status" value="submitted" class="btn btn-success">
                Submit
            </button>
            @else
            <button type="submit" name="status" value="{{ $invoice->status }}" class="btn btn-success">
                Update
            </button>
            @endif
            @endif --}}
        </div>
    </div>
</form>