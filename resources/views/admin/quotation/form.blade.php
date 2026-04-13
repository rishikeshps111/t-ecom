<style>
    textarea {
        height: 150px !important;
    }
</style>
<form
    action="{{ isset($quotation) ? route('admin.quotations.update', $quotation->id) : route('admin.quotations.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($quotation))
        @method('PUT')
    @endif

    @if ($workPlanData)
        <input type="hidden" value="1" name="work_plan" />
    @endif

    <h5 class="mb-3">Quotation Details</h5>
    @if ($workPlanData)
        <div class="row mb-3 bg-wo-cs">
            <div class="col-lg-4 d-none">
                <span class="detail-label fw-bold">Work Order No : </span>
                <span class="detail-value">{{ $workPlanData->workplan_number }}</span>
            </div>
            <div class="col-lg-6">
                <span class="detail-label fw-bold">Customer Name : </span>
                <span class="detail-value">{{ $workPlanData->company->company_name }}</span>
            </div>
            <div class="col-lg-6">
                <span class="detail-label fw-bold">Work Order Date : </span>
                <span class="detail-value">{{ $workPlanData->date->format('d M Y') }}</span>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Quotation Number<span class="text-danger">*</span></label>
            <input type="text" name="quotation_number" class="form-control"
                value="{{ old('quotation_number', $quotation->quotation_number ?? $code) }}" readonly>
            @error('quotation_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Quotation Date <span class="text-danger">*</span></label>
            <input type="date" name="quotation_date" class="form-control"
                value="{{ old('quotation_date', isset($quotation) ? $quotation->quotation_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                @if (!isset($quotation)) min="{{ now()->format('Y-m-d') }}" @endif>
            @error('quotation_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Work Order <span class="text-danger">*</span></label>
            <select name="work_plan_id" id="work_plan_id" class="form-select" disabled>
                <option value="">-- Select Work Order --</option>
                @foreach ($workPlans as $workPlan)
                    <option value="{{ $workPlan->id }}" {{ old('work_plan_id', $quotation->work_plan_id ?? ($workPlanData->id ?? '')) == $workPlan->id ? 'selected' : '' }}>
                        {{ $workPlan->workplan_number }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="work_plan_id"
                value="{{ $quotation->work_plan_id ?? ($workPlanData->id ?? '') }}">
            @error('work_plan_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $quotation->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        @if (Auth::user()->hasRole('Super Admin'))
            <div class="col-lg-4 mb-3 o-f-inp d-none">
                <label>Corp User <span class="text-danger">*</span></label>
                <select name="business_user_id" id="business_user_id" class="form-select">
                    <option value="">-- Select Corp User --</option>
                    @foreach ($corpUsers as $user)
                        <option value="{{ $user->id }}" {{ old('business_user_id', $quotation->business_user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('business_user_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="business_user_id" value="{{ Auth::id() }}">
        @endif

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
            <label>Planner User</label>
            <select name="planner_user_id" id="planner_user_id" class="form-select">
                <option value="">-- Select Planner User --</option>
            </select>
            @error('planner_user_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $quotation->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Contact Person <span class="text-danger">*</span></label>
            <input type="text" name="contact_person" class="form-control"
                value="{{ old('contact_person', $quotation->contact_person ?? '') }}">
            @error('contact_person')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Quotation Expiry Date <span class="text-danger">*</span></label>
            <input type="date" name="validity_date" class="form-control" value="{{ old(
    'validity_date',
    isset($quotation) && $quotation->validity_date
    ? $quotation->validity_date->format('Y-m-d')
    : now()->addDays(30)->format('Y-m-d'),
) }}" @if (!isset($quotation)) min="{{ now()->format('Y-m-d') }}" @endif>
            @error('validity_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Planner</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->planner->name ?? ($quotation->company->planner->name ?? '') }}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Production Staff</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->productionStaff->name ?? ($quotation->workPlan->company->productionStaff->name ?? '') }}
                </option>
            </select>
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group</label>
            <select class="form-select" disabled>
                <option>
                    {{ $workPlanData->company->totalGroup->customer_name ?? ($quotation->workPlan->company->totalGroup->customer_name ?? '') }}
                </option>
            </select>
        </div>

        <div class="col-lg-3 mb-3 o-f-inp d-none">
            <label>Currency <span class="text-danger">*</span></label>
            <select name="currency_id" id="currency_id" class="form-select">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{ old('currency_id', $quotation->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                        {{ $currency->currency_code }}
                    </option>
                @endforeach
            </select>
            @error('currency_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Validity in Days <span class="text-danger">*</span></label>
            <input type="text" name="validity_in_days" class="form-control"
                value="{{ old('validity_in_days', $quotation->validity_in_days ?? '') }}">
            @error('validity_in_days')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}
    </div>

    <hr>
    <h5 class="mb-3">Quotation Items</h5>
    <input type="hidden" name="planner_commission" id="plannerCommission"
        value=" {{ $workPlanData->company->planner->planner_c_percentage ?? ($quotation->workPlan->company->planner->planner_c_percentage ?? 0) }}">
    <input type="hidden" name="production_commission" id="productionCommission"
        value=" {{ $workPlanData->company->productionStaff->production_c_percentage ?? ($quotation->workPlan->company->productionStaff->production_c_percentage ?? 0) }}">

    <div id="items-container">
        @php
            $oldItems = old('items', isset($quotation) ? $quotation->items->toArray() : []);
            // Ensure at least one row
            if (empty($oldItems)) {
                $oldItems[] = [];
            }
        @endphp

        @foreach ($oldItems as $i => $item)
            <div class="row item-row ">
                <div class="col-lg-3 mb-2 d-flex align-items-center d-none">
                    <div class="form-check mt-4">
                        <input type="hidden" name="items[{{ $i }}][is_selected]" value="0">
                        <input type="checkbox" name="items[{{ $i }}][is_selected]" class="form-check-input"
                            id="itemCheck{{ $i }}" value="1" checked>
                        <label class="form-check-label" for="itemCheck{{ $i }}"></label>
                    </div>
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Item <span class="text-danger">*</span></label>
                    <select name="items[{{ $i }}][item_id]" class="form-select shadow-none">
                        <option value="">-- Select Item --</option>
                        @foreach ($items as $it)
                            <option value="{{ $it->id }}" data-price="{{ $it->suggested_price ?? 0 }}"
                                data-sst="{{ $it->stt ?? 0 }}" data-iv="{{ $it->planner_iv_percentage ?? 0 }}"
                                data-piv="{{ $it->production_iv_percentage ?? 0 }}"
                                data-description="{{ e(strip_tags($it->detail_description ?? '')) }}" {{ ($item['item_id'] ?? '') == $it->id ? 'selected' : '' }}>
                                {{ $it->item_name }}
                            </option>
                        @endforeach
                    </select>
                    @error("items.$i.item_id")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-2 o-f-inp">
                    <label>Unit Price<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control shadow-none"
                        value="{{ $item['unit_price'] ?? '' }}">
                    @error("items.$i.unit_price")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-2 o-f-inp">
                    <label>Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="items[{{ $i }}][quantity]" class="form-control shadow-none"
                        value="{{ $item['quantity'] ?? 1 }}">
                    @error("items.$i.quantity")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-2 mb-2 o-f-inp">
                    <label>Total <span class="text-danger">*</span></label>
                    <input type="number" name="items[{{ $i }}][sum_amount]" class="form-control shadow-none"
                        value="{{ $item['sum_amount'] ?? '' }}">
                    @error("items.$i.sum_amount")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Planner IV (%)</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][planner_iv]" class="form-control shadow-none"
                        value="{{ $item['planner_iv'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Production IV (%)</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][production_iv]" class="form-control shadow-none"
                        value="{{ $item['production_iv'] ?? '' }}" readonly>
                </div>
                <div class="col-lg-3 mb-2 o-f-inp d-none">
                    <label>UMO <span class="text-danger">*</span></label>
                    <input type="text" name="items[{{ $i }}][umo]" class="form-control shadow-none"
                        value="{{ $item['umo'] ?? '' }}">
                    @error("items.$i.umo")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>SST (%)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="items[{{ $i }}][tax_percentage]" class="form-control shadow-none"
                        value="{{ $item['tax_percentage'] ?? '' }}" readonly>
                    @error("items.$i.tax_percentage")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Discount(%)</label>
                    <input type="number" min="0" max="100" step="0.01" name="items[{{ $i }}][discount_amount]"
                        class="form-control shadow-none" value="{{ $item['discount_amount'] ?? '' }}">
                    @error("items.$i.discount_amount")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-3 mb-2 o-f-inp">
                    <label>Sub Total</label>
                    <input type="number" step="0.01" name="items[{{ $i }}][total_amount]" class="form-control shadow-none"
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
                <div class="col-lg-12 mb-2 o-f-inp d-flex justify-content-start">
                    <button type="button" class="btn btn-danger remove-item">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="submit-btn mt-3 mx-0" style="background-color:green;" id="add-item">Add Item</button>
    <hr>
    <div id="items-summary" class="mt-3 ">
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
                                class="detail-value">{{ $workPlanData->company->productionStaff->production_c_percentage ?? ($quotation->workPlan->company->productionStaff->production_c_percentage ?? 0) }}</span>%
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
    <h5 class="mb-3 d-none">Attachments</h5>
    <div id="attachments-container" class="d-none">
        @php
            $oldAttachments = old('attachments', isset($quotation) ? $quotation->attachments->toArray() : [0 => []]);
        @endphp

        @foreach ($oldAttachments as $i => $attachment)
            <div class="row attachment-row mb-3">
                <div class="col-lg-6 mb-3 o-f-inp">
                    <label>File</label>
                    <input type="file" name="attachments[{{ $i }}][file]" class="form-control attachment-input">
                    <input type="hidden" name="attachments[{{ $i }}][id]" value="{{ $attachment['id'] ?? '' }}">
                    <div class="preview mt-2">
                        @if (!empty($attachment['file']))
                            @php
                                $fileUrl = asset('storage/' . $attachment['file']);
                                $fileExt = pathinfo($attachment['file'], PATHINFO_EXTENSION);
                            @endphp

                            @if (in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ $fileUrl }}" class="img-thumbnail" style="max-width:150px;">
                            @elseif(strtolower($fileExt) === 'pdf')
                                <p><i class="fa-solid fa-file-pdf text-danger"></i>
                                    <a href="{{ $fileUrl }}" target="_blank">{{ basename($attachment['file']) }}</a>
                                </p>
                            @else
                                <p><i class="fa-solid fa-file"></i>
                                    <a href="{{ $fileUrl }}" target="_blank">{{ basename($attachment['file']) }}</a>
                                </p>
                            @endif
                        @endif
                    </div>
                    @error("attachments.$i.file")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-lg-1 mb-3 o-f-inp d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-attachment">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-primary mb-3 d-none" id="add-attachment">Add Attachment</button>
    <hr class="d-none">
    {{-- <h5 class="mb-3">Terms</h5> --}}
    <div class="row">

        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Invoice Address </label>
            <textarea name="invoice_address" class="form-control editor"
                rows="4">{{ old('invoice_address', $quotation->invoice_address ?? $workPlanData->company->address ?? '') }}</textarea>
            @error('invoice_address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Delivery Address</label>
            <textarea name="delivery_address" class="form-control"
                rows="4">{{ old('delivery_address', $quotation->delivery_address ?? '') }}</textarea>
            @error('delivery_address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Payment Terms</label>
            <textarea name="payment_terms" class="form-control editor"
                rows="4">{{ old('payment_terms', $quotation->payment_terms ?? $workPlanData->totalGroup->billerProfile->quotation_tc) }}</textarea>
            @error('payment_terms')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-6 mb-3 o-f-inp d-none">
            <label>Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" class="form-control"
                rows="4">{{ old('remarks', $quotation->remarks ?? '') }}</textarea>
            @error('remarks')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- <div class="col-lg-4 mb-3 o-f-inp">
            <label>Notes<span class="text-danger">*</span></label>
            <textarea name="notes" class="form-control" rows="4">{{ old('notes', $quotation->notes ?? '') }}</textarea>
            @error('notes')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Terms & Conditions<span class="text-danger">*</span></label>
            <textarea name="terms" class="form-control" rows="4">{{ old('terms', $quotation->terms ?? '') }}</textarea>
            @error('terms')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}
    </div>
    <hr>
    <div class="d-flex justify-content-center flex-center-btns">
        @if ($workPlanData)
            <a href="{{ route('admin.work-orders.show', $workPlanData->id) }}" class="btn-back-cs">
                Back
            </a>
        @else
            <a href="{{ route('admin.quotations.index') }}" class="btn-back-cs">
                Back
            </a>
        @endif
        <div class="d-flex gap-2">
            <button type="submit" class="add-btn">
                {{ isset($quotation) ? 'Update' : 'Save' }}
            </button>
            {{-- @if (!isset($quotation))
            <button type="submit" name="status" value="draft" class="submit-btn">
                Save as Draft
            </button>

            <button type="submit" name="status" value="submitted" class="add-btn">
                Submit
            </button>
            @else
            @if ($quotation->status === 'draft')
            <button type="submit" name="status" value="draft" class="submit-btn">
                Save as Draft
            </button>
            <button type="submit" name="status" value="submitted" class="add-btn">
                Submit
            </button>
            @else
            <button type="submit" name="status" value="{{ $quotation->status }}" class="add-btn">
                Update
            </button>
            @endif
            @endif --}}
        </div>
    </div>
</form>