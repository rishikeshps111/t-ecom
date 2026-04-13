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
                <form action="{{route('admin.dealer.update',$dealer->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Dealer Code<span class="text-danger">*</span></label>
                            <input type="text" name="user_id" class="form-control shadow-none" readonly value="{{$dealer->user_id}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-none" value="{{$dealer->name}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Contact Person Name <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" class="form-control shadow-none" value="{{$dealer->contact_person}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control shadow-none" value="{{$dealer->contact_person}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control shadow-none" value="{{$dealer->phone}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control shadow-none" value="{{$dealer->address}}">
                        </div>
                        <input type="hidden" name="role" value="4"/>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Assigned Item Categories</label>
                            <input type="text" name="assigned_item" class="form-control shadow-none" value="{{$dealer->assigned_item}}">
                        </div>

                         <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Pricing Level</label>
                            <input type="text" name="pricing_level" class="form-control shadow-none" value="{{$dealer->pricing_level}}">
                        </div>

                         <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Commission Factor (%)</label>
                            <input type="text" name="commission_factor" class="form-control shadow-none" value="{{$dealer->commission_factor}}">
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Tax Group</label>
                            <input type="text" name="tax_group" class="form-control shadow-none" value="{{$dealer->tax_group}}">
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Quotation Access</label>
                             <select name="quotation_access" id="quotation_access" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes" {{ isset($dealer) && $dealer->quotation_access == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ isset($dealer) && $dealer->quotation_access == 'no' ? 'selected' : '' }}>No</option>

                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Invoice View Access</label>
                             <select name="invoice_view" id="invoice_view" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes" {{ isset($dealer) && $dealer->invoice_view == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ isset($dealer) && $dealer->invoice_view == 'no' ? 'selected' : '' }}>No</option>

                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Status </label>
                            <select name="status" id="status" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                 <option value="1" {{ isset($dealer) && $dealer->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ isset($dealer) && $dealer->status == 0 ? 'selected' : '' }}>Inactive</option>

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