@extends('admin.layouts.app')
@section('content')

<section class="section-profile dashboard section-top-padding">

    <div class="row">
        <div class="col-xl-8">

            <div class="card bx_none profile-box">
                <div class="card-body pt-3 profile-container">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered justify-content-start" role="tablist">

                        <li class="nav-item ps-0 ms-0" role="presentation">
                            <button class="nav-link ms-0 active" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="true" role="tab">Edit Profile</button>
                        </li>

                        <!-- <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#profile-edit">overview</button>
                  </li> -->

                        <!-- <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                  </li> -->

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="false" role="tab" tabindex="-1">Change Password</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade profile-overview active show" id="profile-overview" role="tabpanel">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                  
                            <form method="POST" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3 form-inp">
                                    <div class="col-lg-4">
                                        <label for="profileImage" class=" col-form-label">Profile Image @error('profile_img') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class=" d-flex align-items-center justify-content-start gap-3 profile-img">
                                            @if(isset($user->profile_image))
                                            <img src="{{asset($user->profile_image)}}" alt="Profile">
                                            @endif
                                            <div class="pt-2">
                                                <label for="upload" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i></label>
                                                <input type="file" id="upload" name="profile_image" class="d-none"><a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="profile-preview">
                                            <ul>
                                                <li>Name : <span>{{$user->name ?? ''}}</span></li>
                                                <li>Phone : <span>{{$user->phone ?? ''}}</span></li>
                                                <li>E mail : <span>{{$user->email ?? ''}}</span></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-4 form-inp mb-2">
                                        <label for="fullName" class=" col-form-label"> Name <span class="text-danger">*</span>@error('name') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <input name="name" type="text" class="form-control shadow-none" id="fullName" value="{{$user->name ?? ''}}">
                                    </div>


                                    <div class="col-lg-4 form-inp mb-2">
                                        <label for="fullName" class=" col-form-label">Phone <span class="text-danger">*</span>@error('phone') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <input name="phone" type="text" class="form-control shadow-none" id="Phone" value="{{$user->phone ?? ''}}">
                                    </div>
                                    <div class="col-lg-4 form-inp  mb-2">
                                        <label for="fullName" class=" col-form-label">Email <span class="text-danger">*</span>@error('email') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <input name="email" type="email" class="form-control shadow-none" id="Email" value="{{$user->email ?? ''}}">
                                    </div>


                                </div>

                                <div class="text-center d_flex">
                                    <button type="submit" class="submit-btn">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="profile-change-password" role="tabpanel">
                            <!-- Change Password Form -->
                             @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                <div class="row mb-3 ">
                                    <div class="col-lg-4 form-inp">
                                        <label for="currentPassword" class=" col-form-label">Current Password <span class="text-danger">*</span>@error('current_password') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="">
                                            <input name="current_password" type="text" class="form-control shadow-none" id="currentPassword">
                                        </div>

                                    </div>
                                    <div class="col-lg-4 form-inp">
                                        <label for="newPassword" class=" col-form-label">New Password <span class="text-danger">*</span>@error('new_password') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="">
                                            <input name="new_password" type="text" class="form-control shadow-none" id="newPassword">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-inp">
                                        <label for="renewPassword" class=" col-form-label">Re-enter New Password <span class="text-danger">*</span>@error('new_password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="">
                                            <input name="new_password_confirmation" type="text" class="form-control shadow-none" id="renewPassword">
                                        </div>
                                    </div>

                                </div>

                                <div class="text-center d_flex">
                                    <button type="submit" class="submit-btn">Change Password</button>
                                </div>
                            </form><!-- End Change Password Form -->

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </div>



    </div>





</section>

@endsection