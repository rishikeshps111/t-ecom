@extends('admin.layouts.app')
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>General Settings</h3>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="top-choose-box">
                    <form method="POST" action="{{ route('admin.system-setting.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 o-f-inp mb-2">
                                <label for="financial_year" class="form-label m-0">Financial Year <span
                                        class="text-danger">*</span>
                                    @error('financial_year') <small class="text-danger">{{ $message }}</small>
                                    @enderror</label>
                                <input type="text" class="form-control shadow-none" name="financial_year"
                                    value="{{ old('financial_year', system_setting('financial_year') ?? '2025') }}"
                                    @cannot('system-setting.edit') disabled @endcannot>
                            </div>
                            @can('system-setting.edit')
                                <div class="col-lg-12 ">
                                    <button type="submit"
                                        class="submit-btn mx-auto">{{ isset($prefix) ? 'Update' : 'Submit' }}</button>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection