<!DOCTYPE html>
<html>

<head>
    <title>Consolidated Report</title>
    <style>
        body {
            font-family: DejaVu Sans;
        }

        .container-pd {
            width: 100%;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            font-size: 14px;
        }

        h2 {
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
                    style="display: block; margin: 0 auto; width:200px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width:200px;">
            @endif

            <div
                style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px; font-size:12.5px">
                {!! $biller->address ?? 'Not Available' !!}
            </div>
        </div>
    @endif

    <h2 style="text-align: center; margin-top: 10px; font-size:25px;">
        Consolidated Report
    </h2>

    <p style="font-size:14px">
        <strong>Total Group:</strong>
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }} <br>
        <strong>From:</strong> {{ $from_date ?? '-' }} <br>
        <strong>To:</strong> {{ $to_date ?? '-' }} <br>
        <strong>Month:</strong> {{ $month ?? '-' }} <br>
    </p>

    <table width="100%" cellspacing="0" cellpadding="5">
        <tr>
            <td class="box">Total Work Order Amount: RM {{ number_format($summary['totalAmount'], 2) }}</td>
            <td class="box">Total Quotation Amount: RM {{ number_format($summary['totalQuotation'], 2) }}</td>
        </tr>
        <tr>
            <td class="box">Total Paid Amount: RM {{ number_format($summary['totalPaid'], 2) }}</td>
            <td class="box">Total Balance Amount: RM {{ number_format($summary['totalBalance'], 2) }}</td>
        </tr>
        <tr>
            <td class="box">Total Receipt Amount: RM {{ number_format($summary['totalReceipt'], 2) }}</td>
            <td class="box">Total Credit Note Amount: RM {{ number_format($summary['totalCredit'], 2) }}</td>
        </tr>
        <tr>
            <td class="box">Total Planner Commission: RM {{ number_format($summary['totalPlanner'], 2) }}</td>
            <td></td>
        </tr>
    </table>
</body>

</html>