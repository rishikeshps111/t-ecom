<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        h3 {
            text-align: center;
        }

        .header-container {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .logo {
            max-width: 150px;
            /* Adjust size as needed */
            height: auto;
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
                    style="display: block; margin: 0 auto; width:180px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width:180px;">
            @endif

            <div style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px;">
                {!! $biller->address ?? 'Not Available' !!}
            </div>
        </div>
    @endif

    <h2 style="text-align: center; margin-top: 10px; font-size:27px;">
        Planner Commission Report
    </h2>

    <p>
        Date Of Report : {{ $reportDate }}
    </p>

    <p>
        CO/Firm :
        {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }}
    </p>

    <p>
        Planner :
        {{ $planner ? $planner->name : 'All' }}
    </p>

    <p>
        Period :
        {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d/m/y') : '-' }}
        -
        {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d/m/y') : '-' }}
    </p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>INV NUM</th>
                <th>Customer Name</th>
                <th>PV</th>
                <th>Paid</th>
                <th>IV</th>
                <th>Com</th>
            </tr>
        </thead>

        <tbody>

            @foreach($records as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td>
                            {{ $row->invoice->invoice_number ?? '-' }}
                        </td>

                        <td>
                            {{ $row->invoice->quotation->workPlan->company->company_name ?? '-' }}
                        </td>

                        <td>
                            {{ $row->invoice->grant_total ?? '-' }}
                        </td>

                        <td>
                            {{ $row->invoice->paid_amount ?? '-' }}
                        </td>

                        <td>
                            {{ $row->invoice->grant_total !== null && $row->invoice->paid_amount !== null
                ? number_format(($row->invoice->paid_amount - ($row->disbursement ?? 0)) / 1.08 * 0.7, 2)
                : '-' }}
                        </td>

                        <td>
                            {{ number_format($row->amount, 2) }}
                        </td>
                    </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right; font-weight:bold;">
                    Total
                </td>

                <td style="font-weight:bold;">
                    RM {{ number_format($totalAmount, 2) }}
                </td>

            </tr>
        </tfoot>
    </table>

    {{-- <p>
        PV - Total INV Amount
    </p>
    <p>
        IV - (Paid Amount - Disbursement)/1.08%(STT)*70%
    </p>
    <p>
        COM - IV * Planner PERC
    </p> --}}

</body>

</html>