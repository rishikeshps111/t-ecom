<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;

        }

        /* New styles for the logo section */
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
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

    <h2 class="title" style="text-align: center; margin-top: -10px; font-size:25px; ">
        @if (isset($totalGroup) && $totalGroup)
            {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All Reports' : $totalGroup->customer_name ?? 'N/A' }}
        @endif
    </h2>

    <p style="width: 100%; margin-top:25px;">
        <span style="display: inline-block; width: 50%;">
            {{-- @if (isset($totalGroup) && $totalGroup)
                <b>Total Group:</b>
                {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : $totalGroup->customer_name ?? 'N/A' }}
            @endif --}}
        </span>

        <span style="display: inline-block; width: 20%; text-align: center;">
            <b>From:</b> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
        </span>

        <span style="display: inline-block; width: 20%; text-align: right;">
            <b>To:</b> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </span>
    </p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>INV Number</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Paid Amount</th>
                <th>Balance Amount</th>
                <th>Planner</th>
                <th>Com</th>
                <th>Pro Staff</th>
                <th>PI</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($records as $record)
                    <tr>
                        <td>{{ $record?->quotation?->invoice?->invoice_date->format('d/m/Y') }}</td>
                        <td>{{ $record?->quotation?->invoice?->invoice_number ?? '-' }}</td>
                        <td>{{ $record?->company?->company_name ?? '-' }}</td>
                        <td>{{ $record?->quotation?->invoice?->grant_total ?? '-' }}</td>
                        <td>{{ $record?->quotation?->invoice?->paid_amount ?? '-' }}</td>
                        <td>{{ $record?->quotation?->invoice?->balance_amount ?? '-' }}</td>
                        <td>{{ $record?->planner?->name ?? '-' }}</td>
                        <td>{{ $record?->quotation?->invoice?->planner_commission ?? '-' }}</td>
                        <td>{{ $record?->company?->productionStaff?->name ?? '-' }}</td>
                        <td>
                            {{ $record?->quotation?->invoice?->grant_total !== null &&
                $record?->company?->productionStaff?->production_c_percentage !== null
                ? ($record->quotation->invoice->grant_total * $record->company->productionStaff->production_c_percentage) / 100
                : '-' }}
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>