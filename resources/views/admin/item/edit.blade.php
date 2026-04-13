@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3 class="d-flex justify-content-between align-items-center">Items Management <span>Edit</span></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <form action="{{ route('admin.item.update', $item->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" name="item_name"
                                    value="{{ $item->item_name }}">
                                @error('item_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for=""> Items Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" name="item_code"
                                    value="{{ $item->item_code }}" readonly>
                                @error('item_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="company_type_id">Service Type <span class="text-danger">*</span></label>
                                <select name="company_type_id" id="company_type_id" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('company_type_id', $item->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_type_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="total_group_id">Total Group</label>
                                <select name="total_group_id" id="total_group_id" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('total_group_id', $item->total_group_id ?? '') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                                @error('total_group_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Category <span class="text-danger">*</span>@error('category') <small
                                        class="text-danger">{{ $message }}</small> @enderror</label>
                                <select name="category" id="category" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                    @foreach ($category as $cate)
                                    <option value="{{$cate->id}}" {{ old('category', $item->category_id ?? '') == $cate->id
                                        ? 'selected' : '' }}>{{$cate->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Sub Category <span class="text-danger">*</span>@error('sub_category') <small
                                        class="text-danger">{{ $message }}</small> @enderror</label>
                                <select name="sub_category" id="sub_category" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    User <span class="text-danger">*</span>
                                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>

                                <select name="user_id" id="user_id" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $item->user_id ?? '') == $user->id ?
                                        'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Company <span class="text-danger">*</span>
                                    @error('company_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>

                                <select name="company_id" id="company_id" class="form-select shadow-none">
                                    <option value="">---Select---</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $item->company_id ?? '') ==
                                        $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Item Type </label>
                                <select name="item_type" id="item_type" class="form-select shadow-none">
                                    <option value="">--- Select ---</option>
                                    <option value="Product" {{ old('item_type', $item->item_type ?? '') == 'Product' ?
                                        'selected' : '' }}>Product</option>
                                    <option value="Service" {{ old('item_type', $item->item_type ?? '') == 'Service' ?
                                        'selected' : '' }}>Service</option>

                                </select>
                            </div> --}}

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="suggested_price">Suggested Price<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control shadow-none" name="suggested_price"
                                    id="suggested_price" value="{{ $item->suggested_price }}">
                                @error('suggested_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="status">Status<span class="text-danger">*</span> </label>
                                <select name="status" id="status" class="form-select shadow-none">
                                    {{-- <option value="">--- Select ---</option> --}}
                                    <option value="1" {{ old('status', $item->status ?? '') == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $item->status ?? '') == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="planner_commission"
                                        name="planner_commission" value="1"
                                        {{ old('planner_commission', $item->planner_commission) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="planner_commission">
                                        Planner Commission
                                    </label>
                                </div>
                            </div>

                            <div
                                class="col-lg-6 mb-3 o-f-inp planner-fields {{ old('planner_commission', $item->planner_commission) ? '' : 'd-none' }}">
                                <label>Planner IV % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="planner_iv_percentage"
                                    value="{{ old('planner_iv_percentage', $item->planner_iv_percentage) }}"
                                    class="form-control shadow-none">
                                @error('planner_iv_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div
                                class="col-lg-6 mb-3 o-f-inp d-none">
                                <label>Planner C % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="planner_c_percentage"
                                    value="{{ old('planner_c_percentage', $item->planner_c_percentage) }}"
                                    class="form-control shadow-none">
                                @error('planner_c_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <!-- Production Commission -->
                            <div class="col-lg-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="production_commission"
                                        name="production_commission" value="1"
                                        {{ old('production_commission', $item->production_commission) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="production_commission">
                                        Production Commission
                                    </label>
                                </div>
                            </div>

                            <div
                                class="col-lg-6 mb-3 o-f-inp production-fields {{ old('production_commission', $item->production_commission) ? '' : 'd-none' }}">
                                <label>Production IV % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="production_iv_percentage"
                                    value="{{ old('production_iv_percentage', $item->production_iv_percentage) }}"
                                    class="form-control shadow-none">
                                @error('production_iv_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div
                                class="col-lg-6 mb-3 o-f-inp d-none">
                                <label>Production C % <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="production_c_percentage"
                                    value="{{ old('production_c_percentage', $item->production_c_percentage) }}"
                                    class="form-control shadow-none">
                                @error('production_c_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div
                                class="col-lg-12 mb-3 o-f-inp"></div>

                            <div class="col-lg-6 mb-3 o-f-inp">
                                <label>SST %</label>
                                <input type="number" step="0.01" name="stt" value="{{ old('stt', $item->stt) }}"
                                    class="form-control shadow-none">
                                @error('stt')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-6 mb-3 o-f-inp">
                                <label>Access Code</label>
                                <input type="text" name="account_code"
                                    value="{{ old('account_code', $item->account_code) }}"
                                    class="form-control shadow-none">
                                @error('account_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 mb-3 o-f-inp">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description',$item->detail_description ?? null) }}</textarea>

                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <hr />
                            {{-- <h5>Pricing</h5>
                            <hr />

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Selling Price (RM)
                                    <span class="text-danger">*</span>
                                    @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>
                                <input type="number" step="0.01" class="form-control shadow-none" name="selling_price"
                                    id="selling_price" value="{{ $item->selling_price }}">
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Cost Price (RM)
                                    <span class="text-danger">*</span>
                                    @error('cost_price') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>
                                <input type="number" step="0.01" class="form-control shadow-none" name="cost_price"
                                    value="{{ $item->cost_price }}">
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Commission Factor (%)
                                    <span class="text-danger">*</span>
                                    @error('commission_factor') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control shadow-none"
                                    name="commission_factor" id="commission_factor" value="{{ $item->commission_factor }}">
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Commission Amount (RM)</label>
                                <input type="text" class="form-control shadow-none" id="commission_amount" readonly>
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>
                                    Tax Group
                                    <span class="text-danger">*</span>
                                    @error('tax_group') <small class="text-danger">{{ $message }}</small> @enderror
                                </label>
                                <select name="tax_group" id="tax_group" class="form-select shadow-none">
                                    <option value="0" {{ $item->tax_group == 0 ? 'selected' : '' }}>No Tax</option>
                                    <option value="5" {{ $item->tax_group == 5 ? 'selected' : '' }}>Sales Tax 5%</option>
                                    <option value="10" {{ $item->tax_group == 10 ? 'selected' : '' }}>Sales Tax 10%</option>
                                    <option value="6" {{ $item->tax_group == 6 ? 'selected' : '' }}>Service Tax 6%</option>
                                    <option value="0">Exempt / Zero Rated</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Tax Amount (RM)</label>
                                <input type="text" class="form-control shadow-none" id="tax_amount" readonly>
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label>Total Price (RM)</label>
                                <input type="text" class="form-control shadow-none" id="total_price" readonly>
                            </div>

                            <hr />
                            <h5>Inventory</h5>
                            <hr />
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Unit of Measure (UOM)<span class="text-danger">*</span>@error('uom') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="uom" value="{{$item->uom}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Opening Stock<span class="text-danger">*</span>@error('opening_stock') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="opening_stock"
                                    value="{{$item->opening_stock}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Reorder Level<span class="text-danger">*</span>@error('reorder_level') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="reorder_level"
                                    value="{{$item->reorder_level}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Safety Stock<span class="text-danger">*</span>@error('safety_stock') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="safety_stock"
                                    value="{{$item->safety_stock}}">
                            </div>

                            <hr />
                            <h5>Supplier</h5>
                            <hr />
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Default Supplier<span class="text-danger">*</span>@error('default_supplier')
                                <small class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="default_supplier"
                                    value="{{$item->default_supplier}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Supplier Item Code<span class="text-danger">*</span>@error('supplier_item')
                                <small class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="supplier_item"
                                    value="{{$item->supplier_item}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Purchase Price<span class="text-danger">*</span>@error('purchase_price')
                                <small class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="purchase_price"
                                    value="{{$item->purchase_price}}">
                            </div>

                            <hr />
                            <h5>Logistics</h5>
                            <hr />

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Warehouse<span class="text-danger">*</span>@error('warehouse') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="warehouse"
                                    value="{{$item->warehouse}}">
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Bin / Rack Location<span class="text-danger">*</span>@error('bin_location')
                                <small class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="bin_location"
                                    value="{{$item->bin_location}}">
                            </div>

                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Weight<span class="text-danger">*</span>@error('weight') <small
                                class="text-danger">{{ $message }}</small> @enderror</label>
                                <input type="text" class="form-control shadow-none" name="weight" value="{{$item->weight}}">
                            </div>

                            <hr />
                            <h5>Description</h5>
                            <hr />

                            <div class="col-lg-12 mb-3 o-f-inp">
                                <label for="">Short Description<span class="text-danger">*</span>@error('short_description')
                                <small class="text-danger">{{ $message }}</small> @enderror</label>
                                <textarea class="form-control shadow-none"
                                    name="short_description">{{$item->short_description}}</textarea>
                            </div>

                            <div class="col-lg-12 mb-3 o-f-inp">
                                <label for="">Detailed Description<span
                                        class="text-danger">*</span>@error('detail_description') <small
                                        class="text-danger">{{ $message }}</small> @enderror</label>
                                <textarea class="form-control shadow-none"
                                    name="detail_description">{{$item->detail_description}}</textarea>
                            </div> --}}



                            <div class="col-lg-12">
                                <button type="submit" class="submit-btn">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>


    </section>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function() {

            let selectedCategory = $('#category').val();
            let selectedSubCategory = "{{ $item->sub_category_id ?? '' }}";

            if (selectedCategory) {
                loadSubCategories(selectedCategory, selectedSubCategory);
            }

            $('#category').on('change', function() {
                let categoryId = $(this).val();
                loadSubCategories(categoryId, null);
            });

            function loadSubCategories(categoryId, selectedSubCategory = null) {

                $('#sub_category').html('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('admin.get.subcategories') }}",
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(res) {
                            $('#sub_category').empty()
                                .append('<option value="">--- Select ---</option>');

                            $.each(res, function(key, value) {
                                $('#sub_category').append(
                                    `<option value="${value.id}" 
                                                            ${selectedSubCategory == value.id ? 'selected' : ''}>
                                                            ${value.name}
                                                        </option>`
                                );
                            });
                        }
                    });
                } else {
                    $('#sub_category').html('<option value="">--- Select ---</option>');
                }
            }

        });

        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', 'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        function calculatePricing() {
            let sellingPrice = parseFloat($('#selling_price').val()) || 0;
            let commissionPercent = parseFloat($('#commission_factor').val()) || 0;
            let taxPercent = parseFloat($('#tax_group').val()) || 0;

            let commissionAmount = (sellingPrice * commissionPercent) / 100;

            let taxAmount = (sellingPrice * taxPercent) / 100;

            let totalPrice = sellingPrice + taxAmount;

            $('#commission_amount').val(commissionAmount.toFixed(2));
            $('#tax_amount').val(taxAmount.toFixed(2));
            $('#total_price').val(totalPrice.toFixed(2));
        }

        $('#selling_price, #commission_factor, #tax_group').on('input change', function() {
            calculatePricing();
        });

        $(document).ready(function() {
            calculatePricing();
        });
    </script>
    <script>
        $(document).ready(function() {

            function togglePlanner() {
                if ($('#planner_commission').is(':checked')) {
                    $('.planner-fields').removeClass('d-none');
                    $('[name="planner_iv_percentage"]').attr('required', true);
                } else {
                    $('.planner-fields').addClass('d-none');
                    $('[name="planner_iv_percentage"]').removeAttr('required');
                }
            }

            function toggleProduction() {
                if ($('#production_commission').is(':checked')) {
                    $('.production-fields').removeClass('d-none');
                    $('[name="production_iv_percentage"]').attr('required', true);
                } else {
                    $('.production-fields').addClass('d-none');
                    $('[name="production_iv_percentage"]').removeAttr('required');
                }
            }

            togglePlanner();
            toggleProduction();

            $('#planner_commission').change(togglePlanner);
            $('#production_commission').change(toggleProduction);

        });
    </script>
@endsection
