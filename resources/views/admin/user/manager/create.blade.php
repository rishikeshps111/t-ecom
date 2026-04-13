@extends('admin.layouts.app')
@section('content')

<section class="section dashboard section-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Document Manager <span>Add</span></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="main-table-container">
                <form action="{{route('admin.document_manager.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Employee ID<span class="text-danger">*</span></label>
                            <input type="text" name="user_id" class="form-control shadow-none" readonly value="{{$userId}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Full Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Mobile Number <span class="text-danger">*</span></label>
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
                            <label for="">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" class="form-control shadow-none">
                        </div>
                        <input type="hidden" name="role" value="6"/>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Document Category Access</label>
                            <select name="document_category" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Folder Access Rights</label>
                            <select name="folder_access" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Upload Permission</label>
                            <select name="document_upload" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Edit Permission</label>
                            <select name="document_edit" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Delete Permission</label>
                            <select name="document_delete" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Version Control Access</label>
                            <select name="verson_control" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label>Approval Authority</label>
                            <select name="approval_authority" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Document Visibility Scope</label>
                             <input type="text" name="task_scope" class="form-control shadow-none">
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