<script>
    let itemIndex = {{ isset($quotation) ? $quotation->items->count() : 1 }};
    let attachmentIndex = {{ isset($quotation) ? $quotation->attachments->count() : 1 }};

    // Add Item
    $('#add-item').click(function () {
        let html = `<div class="row item-row mb-3">
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Item <span class="text-danger">*</span></label>
            <select name="items[${itemIndex}][item_id]" class="form-select">
                <option value="">-- Select Item --</option>
                @foreach($items as $it)
                    <option value="{{ $it->id }}" data-price="{{ $it->suggested_price ?? 0 }}">{{ $it->item_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 mb-3 o-f-inp d-none">
            <label>Description <span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][description]" class="form-control">
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Quantity <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control" value="1">
        </div>
        <div class="col-lg-1 mb-3 o-f-inp d-none">
            <label>UMO <span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][umo]" class="form-control">
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Unit Price <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control">
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Tax (%)<span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="items[${itemIndex}][tax_percentage]" class="form-control">
        </div>
        <div class="col-lg-2 mb-3 o-f-inp d-none">
            <label>Discount</label>
            <input type="number" step="0.01" name="items[${itemIndex}][discount_amount]" class="form-control">
        </div>
         <div class="col-lg-2 mb-3 o-f-inp">
            <label>Total</label>
            <input type="number" step="0.01" name="items[${itemIndex}][total_amount]" class="form-control" readonly>
        </div>
        <div class="col-lg-12 mb-3 o-f-inp d-flex justify-content-end">
            <button type="button" class="btn btn-danger remove-item"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>`;
        $('#items-container').append(html);
        itemIndex++;
        selectTwo();
    });

    // Remove Item
    $(document).on('click', '.remove-item', function () {
        $(this).closest('.item-row').remove();
    });

    $(document).ready(function () {

        selectTwo();

        function calculateRowTotal(row) {
            let quantity = parseFloat(row.find('input[name$="[quantity]"]').val()) || 0;
            let unitPrice = parseFloat(row.find('input[name$="[unit_price]"]').val()) || 0;
            let tax = parseFloat(row.find('input[name$="[tax_percentage]"]').val()) || 0;
            let discountPercent = parseFloat(row.find('input[name$="[discount_amount]"]').val()) || 0;

            let totalBeforeTax = quantity * unitPrice;
            let discountAmount = totalBeforeTax * (discountPercent / 100);

            let taxableAmount = totalBeforeTax - discountAmount;
            let taxAmount = taxableAmount * (tax / 100);

            let total = taxableAmount + taxAmount;

            row.find('input[name$="[sum_amount]"]').val(totalBeforeTax.toFixed(2));
            row.find('input[name$="[total_amount]"]').val(total.toFixed(2));

            return {
                totalBeforeTax,
                taxAmount,
                discount: discountAmount,
                total
            };
        }

        function calculateSummary() {
            let subTotal = 0;
            let taxTotal = 0;
            let discountTotal = 0;
            let grandTotal = 0;

            $('#items-container .item-row').each(function () {
                let rowTotals = calculateRowTotal($(this));
                subTotal += rowTotals.totalBeforeTax;
                taxTotal += rowTotals.taxAmount;
                discountTotal += rowTotals.discount;
                grandTotal += rowTotals.total;
            });

            $('#sub_total').text(subTotal.toFixed(2));
            $('#tax_total').text(taxTotal.toFixed(2));
            $('#discount_total').text(discountTotal.toFixed(2));
            $('#grand_total').text(grandTotal.toFixed(2));
            $('#hiddenGrandTotal').val(grandTotal.toFixed(2));

        }

        function calculatePlannerCommission() {
            l

        }

        // Trigger calculation on input change
        $(document).on('input', 'input[name$="[quantity]"], input[name$="[unit_price]"], input[name$="[tax_percentage]"], input[name$="[discount_amount]"]', function () {
            calculateSummary();
            calculatePlannerCommission();
        });

        $(document).on('change', 'input[name$="[is_selected]"]', function () {
            calculatePlannerCommission();
        });

        // Trigger calculation on page load for old items
        calculateSummary();
        calculatePlannerCommission();

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            $(this).closest('.item-row').remove();
            calculateSummary();
            calculatePlannerCommission();
        });

        // Optional: If you have an add-item button
        $(document).on('click', '.add-item', function () {
            let lastRow = $('#items-container .item-row:last');
            let newRow = lastRow.clone();
            newRow.find('input, select').val('');
            $('#items-container').append(newRow);
            calculateSummary();
        });

        $(document).on('change', 'select[name^="items"]', function () {
            var selectedOption = $(this).find('option:selected');
            var price = selectedOption.data('price') ?? 0;
            var sst = selectedOption.data('sst') ?? 0;
            var iv = selectedOption.data('iv') ?? 0;

            var row = $(this).closest('.item-row');
            row.find('input[name$="[unit_price]"]').val(price);
            row.find('input[name$="[tax_percentage]"]').val(sst);
            row.find('input[name$="[planner_iv]"]').val(iv);

            calculateSummary();
            calculatePlannerCommission();

        });

        function loadQuotationDetails(quotationId) {
            if (!quotationId) return;

            $.get(`/quotations/${quotationId}/details`, function (res) {

                const q = res.quotation;

                // ---------- BASIC FIELDS ----------
                $('#company_type_id').val(q.company_type_id).trigger('change');
                $('#business_user_id').val(q.business_user_id).trigger('change');
                $('#currency_id').val(q.currency_id).trigger('change');
                $('#total_group_id').val(q.total_group_id).trigger('change');

                $('#invoice_date').val(q.invoice_date);

                if (q.validity_date) {
                    $('#due_date').val(q.validity_date.split('T')[0]);
                }

                $('#terms').val(q.payment_terms);

                // Wait for company dropdown to load
                setTimeout(() => {
                    $('#company_id').val(q.company_id).trigger('change');
                }, 500);

                // ---------- ITEMS ----------
                $('#items-container').html('');
                itemIndex = 0;

                res.items.forEach(item => {
                    addItemRow(item);
                });

                calculateSummary();
                calculatePlannerCommission();

            });
        }

        $(document).on('change', 'select[name="quotation_id"]', function () {
            loadQuotationDetails($(this).val());
        });

        @if(empty($invoice))
            const quotationId = $('select[name="quotation_id"]').val();

            if (quotationId) {
                loadQuotationDetails(quotationId);
            }
        @endif

    });

    function addItemRow(data = {}) {
        let isChecked = data.is_selected == 1 ? 'checked' : '';
        let html = `
    <div class="row item-row mb-3">
        <div class="col-lg-auto mb-3 d-flex align-items-center d-none">
            <div class="form-check mt-4">
                <input type="hidden" name="items[${itemIndex}][is_selected]" value="0">
                <input type="checkbox"
                    name="items[${itemIndex}][is_selected]"
                    class="form-check-input"
                    id="itemCheck${itemIndex}"
                    value="1"
                    ${isChecked} readonly>
                <label class="form-check-label" for="itemCheck${itemIndex}"></label>
            </div>
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Item <span class="text-danger">*</span></label>
            <select name="items[${itemIndex}][item_id]" class="form-select" disabled>
                <option value="">-- Select Item --</option>
                @foreach($items as $it)
                    <option value="{{ $it->id }}"
                        data-price="{{ $it->suggested_price }}"
                        data-sst="{{ $it->stt ?? 0 }}" data-iv="{{ $it->planner_iv_percentage ?? 0 }}"
                        ${data.item_id == {{ $it->id }} ? 'selected' : ''}>
                        {{ $it->item_name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="items[${itemIndex}][item_id]" value="${data.item_id ?? ''}">
        </div>

        <div class="col-lg-2 mb-3 o-f-inp d-none">
            <label>Description</label>
            <input type="text" name="items[${itemIndex}][description]"
                class="form-control" value="${data.description ?? ''}">
        </div>

        <div class="col-lg-1 mb-3 o-f-inp">
            <label>Unit Price<span class="text-danger">*</span></label>
            <input type="number" step="0.01"
                name="items[${itemIndex}][unit_price]"
                class="form-control" value="${data.unit_price ?? 0}" readonly>
        </div>

        <div class="col-lg-1 mb-3 o-f-inp">
            <label>Quantity<span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][quantity]"
                class="form-control" value="${data.quantity ?? 1}" readonly>
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Total <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][sum_amount]" value="${data.sum_amount ?? ''}" class="form-control" readonly>
        </div>
        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Planner IV (%)<span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="items[${itemIndex}][planner_iv]" value="${data.planner_iv ?? ''}" class="form-control" readonly>
        </div>
        <div class="col-lg-1 mb-3 o-f-inp d-none">
            <label>UMO<span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][umo]"
                class="form-control" value="${data.umo ?? ''}">
        </div>

        

        <div class="col-lg-1 mb-3 o-f-inp">
            <label>SST %<span class="text-danger">*</span></label>
            <input type="number" step="0.01"
                name="items[${itemIndex}][tax_percentage]"
                class="form-control" value="${data.tax_percentage ?? 0}" readonly>
        </div>

        <div class="col-lg-1 mb-3 o-f-inp">
            <label>Discount(%)</label>
            <input type="number" min="0" max="100" step="0.01"
                name="items[${itemIndex}][discount_amount]"
                class="form-control" value="${data.discount ?? 0}" readonly>
        </div>

        <div class="col-lg-2 mb-3 o-f-inp">
            <label>Sub Total</label>
            <input type="number" step="0.01"
                name="items[${itemIndex}][total_amount]"
                class="form-control" readonly>
        </div>

        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-danger remove-item d-none">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>`;

        $('#items-container').append(html);
        itemIndex++;
        selectTwo();
    }

    function selectTwo() {
        $('.form-select').select2()
    }
