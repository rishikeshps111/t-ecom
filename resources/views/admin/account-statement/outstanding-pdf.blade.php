<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 13.5px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
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
                    style="display: block; margin: 0 auto; width: 200px;">
            @else
                <img src="{{ public_path('assets/images/default-logo.png') }}" alt="Default Logo"
                    style="display: block; margin: 0 auto; width: 200px;">
            @endif
        </div>

        <div
            style="text-align: center; margin-top: -15px; border-bottom:1px solid #ccc; padding-bottom:5px; font-size:14.5px">
            {!! $biller->address ?? 'Not Available' !!}
        </div>
    @endif

    <h2 class="title" style="text-align: center; margin-top: 10px; font-size:25px; margin-bottom:20px;">
        Outstanding Report
    </h2>

    <div style="width: 100%; margin-bottom: 10px;">
        <span style="display:inline-block; font-size:14.5px">
            <strong>Total Group:</strong>
            {{ ($totalGroup->customer_name ?? 'N/A') === 'Default' ? 'All' : ($totalGroup->customer_name ?? 'N/A') }}
        </span>

        <span style="display:inline-block; margin-left:25px; font-size:13px">
            <strong>Customer:</strong>
            {{ $company->company_name ?? 'All' }}
        </span>

        <span style="display:inline-block; margin-left:25px; font-size:13px">
            <strong>Planner:</strong>
            {{ $planner->name ?? 'All' }}
        </span>

        {{-- <span style="display:inline-block; margin-left:25px; font-size:13px">
            <strong>Customer:</strong>
            {{ $cusUser->name ?? 'All' }}
        </span> --}}
    </div>
    <p style="width: 100%; margin-bottom: 40px; float:unset;font-size:13px">
        <span style="float:left; color:red;"><strong style="color:black;">From:</strong>
            {{ $request->from_date ?? '-' }}</span>
        <span style="float:left; color:red; padding-left:20px"><strong style="color:black;">To:</strong>
            {{ $request->to_date ?? '-' }}</span>
    </p>


    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                {{-- <th>Planner</th> --}}
                <th>Invoice</th>
                <th>OR</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>COMM</th>
                <th>PRODUCTION</th>
            </tr>
        </thead>

        <tbody>

            @foreach($collection as $i => $row)

                <tr>

                    <td style="text-align:center;">{{ $i + 1 }}</td>

                    <td style="text-align:center;">
                        {{ $row->invoice?->quotation?->workPlan?->company?->company_name }}
                    </td>

                    {{-- <td style="text-align:center;">
                        {{ $row->invoice?->quotation?->workPlan?->company?->planner?->user_code }}
                    </td> --}}

                    <td style="text-align:center;">{{ $row->invoice?->invoice_number }}</td>

                    <td style="text-align:center;">{{ $row->custom_payment_id }}</td>
                    <td style="text-align:center;">{{ $row->invoice?->grant_total }}</td>

                    <td style="text-align:center;">{{ $row->amount }}</td>


                    <td style="text-align:center;">{{ $row->invoice?->balance_amount }}</td>



                    @php

                        $plannerPercentage =
                            optional($row->invoice)->p_bill_percentage ?? 0;

                        $plannerAmount = $row->amount * ($plannerPercentage / 100);

                    @endphp

                    <td style="text-align:center;">
                        {{  number_format($plannerAmount, 2) ?? '' }}
                    </td>


                    @php
                        $psPercentage = optional(
                            optional(
                                optional(
                                    optional(
                                        optional($row->invoice)->quotation
                                    )->workPlan
                                )->company
                            )->productionStaff
                        )->production_c_percentage ?? 0;

                        $psAmount = $row->amount * ($psPercentage / 100);

                    @endphp

                    <td style="text-align:center;">
                        {{  number_format($psAmount, 2) ?? '' }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <br>

    <h4>Totals</h4>

    <div style="width: 100%;  margin-top: 20px; font-family: DejaVu Sans; font-size: 12px;">

        <!-- Total Amount Block (col-4) -->
        <div style=" padding: 10px;  text-align: center; float:left; width:30%; margin:0 5px; height:130px;">
            <div style="font-weight: bold; margin-bottom: 6px;">TOTAL AMOUNT:
                <span>{{ number_format($totalAmount, 2) }}</span>
            </div>

            <hr style="margin: 6px 0; border: 0; border-top: 1px solid #ccc;">
            <div><strong>TOTAL PAID:</strong> {{ number_format($totalPaidAmount, 2) }}</div>
            <div><strong>TOTAL BALANCE:</strong> {{ number_format($totalBalanceAmount, 2) }}</div>
        </div>

        <!-- Planner Block (col-4) -->
        <div style=" padding: 10px; text-align: center; float:left; width:30%; margin:0 5px; height:130px;">
            <div style="font-weight: bold; margin-bottom: 6px;">PLANNER</div>
            <hr style="margin: 6px 0; border: 0; border-top: 1px solid #ccc;">
            <div style="margin:5px 0;"><strong>Commission:</strong> {{ number_format($totalPlannerCommission, 2) }}
            </div>
            <div style="margin:5px 0;"><strong>Paid:</strong> {{ number_format($totalPlannerPaid, 2) }}</div>
            <div style="margin:5px 0;"><strong>Pending:</strong> {{ number_format($totalPlannerPending, 2) }}</div>
        </div>

        <!-- Production Staff Block (col-4) -->
        <div style="padding: 10px; text-align: center; float:left; width:30%; margin:0 5px; height:130px;">
            <div style="font-weight: bold; margin-bottom: 6px;">PRODUCTION STAFF</div>
            <hr style="margin: 6px 0; border: 0; border-top: 1px solid #ccc;">
            <div style="margin:5px 0;"><strong>Commission:</strong> {{ number_format($totalPsCommission, 2) }}</div>
            <div style="margin:5px 0;"><strong>Paid:</strong> {{ number_format($totalPsPaid, 2) }}</div>
            <div style="margin:5px 0;"><strong>Pending:</strong> {{ number_format($totalPsPending, 2) }}</div>
        </div>

    </div>

</body>

</html>