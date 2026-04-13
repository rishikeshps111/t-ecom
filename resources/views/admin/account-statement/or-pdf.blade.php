<!DOCTYPE html>
<html>

<head>
    <title>OR Report</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
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
                    style="display: block; margin: 0 auto; width:220px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width:220px;">
            @endif

            <div style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px;">
                {!! $biller->address ?? 'Not Available' !!}
            </div>
        </div>
    @endif

    <h2 style="text-align: center; margin-top: 10px; font-size:28px;">
        Original Receipt Report
    </h2>

    <p>
        <strong>Total Group:</strong>
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }}<br>
        <strong>From:</strong> {{ $from_date ? \Carbon\Carbon::parse($from_date)->format('d M Y') : '-' }}<br>
        <strong>To:</strong> {{ $to_date ? \Carbon\Carbon::parse($to_date)->format('d M Y') : '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>SL NO</th>
                <th>WO</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Invoice Amount</th>
                <th>OR No</th>
                <th>OR Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->invoice?->quotation?->workPlan?->workplan_number }}</td>
                    <td>{{ $row->invoice?->invoice_number }}</td>
                    <td>{{ $row->created_at?->format('d M Y') }}</td>
                    <td>{{ $row->invoice?->grant_total }}</td>
                    <td>{{ $row->custom_payment_id }}</td>
                    <td>{{ $row->amount }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <h4 style="margin-top:20px;">
        Total Receipt Amount: RM {{ number_format($totalAmount, 2) }}
    </h4> --}}

</body>

</html>