<!DOCTYPE html>
<html>

<head>
    <title>Invoice Report</title>
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
        Invoice Report
    </h2>

    <p>
        <strong>Total Group:</strong>
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }}<br>
        <strong>From:</strong> {{ $from_date ? \Carbon\Carbon::parse($from_date)->format('d M Y') : '-' }}<br>
        <strong>To:</strong> {{ $to_date ? \Carbon\Carbon::parse($to_date)->format('d M Y') : '-' }}<br>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>WO No</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Amount</th>
                <th>OR No</th>
                <th>OR Amount</th>
                <th>Status</th>
                <th>CR No</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->quotation?->workPlan?->workplan_number }}</td>
                    <td>{{ $row->invoice_number }}</td>
                    <td>{{ $row->invoice_date?->format('d M Y') }}</td>
                    <td>{{ $row->grant_total }}</td>
                    <td>
                        {{ $row->payments->pluck('custom_payment_id')->implode(', ') ?: '-' }}
                    </td>
                    <td>{{ $row->payments->sum('amount') }}</td>
                    <td>{{ ucfirst($row->payment_status) }}</td>
                    <td>{{ $row->creditNotes->pluck('credit_note_number')->implode(', ') ?: '-' }}</td>
                    <td>{{ $row->creditNotes->sum('amount') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <h4 style="margin-top:20px;">
        Total Invoice: RM {{ number_format($totalAmount, 2) }} <br>
        Total Paid: RM {{ number_format($totalPaid, 2) }} <br>
        Total Balance: RM {{ number_format($totalBalance, 2) }}
    </h4> --}}

</body>

</html>