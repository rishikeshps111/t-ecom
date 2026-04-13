@extends('admin.layouts.app')
@section('content')
<style>
    .card{
        border-radius:15px !important;
    }
    .card-body{
        padding:0 15px;
    }
    .card h6{
        color:#000 !important;
        font-size:15px !important;
        
    }
    .card span{
        color:#000 !important;
        font-size:25px !important;
    }
    .card i{
           width: 60px;
    height: 60px;
    background-color: #c2230a;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    color: #fff ! Important;
    font-size: 23px;
    }
</style>

    <section class="section dashboard section-top-padding">
        <div class="container">
            <div class="row">

                <!-- Total Customers -->
                @role('Super Admin')
                <div class="col-md-6 mb-2">
                    <a href="{{ route('admin.customers.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-users fa-2x text-primary"></i>
                                <div>
                                    <h6 class="mb-1 text-primary fw-semibold" style="font-size:0.9rem;">Total Cus Users</h6>
                                    <span class="fw-bold text-primary"
                                        style="font-size:1.3rem;">{{ $totalCustomers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endrole
                <!-- Total Companies -->
                @php
                    $user = auth()->user();
                @endphp

                <div class="col-md-6 mb-2">
                    @if ($user && !$user->hasAnyRole(['Planner', 'Corp User']))
                        <a href="{{ route('admin.manage.company') }}" class="text-decoration-none">
                    @endif
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-building fa-2x text-success"></i>
                                <div>
                                    <h6 class="mb-1 text-success fw-semibold" style="font-size:0.9rem;">Total Customers</h6>
                                    <span class="fw-bold text-success"
                                        style="font-size:1.3rem;">{{ $totalCompanies ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($user && !$user->hasAnyRole(['Planner', 'Corp User']))
                            </a>
                        @endif
                </div>

                <div class="col-md-4">
                </div>

                <div class="col-md-12 justify-content-center d-flex mb-3">
                    <form id="dateFilterForm" class="row g-2 w-100">

                        <div class="col-md-6 o-f-inp">
                            <label for="fromDate" class="form-label">From</label>
                            <input type="date" id="fromDate" name="from_date" class="form-control"
                                value="{{  now()->subDays(30)->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-6 o-f-inp">
                            <label for="toDate" class="form-label">To</label>
                            <input type="date" id="toDate" name="to_date" class="form-control"
                                value="{{  now()->format('Y-m-d') }}">
                        </div>
                    </form>
                </div>

                <!-- Total Invoices -->
                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.invoices.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-file-invoice-dollar fa-2x text-warning"></i>
                                <div>
                                    <h6 class="mb-1 text-warning fw-semibold" style="font-size:0.9rem;">Invoices</h6>
                                    <span class="fw-bold text-warning" style="font-size:1.3rem;"
                                        id="totalInvoices">{{ $totalInvoices ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.work-orders.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-file-signature fa-2x text-danger"></i>
                                <div>
                                    <h6 class="mb-1 text-danger fw-semibold" style="font-size:0.9rem;">Work Orders</h6>
                                    <span class="fw-bold text-danger" style="font-size:1.3rem;"
                                        id="totalWorkOrders">{{ $totalWorkOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Quotations -->
                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.quotations.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-file-signature fa-2x text-danger"></i>
                                <div>
                                    <h6 class="mb-1 text-danger fw-semibold" style="font-size:0.9rem;">Quotations</h6>
                                    <span class="fw-bold text-danger" style="font-size:1.3rem;"
                                        id="totalQuotations">{{ $totalQuotations ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Payments -->
                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.payments.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-credit-card fa-2x text-info"></i>
                                <div>
                                    <h6 class="mb-1 text-info fw-semibold" style="font-size:0.9rem;">OR Original Receipts
                                    </h6>
                                    <span class="fw-bold text-info" style="font-size:1.3rem;"
                                        id="totalPayments">{{ $totalPayments ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Documents -->
                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.documents.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-file-alt fa-2x text-secondary"></i>
                                <div>
                                    <h6 class="mb-1 text-secondary fw-semibold" style="font-size:0.9rem;">Company Documents
                                    </h6>
                                    <span class="fw-bold text-secondary"
                                        style="font-size:1.3rem;">{{ $totalDocuments ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Planner Documents -->
                <div class="col-lg-4 mb-2">
                    @if ($user && !$user->hasAnyRole(['Planner', 'Corp User']))
                        <a href="{{ route('admin.planner-documents.index') }}" class="text-decoration-none">
                    @endif
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-file-alt fa-2x text-secondary"></i>
                                <div>
                                    <h6 class="mb-1 text-secondary fw-semibold" style="font-size:0.9rem;">Planner Documents
                                    </h6>
                                    <span class="fw-bold text-secondary"
                                        style="font-size:1.3rem;">{{ $totalPlannerDocuments ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($user && !$user->hasAnyRole(['Planner', 'Corp User']))
                            </a>
                        @endif
                </div>

                <!-- Planner Documents -->
                <div class="col-lg-4 mb-2">
                    <a href="{{ route('admin.messages.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-inbox fa-2x text-primary"></i>
                                <div>
                                    @php
                                        $unreadItems = unreadMessagesByUser();
                                    @endphp
                                    <h6 class="mb-1 text-primary fw-semibold" style="font-size:0.9rem;">Messages
                                    </h6>
                                    <span class="fw-bold text-primary"
                                        style="font-size:1.3rem;">{{ $unreadItems->count() ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const fromInput = document.getElementById('fromDate');
        const toInput = document.getElementById('toDate');

        function fetchCounts() {
            axios.get('{{ route("admin.dashboard.counts") }}', {
                params: {
                    from_date: fromInput.value,
                    to_date: toInput.value
                }
            })
                .then(res => {
                    const data = res.data;
                    document.getElementById('totalInvoices').textContent = data.totalInvoices;
                    document.getElementById('totalQuotations').textContent = data.totalQuotations;
                    document.getElementById('totalPayments').textContent = data.totalPayments;
                    document.getElementById('totalWorkOrders').textContent = data.totalWorkOrders;

                })
                .catch(err => console.error(err));
        }

        // Trigger fetch whenever date changes
        fromInput.addEventListener('change', fetchCounts);
        toInput.addEventListener('change', fetchCounts);
    </script>
@endsection