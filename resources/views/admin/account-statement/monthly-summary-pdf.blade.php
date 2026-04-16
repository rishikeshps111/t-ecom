<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Monthly Summary PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            /* reduce from 12px */
        }

        th,
        td {
            padding: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* ðŸ”¥ key fix */
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 10px;
            /* reduce font */
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        h4 {
            margin: 0;
            padding: 0;
        }

        .summary {
            margin-bottom: 20px;
            margin-top: 10px;
        }

        .summary td {
            border: none;
            padding: 4px 0;
        }
    </style>
</head>

<body>
    @if (isset($totalGroup) && $totalGroup)
        @php
            $biller = $totalGroup?->billerProfile;
        @endphp

        <div class="header-container" style="text-align: center;">
            @if ($biller && $biller->invoice_header)
                <img src="{{ public_path('storage/' . $biller->invoice_header) }}" alt="Company Logo"
                    style="display: block; margin: 0 auto; width:200px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width:200px;">
            @endif

            <div style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px;">
                {!! $biller->address ?? 'Not Available' !!}
            </div>
        </div>
    @endif

    <h2 style="text-align: center; margin-top: 10px; font-size:24px; margin-bottom:10px;">
        Monthly Summary Report
    </h2>
    <h4 style="font-size:10px; margin-bottom:15px;">
        Month: {{ $month ?? 'All' }} <br>
        Total Group:
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : $totalGroup->customer_name ?? 'N/A' }}
    </h4>


    <table>
        <thead>
            <tr>
                <th>SL NO</th>
                <th>WO Number</th>
                <th>Customer Name</th>
                <th>Total Group</th>
                <th>Work Order Amount</th>
                <th>Invoice Amount</th>
                <th>Paid Amount</th>
                <th>Credit Note Amount</th>
                <th>Debit Note Amount</th>
                <th>Balance Amount</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->workplan_number ?? '-' }}</td>
                    <td>{{ $row->company?->company_name ?? '-' }}</td>
                    <td>{{ $row->totalGroup?->customer_name ?? '-' }}</td>
                    <td>RM {{ number_format($row->quotation?->grant_total ?? 0, 2) }}</td>
                    <td>RM {{ number_format($row->quotation?->invoice?->grant_total ?? 0, 2) }}</td>
                    <td>RM {{ number_format($row->quotation?->invoice?->paid_amount ?? 0, 2) }}</td>
                    <td>RM
                        {{ number_format($row->quotation?->invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0, 2) }}
                    </td>
                    <td>RM
                        {{ number_format($row->quotation?->invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0, 2) }}
                    </td>
                    <td>
                        @php
                            $invoice = $row->quotation?->invoice;
                            $balanceAmount = $invoice?->balance_amount ?? 0;
                            $creditTotal = $invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0;
                            $debitTotal = $invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0;
                            $netBalance = $balanceAmount != 0 ? $balanceAmount - $creditTotal + $debitTotal : 0;
                        @endphp
                        RM {{ number_format($netBalance, 2) }}
                    </td>
                    <td>{{ ucfirst($row->quotation?->invoice?->payment_status ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; font-size: 0 ; margin-top:25px;">

        <!-- Column 1 -->
        <div style="display: inline-block; width: 33.33%; vertical-align: top; font-size: 14px;">
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 10px 7px 0; font-weight:600;">
                Total Work Order Amount: <br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalAmount'], 2) }}</span></p>
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 10px 7px 0; font-weight:600;">
                Total Quotation Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalQuotation'], 2) }}</span></p>
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 10px 7px 0; font-weight:600;">
                Total Paid Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalPaid'], 2) }}</span></p>
        </div>

        <!-- Column 2 -->
        <div style="display: inline-block; width: 33.33%; vertical-align: top; font-size: 14px;">
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 0; font-weight:600;">
                Total Balance Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalBalance'], 2) }}</span></p>
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important;  margin:7px 0; font-weight:600;">
                Total Receipt Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalReceipt'], 2) }}</span></p>
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important;  margin:7px 0; font-weight:600;">
                Total Credit Note Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalCredit'], 2) }}</span></p>
        </div>

        <!-- Column 3 -->
        <div style="display: inline-block; width: 33.33%; vertical-align: top; font-size: 14px;">
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 0 7px 10px; font-weight:600;">
                Total Debit Note Amount:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalDebit'], 2) }}</span></p>
            @role(['Super Admin', 'Admin', 'Management Staff', 'Production Staff', 'Planner'])

            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 0 7px 10px; font-weight:600;">
                Total Planner Commission:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalPlanner'], 2) }}</span></p>
            <p
                style="padding-bottom:7px; border-bottom:1px solid #ccc; font-size:12px !important; margin:7px 0 7px 10px; font-weight:600;">
                Total Production Commission:<br> <span style="font-weight:400;">RM
                    {{ number_format($summary['totalProduction'], 2) }}</span></p>
            @endrole

        </div>

    </div>


</body>

</html>