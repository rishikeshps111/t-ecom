@extends('admin.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Business Categories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Business Category</li>
        </ol>
    </nav>
</div>

<section class="section dashboard section-top-padding">

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="main-table-container">
                <form action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Code <span class="text-danger">*</span>@error('code') <small class="text-danger">{{ $message }}</small> @enderror</label>
                            <input type="text" name="code" class="form-control shadow-none" value="{{$code}}" readonly>
                        </div>                       
                         <div class="col-lg-4 mb-3 o-f-inp">
                            <label for=""> Category Name <span class="text-danger">*</span>@error('name') <small class="text-danger">{{ $message }}</small> @enderror</label>
                            <input type="text" name="name" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp file-input">
                            <label for="">Upload Image </label>
                            <input type="file" name="image" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-4 mb-3 o-f-inp">
                            <label for="">Status </label>
                            <select name="status" id="status" class="form-select shadow-none">
                                <option value="">--- Select ---</option>
                                <option value="1" selected>Active</option>
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