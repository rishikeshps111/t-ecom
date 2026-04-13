@extends('admin.layouts.app')
@section('title')
    Account Statements
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <style>
        table tr th,
        table tr td {
            width: 50%;
        }
    </style>
    <style>
        /* Force the table to respect assigned widths and share space equally */
        #invoiceDetailsTable {
            table-layout: fixed;
            width: 100%;
        }

        #invoiceDetailsTable th,
        #invoiceDetailsTable td {
            width: 16.66%;
            /* 100% divided by 6 columns */
            word-wrap: break-word;
            /* Prevents long text from breaking layout */
            text-align: center;
        }

        .text-end-cstm {
            text-align: right !important;
            padding-right: 15px !important;
        }
    </style>
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Account Statements - Production Staff Yearly Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 mb-2">
                                    <div class="o-f-inp">
                                        <label>Year</label>
                                        <select id="filter-year" class="form-control shadow-none">
                                            @php
                                                $currentMonth = date('n'); // 1-12
                                                $currentYear = date('Y');

                                                // Determine ongoing FY start year
                                                $ongoingFY = $currentMonth >= 7 ? $currentYear : $currentYear - 1;

                                                // Prepare last 5 completed FYs
                                                $completedFYs = [];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    $completedFYs[] = $ongoingFY - $i;
                                                }
                                            @endphp

                                            {{-- Ongoing FY --}}
                                            @php
                                                $nextYear = $ongoingFY + 1;
                                            @endphp
                                            <option value="{{ $ongoingFY }}" selected>
                                                FY {{ $ongoingFY }} (Jan - Dec)
                                            </option>
                                            @php
                                                $currentYear = date('Y');
                                            @endphp

                                            @for ($i = 0; $i < 6; $i++)
                                                <option value="{{ $currentYear - $i }}" {{ $i == 0 ? 'selected' : '' }}>
                                                    FY {{ $currentYear - $i }} (Jan - Dec)
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                @if(!auth()->user()->hasRole('Production Staff'))
                                    <div class="col-lg-4 mb-2">
                                        <div class="o-f-inp">
                                            <label>Production Staff</label>
                                            <select id="filter-planner" class="form-control shadow-none">
                                                <option value="">-- Select Production Staff --</option>
                                                @foreach ($planners as $planner)
                                                    <option value="{{ $planner->id }}">{{ $planner->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else

                                    <div class="col-lg-4 mb-2">
                                        <div class="o-f-inp">
                                            <label>Production Staff</label>
                                            <select id="filter-planner" class="form-control shadow-none">
                                                <option value="{{ auth()->id() }}" selected>{{ auth()->user()->name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                @endif
                                <div class="col-lg-4 mb-2">
                                    <div class="o-f-inp">
                                        <label>Company</label>
                                        <select id="filter-company" class="form-control shadow-none">
                                            <option value="">All</option>
                                            @foreach ($totalGroups as $company)
                                                <option value="{{ $company->id }}"
                                                    data-logo="{{ $company->billerProfile?->invoice_header ? asset('storage/' . $company->billerProfile->invoice_header) : asset('assets/images/default-logo.png') }}">
                                                    {{ $company->customer_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2">
                                    <div class="buttons-tab-top pt-3">
                                        {{-- <button type="button" id="exportCsv" class="add-btn">
                                            Export CSV
                                        </button> --}}
                                        <button type="button" id="exportPdf" class="btn-back-cs">
                                            Export PDF
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table"
                                        class="align-middle mb-0 table table-striped tble-cstm mt-3 min-width-table">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Paid</th>
                                                <th>Unpaid</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Please select a year &
                                                    Production Staff</td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="table-footer" style="display: none;">
                                            <tr
                                                style="background-color: #eeeeee; border-top: 3px solid #bcbcbc; font-size: 1.05rem;">
                                                <td style="font-weight: 800; text-transform: uppercase;">Total</td>
                                                <td id="footer-paid" style="font-weight: 800;">0.00</td>
                                                <td id="footer-unpaid" style="font-weight: 800;">0.00</td>
                                                <td id="footer-total" style="font-weight: 800;">0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="mt-3 p-2" style="background:#dcdcdc; font-weight:600; display: none;">
                                    Year to Date Total :
                                    <span id="yearTotalPaid">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Monthly Invoice Details -->
    <div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="invoiceDetailsModalLabel">Invoice Details</h5>
                    <div>
                        <button id="printInvoiceBtn" class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-print"></i> Print / PDF
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="report-header mb-4">
                        <div class="header-container" style="display:flex; justify-content:center; align-items:center;">
                            <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                                style="width: 200px; margin-bottom: 10px;">
                        </div>
                        <h3 class="text-center fw-bold mb-3">Production Staff Monthly Statement</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Date of Report:</strong> <span
                                        id="modal-date-report">{{ date('d-m-Y') }}</span></p>
                                <p class="mb-1"><strong>Company:</strong> <span id="modal-company-name">All</span></p>
                                <p class="mb-1"><strong>Period:</strong> <span id="modal-period-text"></span></p>
                            </div>
                        </div>
                    </div>
                    <table id="invoiceDetailsTable" class="table table-bordered table-striped tble-cstm">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Work Order</th>
                                <th>Invoice No</th>
                                <th>OR</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceDetailsBody">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td colspan="4" class="text-end-cstm">Total</td>
                                <td id="modalGrandTotal" class="text-end-cstm">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#filter-year').select2({
                placeholder: "Select Year",
                allowClear: true
            });
            $('#filter-planner').select2({
                placeholder: "Select Planner",
                allowClear: true
            });
            $('#filter-company').select2({
                allowClear: true
            });
            fetchPlannerMonthlyReport();
            function fetchPlannerMonthlyReport() {
                let year = $('#filter-year').val();
                let planner_id = $('#filter-planner').val();
                let company_id = $('#filter-company').val();

                // Only fetch if both year and planner are selected
                if (!year || !planner_id) {
                    $('#table tbody').html(''); // Clear table if not selected
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.ps.monthly-invoices') }}",
                    type: "GET",
                    data: {
                        year,
                        planner_id,
                        company_id
                    },
                    success: function (res) {

                        let monthNames = [
                            "January", "February", "March", "April",
                            "May", "June", "July", "August",
                            "September", "October", "November", "December"
                        ];

                        let tableBody = '';
                        let grandPaid = 0;
                        let grandUnpaid = 0;
                        let grandTotal = 0;

                        for (let i = 1; i <= 12; i++) {

                            let monthData = res.find(m => m.month == i);

                            let paid = monthData ? parseFloat(monthData.paid) : 0;
                            let unpaid = monthData ? parseFloat(monthData.unpaid) : 0;
                            let total = monthData ? parseFloat(monthData.total) : 0;

                            grandPaid += paid;
                            grandUnpaid += unpaid;
                            grandTotal += total;

                            tableBody +=
                                `
                                                                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                                                                    <td>${monthNames[i - 1]}</td>

                                                                                                                                                                                                                                                                                    <td class="clickable paid" data-month="${i}">
                                                                                                                                                                                                                                                                                        ${paid.toFixed(2)}
                                                                                                                                                                                                                                                                                    </td>

                                                                                                                                                                                                                                                                                    <td class="clickable unpaid" data-month="${i}">
                                                                                                                                                                                                                                                                                        ${unpaid.toFixed(2)}
                                                                                                                                                                                                                                                                                    </td>

                                                                                                                                                                                                                                                                                    <td>
                                                                                                                                                                                                                                                                                        ${total.toFixed(2)}
                                                                                                                                                                                                                                                                                    </td>
                                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                                            `;
                        }

                        $('#table tbody').html(tableBody);
                        // Update Footer Values
                        $('#footer-paid').text(grandPaid.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('#footer-unpaid').text(grandUnpaid.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('#footer-total').text(grandTotal.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));

                        // Update the separate "Year to Date" span you had
                        $('#yearTotalPaid').text(grandPaid.toFixed(2));

                        // Show Footer
                        $('#table-footer').show();

                    }
                });
            }

            // Trigger on filters change
            $('#filter-year, #filter-planner, #filter-company').on('change', fetchPlannerMonthlyReport);

            $('#exportCsv').click(function () {

                let year = $('#filter-year').val();
                let planner = $('#filter-planner').val();
                let company = $('#filter-company').val();

                if (!year || !planner) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Filter',
                        text: 'Please select Year and Production Staff',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let url =
                    "{{ route('admin.ps.monthly-export') }}" +
                    "?year=" + year +
                    "&planner_id=" + planner +
                    "&company_id=" + company;

                window.open(url, '_blank');

            });

            $('#exportPdf').click(function () {

                let year = $('#filter-year').val();
                let planner = $('#filter-planner').val();
                let company = $('#filter-company').val();

                if (!year || !planner || !company) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Filter',
                        text: 'Please select Year, Production Staff And Company',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let url =
                    "{{ route('admin.ps.monthly-pdf') }}" +
                    "?year=" + year +
                    "&planner_id=" + planner +
                    "&company_id=" + company;

                window.open(url, '_blank');

            });

            $(document).on('click', '.clickable', function () {
                let type = $(this).hasClass('paid') ? 'paid' : 'unpaid';
                let month = $(this).data('month');
                let year = $('#filter-year').val();
                let planner_id = $('#filter-planner').val();
                let company_id = $('#filter-company').val();

                if (!company_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: 'Please select a company.',
                    });
                    return;
                }

                if (!year || !planner_id) return;
                let monthNames = [
                    "January", "February", "March", "April",
                    "May", "June", "July", "August",
                    "September", "October", "November", "December"
                ];
                // Open modal
                $('#invoiceDetailsModalLabel').text(
                    `${type.toUpperCase()} Invoices - ${monthNames[month - 1]}`);
                $('#modal-period-text').text(`${monthNames[month - 1]} ${year}`);
                let selectedCompany = $('#filter-company option:selected').text();
                $('#modal-company-name').text(selectedCompany === "" ? "All" : selectedCompany);

                let selectedOption = $('#filter-company option:selected');
                let logoUrl = selectedOption.attr('data-logo') || '{{ asset("assets/images/default-logo.png") }}';
                console.log("Logo URL:", logoUrl); // should log correct URL
                $('#invoiceDetailsModal .header-container img').attr('src', logoUrl);

                $('#invoiceDetailsBody').html(
                    '<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                $('#invoiceDetailsModal').modal('show');

                // Fetch details via AJAX
                $.ajax({
                    url: "{{ route('admin.account-statements.ps.monthly-invoices-details') }}",
                    type: 'GET',
                    data: {
                        year,
                        planner_id,
                        month,
                        type,
                        company_id
                    },
                    success: function (res) {
                        if (res.length > 0) {
                            let body = '';
                            let grandTotal = 0;

                            res.forEach(inv => {
                                if (inv.payments && inv.payments.length > 0) {
                                    inv.payments.forEach(p => {
                                        let amt = parseFloat(p.amount) || 0;
                                        grandTotal += amt;
                                        body +=
                                            `<tr>
                                                                                                                                                                <td>${inv.sl_no}</td>
                                                                                                                                                                <td>${inv.work_plan || '-'}</td>                                                                                                                                  
                                                                                                                                                                <td>${inv.invoice_number}</td>
                                                                                                                                                                                                                                                                                                            <td>${p.or}</td>
                                                                                                                                                                                                                                                                                                            <td>${parseFloat(p.amount).toFixed(2)}</td>
                                                                                                                                                                            <td><span class="badge ${inv.type === 'paid' ? 'bg-success' : 'bg-warning'} text-capitalize">${inv.type}</span></td>                                                                                                                             </tr>`;
                                    });
                                }
                            });

                            $('#invoiceDetailsBody').html(body);
                            $('#modalGrandTotal').text(grandTotal.toFixed(2));
                        } else {
                            $('#invoiceDetailsBody').html('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                            $('#modalGrandTotal').text('0.00');
                        }
                    }
                });
            });


            $('#printInvoiceBtn').on('click', function () {
                // Clone modal body
                let modalContent = $('#invoiceDetailsModal .modal-body').clone();

                // Get actual logo src from modal
                let logoSrc = $('#invoiceDetailsModal .header-container img').attr('src');

                // Replace img src in cloned content
                modalContent.find('.header-container img').attr('src', logoSrc);

                // Get HTML as string after updating src
                let htmlContent = modalContent.html();

                // Open print window
                let printWindow = window.open('', '_blank', 'width=900,height=600');
                printWindow.document.write(`
                                <html>
                                <head>
                                    <title>Monthly Invoice</title>
                                    <style>
                                        body { font-family: Arial, sans-serif; padding: 20px; }
                                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                                        th { background-color: #f8f9fa; }
                                        .text-center { text-align: center; }
                                        .text-end-cstm { text-align: right; }
                                        .badge { padding: 5px 8px; border-radius: 4px; color: #fff; }
                                        .bg-success { background-color: #28a745; }
                                        .bg-warning { background-color: #ffc107; color: #000; }
                                        img { max-height: 80px; display: block; margin: 0 auto; }
                                    </style>
                                </head>
                                <body>
                                    ${htmlContent} <!-- now includes correct img src -->
                                </body>
                                </html>
                            `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            });

        });
    </script>
@endsection