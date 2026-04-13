@extends('admin.layouts.app')
@section('title')
    Receipts
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>OR (original receipt ) of {{ $invoice->invoice_number }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-date">Filter by Receipt Date</label>
                                <input type="date" id="filter-date" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-number">Filter by Receipt No</label>
                                <input type="text" id="filter-number" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp d-none">
                                <label for="filter-payment">Filter by Payment Method</label>
                                <select id="filter-payment" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="cash">
                                        Cash
                                    </option>
                                    <option value="card">
                                        Card
                                    </option>
                                    <option value="bank_transfer">
                                        Bank Transfer
                                    </option>
                                    <option value="visa_card">
                                        Visa Card
                                    </option>
                                    <option value="master_card">
                                        Master Card
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp d-none">
                                <label for="filter-status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="closed">Closed</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end mt-3">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <a href="{{ route('admin.invoices.index') }}" type="button" class="btn btn-danger ms-2">Back</a>
                        </div>
                        @php
                            $totalPaid = $invoice->payments()->sum('amount');
                        @endphp
                        @if($totalPaid < $invoice->grant_total)
                            <div class="col-lg-3 ms-auto mt-4">
                                <a href="{{ route('admin.receipts.create') }}?inv_id={{ $invoice->id }}" class="add-btn">
                                    Add OR
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Receipt No</th>
                                                <th class="text-center">Receipt Date</th>
                                                {{-- <th class="text-center">Payment Method</th> --}}
                                                <th class="text-center ">Amount</th>
                                                <th class="text-center ">Remark</th>
                                                {{-- <th class="text-center ">Status</th> --}}
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.receipt.js')
@endsection