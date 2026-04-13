<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            margin: 20px;
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
    @if(isset($company) && $company)
        @php
            $biller = $company?->billerProfile;
        @endphp
        <div class="header-container" style="text-align: center;">
            @if($biller && $biller->invoice_header)
                {{-- Use public_path for local file access --}}
                <img src="{{ public_path('storage/' . $biller->invoice_header) }}" alt="Company Logo"
                    style="max-height: 80px; width: auto;">
            @else
                {{-- Ensure this path is correct relative to your public folder --}}
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="max-height: 80px; width: auto;">
            @endif
        </div>
        <div style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px;">
            {!! $biller->address ?? 'Not Available' !!}
        </div>
    @endif

    <h2 class="title" style="text-align:center;">
        {{ $title ?? 'Planner' }} Yearly Report
    </h2>

    <p style="width: 100%;">
        <span style="display: inline-block; width: 33%;">
            <b>Year:</b> {{ $fyLabel }}
        </span>

        <span style="display: inline-block; width: 33%; text-align: center;">
            {{-- @if(isset($company) && $company)
            <b>Company:</b> {{ $company->customer_name }}
            @endif --}}
        </span>

        <span style="display: inline-block; width: 32%; text-align: right;">
            <b>{{ $title ?? 'Planner' }}:</b> {{ $planner->name }}
        </span>
    </p>

    <p style="font-size: 10px; color: #666; text-align: right; margin-top: -10px;">
        Report Generated: {{ now()->format('d M Y, h:i A') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>COM</th>
                {{-- <th>Unpaid</th>
                <th>Total</th> --}}
            </tr>
        </thead>

        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['month'] }}</td>
                    <td>{{ $row['paid'] }}</td>
                    {{-- <td>{{ $row['unpaid'] }}</td>
                    <td>{{ $row['total'] }}</td> --}}
                </tr>
            @endforeach

            <tr>
                <td><b>Total</b></td>
                <td><b>{{ number_format($grandPaid, 2) }}</b></td>
                {{-- <td><b>{{ number_format($grandUnpaid, 2) }}</b></td>
                <td><b>{{ number_format($grandTotal, 2) }}</b></td> --}}
            </tr>
        </tbody>
    </table>

</body>

</html>