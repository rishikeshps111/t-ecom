@extends('admin.layouts.app')
@section('content')
@php
    $passwordTab = session('profile_tab') === 'password'
        || $errors->has('current_password')
        || $errors->has('new_password')
        || $errors->has('new_password_confirmation');
    $profileTab = !$passwordTab;
    $profilePhone = preg_replace('/^\+?60/', '', old('phone', $user->phone ?? ''));
@endphp

<section class="section-profile dashboard section-top-padding">

    <div class="row">
        <div class="col-xl-8">

            <div class="card bx_none profile-box">
                <div class="card-body pt-3 profile-container">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered justify-content-start" role="tablist">

                        <li class="nav-item ps-0 ms-0" role="presentation">
                            <button class="nav-link ms-0 {{ $profileTab ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="{{ $profileTab ? 'true' : 'false' }}" role="tab">Edit Profile</button>
                        </li>

                        <!-- <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#profile-edit">overview</button>
                  </li> -->

                        <!-- <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                  </li> -->

                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $passwordTab ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="{{ $passwordTab ? 'true' : 'false' }}" role="tab" tabindex="-1">Change Password</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade profile-overview {{ $profileTab ? 'active show' : '' }}" id="profile-overview" role="tabpanel">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                  
                            <form method="POST" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3 form-inp">
                                    <div class="col-lg-4">
                                        <label for="profileImage" class=" col-form-label">Profile Image @error('profile_image') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class=" d-flex align-items-center justify-content-start gap-3 profile-img">
                                            @if(!empty($user->profile_image))
                                            <img id="profileImagePreview" src="{{asset($user->profile_image)}}" alt="Profile">
                                            @else
                                            <img id="profileImagePreview" src="" alt="Profile" class="d-none">
                                            @endif
                                            <div class="pt-2">
                                                <label for="upload" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i></label>
                                                <input type="file" id="upload" name="profile_image" class="d-none" accept="image/png,image/jpeg">
                                                <input type="hidden" id="removeProfileImageInput" name="remove_profile_image" value="0">
                                                <button type="button" id="removeProfileImage" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></button>
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
                                        <input name="name" type="text" class="form-control shadow-none" id="fullName" value="{{ old('name', $user->name ?? '') }}">
                                    </div>


                                    <div class="col-lg-4 form-inp mb-2">
                                        <label for="Phone" class=" col-form-label">Phone <span class="text-danger">*</span>@error('phone') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+60</span>
                                            <input name="phone" type="text" class="form-control shadow-none" id="Phone" value="{{ $profilePhone }}" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-inp  mb-2">
                                        <label for="Email" class=" col-form-label">Email <span class="text-danger">*</span>@error('email') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <input name="email" type="email" class="form-control shadow-none" id="Email" value="{{ old('email', $user->email ?? '') }}">
                                    </div>


                                </div>

                                <div class="text-center d_flex">
                                    <button type="submit" class="submit-btn">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade {{ $passwordTab ? 'active show' : '' }}" id="profile-change-password" role="tabpanel">
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
                                            <input name="current_password" type="password" class="form-control shadow-none" id="currentPassword" autocomplete="current-password">
                                        </div>

                                    </div>
                                    <div class="col-lg-4 form-inp">
                                        <label for="newPassword" class=" col-form-label">New Password <span class="text-danger">*</span>@error('new_password') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="">
                                            <input name="new_password" type="password" class="form-control shadow-none" id="newPassword" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-inp">
                                        <label for="renewPassword" class=" col-form-label">Re-enter New Password <span class="text-danger">*</span>@error('new_password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <div class="">
                                            <input name="new_password_confirmation" type="password" class="form-control shadow-none" id="renewPassword" autocomplete="new-password">
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

@section('scripts')
<script>
    const uploadInput = document.getElementById('upload');
    const previewImage = document.getElementById('profileImagePreview');
    const removeButton = document.getElementById('removeProfileImage');
    const removeInput = document.getElementById('removeProfileImageInput');

    uploadInput?.addEventListener('change', function () {
        const file = this.files?.[0];

        if (!file || !previewImage) {
            return;
        }

        previewImage.src = URL.createObjectURL(file);
        previewImage.classList.remove('d-none');
        removeInput.value = '0';
    });

    removeButton?.addEventListener('click', function () {
        if (uploadInput) {
            uploadInput.value = '';
        }

        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('d-none');
        }

        removeInput.value = '1';
    });
</script>
@endsection
