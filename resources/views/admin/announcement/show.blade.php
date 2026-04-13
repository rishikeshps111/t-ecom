@extends('admin.layouts.app')

@section('title')
    Announcement Details
@endsection

@section('style')
    @include('admin.scripts.css')
@endsection

@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h3>Announcement Details</h3>
                    <a href="{{ route('admin.announcements.index') }}" class="btn-back-cs">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container p-4">
                    @include('admin.announcement.view')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
@endsection