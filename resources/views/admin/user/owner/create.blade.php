@extends('admin.layouts.app')
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>User Management <span>Add</span></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{route('admin.business.owner.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for=""> User Id <span class="text-danger">*</span></label>
                                <input type="text" name="user_id" class="form-control shadow-none" readonly
                                    value="{{$userId}}">
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for=""> Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control shadow-none">
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
                                    <input type="text" name="phone" class="form-control shadow-none"
                                        placeholder="Enter mobile number" maxlength="10"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Password <span class="text-danger">*</span></label>
                                <input type="text" name="password" class="form-control shadow-none">
                            </div>
                            <input type="hidden" name="role" value="2" />
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Assign Companies </label>
                                <select name="company" id="company" class="form-select shadow-none">
                                    <option value="">--- Select ---</option>
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
                            <div class="col-lg-4 mb-3 o-f-inp">
                                <label for="">Profile Image </label>
                                <input type="file" name="profile_image" class="form-control shadow-none">
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