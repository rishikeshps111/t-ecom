@extends('admin.layouts.app')
@section('content')

<section class="section dashboard section-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>User Management <span>Edit</span></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="main-table-container">
                <form action="{{route('admin.business.owner.update',$user->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> User Id <span class="text-danger">*</span></label>
                            <input type="text" name="user_id" class="form-control shadow-none" readonly value="{{$user->user_id}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control shadow-none" value="{{$user->name}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control shadow-none" value="{{$user->email}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control shadow-none" value="{{$user->phone}}">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Password <span class="text-danger">*</span></label>
                            <input type="text" name="password" class="form-control shadow-none" value="{{$user->show_password}}">
                        </div>
                        
                        <input type="hidden" name="role" value="2"/>
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
                                <option value="1" {{ isset($user) && $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ isset($user) && $user->status == 0 ? 'selected' : '' }}>Inactive</option>

                            </select>
                        </div>
                         <div class="col-lg-4 mb-3 file-input">
                            <label for="">Profile Image </label>
                            <input type="file" name="profile_image" class="form-control shadow-none">
                            @if($user->profile_image)
                                <img src="{{asset($user->profile_image)}}"/>
                            @endif
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