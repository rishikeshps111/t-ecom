@extends('admin.layouts.app')
@section('content')

<section class="section dashboard section-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Dealer <span>Add</span></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="main-table-container">
                <form action="{{route('admin.dealer.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Dealer Code<span class="text-danger">*</span></label>
                            <input type="text" name="user_id" class="form-control shadow-none" readonly value="{{$userId}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Contact Person Name <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Phone <span class="text-danger">*</span></label>
                            <input type="hidden" name="country_code" value="+60">
                            <div class="input-group">
                                <span class="input-group-text">+60</span>
                                <input type="text"
                                    name="phone"
                                    class="form-control shadow-none"
                                    placeholder="Enter mobile number"
                                    maxlength="10"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control shadow-none">
                        </div>
                        <input type="hidden" name="role" value="4"/>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Assigned Item Categories</label>
                            <input type="text" name="assigned_item" class="form-control shadow-none">
                        </div>

                         <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Pricing Level</label>
                            <input type="text" name="pricing_level" class="form-control shadow-none">
                        </div>

                         <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Commission Factor (%)</label>
                            <input type="text" name="commission_factor" class="form-control shadow-none">
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Tax Group</label>
                            <input type="text" name="tax_group" class="form-control shadow-none">
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Quotation Access</label>
                             <select name="quotation_access" id="quotation_access" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>

                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Invoice View Access</label>
                             <select name="invoice_view" id="invoice_view" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>

                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Status </label>
                            <select name="status" id="status" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>

                            </select>
                        </div>
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