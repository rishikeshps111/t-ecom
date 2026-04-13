<script>
    let itemIndex = {{ isset($quotation) ? $quotation->items->count() : 1 }};
    let attachmentIndex = {{ isset($quotation) ? $quotation->attachments->count() : 1 }};

    // Add Item
    $('#add-item').click(function () {
        let html = `<div class="row item-row ">
         <div class="col-lg-3 mb-2 d-flex align-items-center d-none">
            <div class="form-check mt-4">
                <input type="hidden" name="items[${itemIndex}][is_selected]" value="0">
                <input type="checkbox"
                       name="items[${itemIndex}][is_selected]"
                       class="form-check-input"
                       id="itemCheck${itemIndex}"
                       value="1"
                       checked>
                <label class="form-check-label" for="itemCheck${itemIndex}"></label>
            </div>
        </div>
        <div class="col-lg-3 mb-2 o-f-inp">
            <label>Item <span class="text-danger">*</span></label>
            <select name="items[${itemIndex}][item_id]" class="form-select shadow-none">
                <option value="">-- Select Item --</option>
                @foreach ($items as $it)
                    <option value="{{ $it->id }}" data-price="{{ $it->suggested_price ?? 0 }}" data-sst="{{ $it->stt ?? 0 }}" data-iv="{{ $it->planner_iv_percentage ?? 0 }}"   data-piv="{{ $it->production_iv_percentage ?? 0 }}"
                                data-description="{{ e(strip_tags($it->detail_description ?? '')) }}">{{ $it->item_name }}</option>
                @endforeach
            </select>
        </div>
         <div class="col-lg-2 mb-2 o-f-inp">
            <label>Unit Price<span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control shadow-none">
        </div>
        <div class="col-lg-2 mb-2 o-f-inp">
            <label>Quantity <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control shadow-none" value="1">
        </div>
         <div class="col-lg-2 mb-2 o-f-inp">
            <label>Total <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][sum_amount]" class="form-control shadow-none">
        </div>
        <div class="col-lg-3 mb-2 o-f-inp">
            <label>Planner IV (%)</label>
            <input type="number" step="0.01" name="items[${itemIndex}][planner_iv]" class="form-control shadow-none" readonly>
        </div>
        <div class="col-lg-3 mb-2 o-f-inp">
            <label>Production IV (%)</label>
            <input type="number" step="0.01" name="items[${itemIndex}][production_iv]" class="form-control shadow-none" readonly>
        </div>
        <div class="col-lg-3 mb-2 o-f-inp d-none">
            <label>UMO <span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][umo]" class="form-control shadow-none">
        </div>
        <div class="col-lg-3 mb-2 o-f-inp">
            <label>SST (%)<span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="items[${itemIndex}][tax_percentage]" class="form-control shadow-none" readonly>
        </div>
        <div class="col-lg-3 mb-2 o-f-inp">
            <label>Discount(%)</label>
            <input type="number" min="0" max="100" step="0.01" name="items[${itemIndex}][discount_amount]" class="form-control shadow-none">
        </div>
         <div class="col-lg-3 mb-2 o-f-inp">
            <label>Sub Total</label>
            <input type="number" step="0.01" name="items[${itemIndex}][total_amount]" class="form-control shadow-none" readonly>
        </div>
        <div class="col-lg-12 mb-2 o-f-inp">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="items[${itemIndex}][description]" class="form-control auto-expand shadow-none" rows="2"
                style="resize: none; overflow: hidden;"></textarea>
        </div>
        <div class="col-lg-12 mb-3 o-f-inp d-flex justify-content-start">
            <button type="button" class="btn btn-danger remove-item"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>`;
        $('#items-container').append(html);
        itemIndex++;
        selectTwo();
        textAreaScroll();
    });

    // Remove Item
    $(document).on('click', '.remove-item', function () {
        $(this).closest('.item-row').remove();
    });

    // Add Attachment
    $('#add-attachment').click(function () {
        let html = `<div class="row attachment-row mb-3">
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>File</label>
            <input type="file" name="attachments[${attachmentIndex}][file]" class="form-control attachment-input">
            <div class="preview mt-2"></div>
        </div>
        <div class="col-lg-1 mb-3 o-f-inp d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-attachment"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>`;
        $('#attachments-container').append(html);
        attachmentIndex++;
    });

    // Remove Attachment
    $(document).on('click', '.remove-attachment', function () {
        $(this).closest('.attachment-row').remove();
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
            let totalCommission = 0;
            let totalSumAmount = 0;

            let plannerCommissionPercent = parseFloat($('#plannerCommission').val()) || 0;
            let grantTotalAmount = parseFloat($('#hiddenGrandTotal').val()) || 0;
            let productionCommissionPercent = parseFloat($('#productionCommission').val()) || 0;


            $('#items-container .item-row').each(function () {
                let row = $(this);

                // Only checked rows
                let isChecked = row.find('input[type="checkbox"][name$="[is_selected]"]').is(
                    ':checked');
                if (!isChecked) return;

                let sumAmount = parseFloat(row.find('input[name$="[sum_amount]"]').val());
                sumAmount = isNaN(sumAmount) ? 0 : sumAmount;

                let discountPercentage = parseFloat(row.find('input[name$="[discount_amount]"]').val());
                discountPercentage = isNaN(discountPercentage) ? 0 : discountPercentage;

                let discountAmount = discountPercentage > 0 ?
                    (sumAmount * discountPercentage) / 100 :
                    0;

                let plannerIv = parseFloat(row.find('input[name$="[planner_iv]"]').val());
                plannerIv = isNaN(plannerIv) ? 0 : plannerIv;

                let commission =
                    (sumAmount - discountAmount) *
                    (plannerIv / 100) *
                    (plannerCommissionPercent / 100);

                totalCommission += commission;
                totalSumAmount += sumAmount;
            });

            // Show values
            $('#totalPlannerCommission').text(totalCommission.toFixed(2));
            $('#totalPlannerCommissionHidden').val(totalCommission.toFixed(2));


            let billToPPercentage = 0;
            let productionCommissionAmount = 0;

            if (grantTotalAmount > 0) {
                billToPPercentage = (totalCommission / grantTotalAmount) * 100;
                productionCommissionAmount = grantTotalAmount * (plannerCommissionPercent / 100)

            }

            $('#totalPlannerCommissionPercentage').text(billToPPercentage.toFixed(2));
            $('#totalProductionCommission').text(productionCommissionAmount.toFixed(2));
            $('#billToP').val(billToPPercentage.toFixed(5));

        }


        // Trigger calculation on input change
        $(document).on('input',
            'input[name$="[quantity]"], input[name$="[unit_price]"], input[name$="[tax_percentage]"], input[name$="[discount_amount]"]',
            function () {
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

        function previewFile(input) {
            let previewContainer = $(input).siblings('.preview');
            invoice
            previewContainer.html(''); // Clear previous preview

            let file = input.files[0];
            if (!file) return;

            let fileType = file.type;
            let reader = new FileReader();

            if (fileType.startsWith('image/')) {
                // Image preview
                reader.onload = function (e) {
                    previewContainer.html(
                        `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 150px;">`
                    );
                }
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                // PDF preview
                previewContainer.html(`<p><i class="fa-solid fa-file-pdf text-danger"></i> ${file.name}</p>`);
            } else {
                // Other files
                previewContainer.html(`<p><i class="fa-solid fa-file"></i> ${file.name}</p>`);
            }
        }

        $(document).on('change', '.attachment-input', function () {
            previewFile(this);
        });

        // Remove attachment row
        $(document).on('click', '.remove-attachment', function () {
            $(this).closest('.attachment-row').remove();
        });

        $(document).on('change', 'select[name^="items"]', function () {
            var selectedOption = $(this).find('option:selected');
            var price = selectedOption.data('price') ?? 0;
            var sst = selectedOption.data('sst') ?? 0;
            var iv = selectedOption.data('iv') ?? 0;
            var piv = selectedOption.data('piv') ?? 0;
            var description = selectedOption.data('description') ?? '';


            var row = $(this).closest('.item-row');
            row.find('input[name$="[unit_price]"]').val(price);
            row.find('input[name$="[tax_percentage]"]').val(sst);
            row.find('input[name$="[planner_iv]"]').val(iv);
            row.find('input[name$="[production_iv]"]').val(piv);
            row.find('textarea[name$="[description]"]').val(description);



            calculateSummary();
            calculatePlannerCommission();
        });

    });

    function selectTwo() {
        $('.form-select').select2()
    }

    function textAreaScroll() {
        document.querySelectorAll('.auto-expand').forEach(textarea => {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto'; // reset height
                this.style.height = this.scrollHeight + 'px'; // set height to scrollHeight
            });

            // Trigger input to resize on page load if there is content
            textarea.dispatchEvent(new Event('input'));
        });
    }
