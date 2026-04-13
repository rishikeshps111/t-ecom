@extends('admin.layouts.app')
@section('title')
    Work Order Details
@endsection

@section('style')
    @include('admin.scripts.css')
    <style>
        .detail-label {
            font-weight: 600;
        }

        .detail-value {
            word-wrap: break-word;
        }

        .detail-row {
            margin-bottom: 15px;
        }

        .attachment-preview img {
            max-width: 200px;
            max-height: 200px;
        }

        .attachment-preview a {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Work Order Details</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                {{-- Top Row: Work Plan Number, Date, Status --}}
                <div class="row detail-row">
                    <div class="col-lg-4">
                        <span class="detail-label">Work Order Number:</span>
                        <span class="detail-value">{{ $workPlan->workplan_number }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $workPlan->date->format('d M Y') }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">{{ ucfirst($workPlan->status) }}</span>
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="row detail-row">
                    <div class="col-lg-4">
                        <span class="detail-label">Customer Name:</span>
                        <span class="detail-value">{{ $workPlan->company->company_name ?? '-' }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $workPlan->company->email_address ?? '-' }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $workPlan->company->mobile_no ?? '-' }}</span>
                    </div>
                </div>

                {{-- Type, Total Group, Planner --}}
                <div class="row detail-row">
                    <div class="col-lg-4">
                        <span class="detail-label">WP Type:</span>
                        <span class="detail-value">{{ $workPlan->companyType->name ?? '-' }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Total Group:</span>
                        <span class="detail-value">{{ $workPlan->totalGroup->customer_name ?? '-' }}</span>
                    </div>
                    <div class="col-lg-4">
                        <span class="detail-label">Planner:</span>
                        <span class="detail-value">{{ $workPlan->planner->name ?? '-' }}</span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="row detail-row">
                    <div class="col-lg-12">
                        <span class="detail-label">Description:</span>
                        <p class="detail-value">{!! $workPlan->description !!}</p>
                    </div>
                </div>

                {{-- Attachment --}}
                <div class="row detail-row">
                    <div class="col-lg-12">
                        <span class="detail-label">Attachment:</span>
                        <div class="attachment-preview mt-2">
                            @if($workPlan->attachment)
                                @php
                                    $fileExt = pathinfo($workPlan->attachment, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/' . $workPlan->attachment) }}" alt="Attachment">
                                @elseif($fileExt === 'pdf')
                                    <a href="{{ asset('storage/' . $workPlan->attachment) }}" target="_blank">View PDF</a>
                                @else
                                    <a href="{{ asset('storage/' . $workPlan->attachment) }}" target="_blank">View File</a>
                                @endif
                            @else
                                <span>-</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Back Button --}}
                <div class="mt-3">
                    <a href="{{ route('admin.work-plans.index') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts.script')
@endsection