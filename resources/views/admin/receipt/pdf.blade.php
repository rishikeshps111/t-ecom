<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt PDF</title>
    <style>
        /* Base */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            width: 100%;
            padding: 0px;
        }

        .card {
            border: 0px solid #ccc !Important;
            padding: 0px !Important;
            border-radius: 10px;
        }

        h3,
        h4,
        h6 {
            margin: 0;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mb-5 {
            margin-bottom: 20px;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table-sm th,
        .table-sm td {
            padding: 3px;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            color: #fff;
            font-size: 11px;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        /* Sections */
        .row {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .col-md-4,
        .col-md-6 {
            padding: 0 10px;
        }

        .col-md-4 {
            width: 33.333%;
        }

        .col-md-6 {
            width: 50%;
        }

        .justify-content-end {
            justify-content: flex-end;
            display: flex;
        }

        .preview-container-center p {
            font-size: 16px;
            margin: 5px 0;
        }

        .p-tag p {
            color: #000;
            font-weight: 500;
            font-size: 15px;
        }

        .preview-container-center h2 {
            font-size: 27px;
            margin: 10px 0;
            color: #000;
            font-weight: 600;
            margin-top: 30px;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .preview-container-center-two {
            width: 50%;
        }

        .preview-container-center-two ul {
            margin: 0;
            list-style: none;
            padding: 0;
        }

        .preview-container-center-two ul li {
            margin: 5px 0;
            font-size: 15px;
            font-weight: 500;
            color: #000;
        }

        .preview-container-center-bottom table tr th {
            border: 0 !important;
            border-top: 1px solid #222 !important;
            border-bottom: 1px solid #222 !important;
            padding: 5px;
            background-color: transparent;
            font-size: 16px;
            text-align: center;
        }

        .preview-container-center-bottom table tr td {
            border: 0;
            padding: 5px;
            background-color: transparent;
            font-size: 16px;
            text-align: center;
        }

        .container {
            padding: 0px !Important;
            margin: unset !Important;
            max-width: 100% !important;
            width: 100% !important;

        }

        .card {
            margin-left: -0px !Important;
        }
           .p-tag p{
            text-align:center;
            margin-top:-15px;
        }
        .center-logo{
            display:flex;
            justify-content:center;
            align-items:center !Important;
        }
    </style>
</head>

<body>
    <div class="container my-5" id="printArea">
        <div class="card shadow-sm" style="padding:0 !important;">
            <div class="card-body" style="padding:0 !important;">

                {{-- Logo --}}
                <div style="margin-bottom: 20px;" class="center-logo">
                    @php
                        // Use the receipt's invoice relationship to find the biller profile
                        $biller = $receipt->invoice?->quotation?->workPlan?->totalGroup?->billerProfile;
                        $headerPath = $biller?->receipt_header;

                        // Determine the full system path or fallback to default
                        if ($headerPath && file_exists(public_path('storage/' . $headerPath))) {
                            $path = public_path('storage/' . $headerPath);
                        } else {
                            $path = public_path('assets/images/default-logo.png');
                        }

                        // Convert image to Base64 for PDF rendering stability
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = @file_get_contents($path);
                        $base64 = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : null;
                    @endphp
                    @if($base64)
                        <img src="{{ $base64 }}" style="max-width: 220px; height: auto;">
                    @endif
                </div>


                {{--
                <hr> --}}

                <div class="d-flex justify-content-start align-items-center mb-5">
                    <div class="d-flex align-items-start gap-3">
                        {{-- Company Logo --}}
                        <div class="preview-container-center-left p-tag">
                            <p class="mb-0">
                                {!!  $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->address ?? '' !!}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-start align-items-center mb-5">
                    <div class="d-flex align-items-start gap-3">
                        {{-- Company Logo --}}
                        <div class="preview-container-center">
                            <h2 class="fw-bold mb-1">
                                Payment Receipt:{{ $receipt->custom_payment_id ?? '' }}
                            </h2>

                        </div>
                    </div>
                    <div class="flex-between">
                        <div class="preview-container-center-two">
                            <ul>

                                <li> Payment Date:
                                    <span>{{  $receipt->created_at->format('d M Y') }}</span>
                                </li>
                                <li> Customer:
                                    <span>{{  $receipt->invoice->quotation->workPlan->company->company_name ?? '' }}</span>
                                </li>
                                <li> Payment Amount: <span> RM{{  $receipt->amount ?? '-' }}</span></li>
                            </ul>

                        </div>
                        <div class="preview-container-center-two">
                            <ul>
                                <li></li>
                                <li>Payment Method:
                                    <span> {{ $receipt->payment_method ?? '-' }}</span>
                                </li>
                                <li>INV Number: <span>{{ $receipt->invoice->invoice_number ?? '-' }}</span></li>
                            </ul>

                        </div>
                    </div>

                </div>
                <div class="preview-container-center-bottom">
                    <div class="table-responsive mb-5">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice Date</th>
                                    <th class="text-start">Invoice Number</th>
                                    <th>Reference</th>
                                    <th>Original Amount</th>
                                    <th>Amount Paid</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $receipt->invoice->invoice_date->format('d M Y') }}</td>
                                    <td class="text-start">{{ $receipt->invoice->invoice_number ?? '-' }}</td>
                                    <td></td>
                                    <td>{{ $receipt->invoice->grant_total }}</td>
                                    <td>{{ $receipt->amount }}</td>
                                    <td>{{ $receipt->invoice->balance_amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</body>

</html>