</script>

<script>
    $(document).ready(function () {

        function loadCompanies() {
            let typeId = $('#company_type_id').val();
            let userId = $('#business_user_id').val();
            let oldCompanyId = '{{ old('company_id', $quotation->company_id ?? null) }}';
            if (!typeId && !userId) {
                $('#company_id').html('<option value="">-- Select Company --</option>');
                $('#planner_user_id').html('<option value="">-- Select Planner User --</option>');
                return;
            }

            $.get('/companies', {
                company_type_id: typeId,
                business_user_id: userId
            }, function (data) {

                let options = '<option value="">-- Select Company --</option>';

                data.forEach(item => {
                    let selected = oldCompanyId == item.id ? 'selected' : '';
                    options +=
                        `<option value="${item.id}" data-total-group-id="${item.total_group_id}" ${selected}>${item.company_name}</option>`;
                });

                $('#company_id').html(options);

                // Auto load planners if company exists
                if (oldCompanyId) {
                    $('#company_id').trigger('change');
                }
            });
        }



        $('#company_type_id, #business_user_id').on('change', loadCompanies);

        $('#company_id').on('change', function () {
            let companyId = $(this).val();
            let oldPlannerId = '{{ old('planner_user_id', $quotation->planner_user_id ?? null) }}';

            if (!companyId) {
                $('#planner_user_id').html('<option value="">-- Select Planner User --</option>');
                return;
            }

            $.get('/planners', {
                company_id: companyId
            }, function (data) {

                let options = '<option value="">-- Select Planner User --</option>';

                data.forEach(user => {
                    let selected = oldPlannerId == user.id ? 'selected' : '';
                    options +=
                        `<option value="${user.id}" ${selected}>${user.name}</option>`;
                });

                $('#planner_user_id').html(options);
            });
        });

        $('#company_id').on('change', function () {
            let totalGroupId = $(this).find('option:selected').attr('data-total-group-id') || '';
            $('#total_group_id').val(totalGroupId).trigger('change');
        });


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

        document.querySelectorAll('.auto-expand').forEach(textarea => {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto'; // reset height
                this.style.height = this.scrollHeight + 'px'; // set height to scrollHeight
            });

            // Trigger input to resize on page load if there is content
            textarea.dispatchEvent(new Event('input'));
        });


    });
</script>