</script>

<script>
    $(document).ready(function () {

        function loadCompanies() {
            let typeId = $('#company_type_id').val();
            let userId = $('#business_user_id').val();
            let oldCompanyId = '{{ old('company_id', $invoice->company_id ?? null) }}';

            if (!typeId && !userId) {
                $('#company_id').html('<option value="">-- Select Company --</option>');
                $('#total_group_id').html('<option value="">-- Select Total Group --</option>');
                return;
            }

            $.get('/companies', {
                company_type_id: typeId,
                business_user_id: userId
            }, function (data) {

                let options = '<option value="">-- Select Company --</option>';

                data.forEach(item => {
                    let selected = oldCompanyId == item.id ? 'selected' : '';
                    options += `<option value="${item.id}" ${selected}>${item.company_name}</option>`;
                });

                $('#company_id').html(options);

                // Auto load planners if company exists
                if (oldCompanyId) {
                    $('#company_id').trigger('change');
                }
            });
        }



        $('#company_type_id, #business_user_id').on('change', loadCompanies);

        //$('#company_id').on('change', function () {
        //    let companyId = $(this).val();
        //    let oldTotalGroupId = '{{ old('total_group_id', $invoice->total_group_id ?? null) }}';
        //
        //    if (!companyId) {
        //        $('#total_group_id').html('<option value="">-- Select Total Group --</option>');
        //        return;
        //    }
        //
        //    $.get('/total-groups', { company_id: companyId }, function (data) {
        //
        //        let options = '<option value="">-- Select Total Group --</option>';
        //
        //        data.forEach(user => {
        //            let selected = oldTotalGroupId == user.id ? 'selected' : '';
        //            options += `<option value="${user.id}" ${selected}>${user.customer_name}</option>`;
        //        });
        //
        //        $('#total_group_id').html(options);
        //    });
        //});

        if ($('#company_type_id').val() || $('#business_user_id').val()) {
            loadCompanies();
        }

    });


</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editor').forEach((el) => {
            ClassicEditor
                .create(el, {
                    toolbar: [
                        'heading',
                        '|',
                        'bold', 'italic', 'underline',
                        '|',
                        'bulletedList', 'numberedList',
                        '|',
                        'link',
                        '|',
                        'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
@error('amount')
    <script>
        showToast('warning', '{{ $message }}');
    </script>
@enderror