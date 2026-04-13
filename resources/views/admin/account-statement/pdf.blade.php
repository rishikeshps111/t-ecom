<!DOCTYPE html>
<html>

<head>
    <title>Work Order Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }
    </style>
</head>

<body>

    @if(isset($totalGroup) && $totalGroup)
        @php
            $biller = $totalGroup?->billerProfile;
        @endphp

        <div class="header-container" style="text-align: center;">
            @if($biller && $biller->invoice_header)
                <img src="{{ public_path('storage/' . $biller->invoice_header) }}" alt="Company Logo"
                    style="display: block; margin: 0 auto; width:250px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width:250px;">
            @endif

            <div style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px;">
                {!! $biller->address ?? 'Not Available' !!}
            </div>
        </div>
    @endif

    <h2 style="text-align: center; margin-top: 10px; font-size:30px;">
        Work Order Report
    </h2>

    <p>
        <strong>Total Group:</strong>
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }}<br>
        <strong>From:</strong> {{ \Carbon\Carbon::parse($from_date)->format('d M Y') }}<br>
        <strong>To:</strong> {{ \Carbon\Carbon::parse($to_date)->format('d M Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>WO No</th>
                <th>Date</th>
                <th>Total Group</th>
                <th>Planner</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>QO No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->workplan_number }}</td>
                    <td>{{ $row->date->format('d M Y') }}</td>
                    <td>{{ $row->totalGroup->customer_name ?? '-' }}</td>
                    <td>{{ $row->planner->name ?? '-' }}</td>
                    <td>{{ $row->company->company_name ?? '-' }}</td>
                    <td>{{ $row->quotation->invoice->grant_total ?? '-' }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ $row->quotation->quotation_number ?? '-' }}</td>
                    <td>{{ $row->quotation->invoice->invoice_number ?? '-' }}</td>
                    <td>
                        {{ $row->quotation?->invoice?->invoice_date?->format('d M Y') ?? '-' }}
                    </td>
                    <td>{{ ucfirst($row->quotation->invoice->payment_status ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <h4 style="margin-top:20px;">
        Total Amount: RM {{ number_format($totalAmount, 2) }}
    </h4> --}}

</body>

</html>