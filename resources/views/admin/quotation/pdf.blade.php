<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation PDF</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .card {
            border: 0px solid #ccc !Important;
            padding: 0px !Important;
            border-radius: 10px;
        }

        /* Utilities */
        .text-center {
            text-align: center;
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

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mb-4 {
            margin-bottom: 15px;
        }

        .mb-5 {
            margin-bottom: 25px;
        }

        /* Flex replacements */
        .flex {
            display: flex;
            width: 100%;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
        }

        table th {
            background: #f5f5f5;
        }

        /* Columns */
        .col-8 {
            width: 66.66%;
        }

        .col-4 {
            width: 33.33%;
        }

        hr {
            margin: 20px 0;
        }

        ul {
            padding-left: 15px;
        }

        .pdf-top-logo {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: Center;
        }

        .pdf-top-logo p {
            margin-bottom: 5px;
        }

        .line-hr {
            width: 100%;
            height: 2px;
            background-color: #000;
            margin: 45px 0;
            display: flex;
            margin-top: 2px;
        }

        .pdf-top-title h3 {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
        }

        .pdf-info-cs h4 {
            font-size: 18px;
            color: #000;
            font-weight: 700;
        }

        .pdf-info-cs p {
            font-size: 15px;
            margin: 5px 0;
        }

        .pdf-container-center-right table tr,
        .pdf-container-center-right table tr th,
        .pdf-container-center-right table tr td {
            border: 0 !important;
        }

        .pdf-container-center-right table tr th,
        .pdf-container-center-right table tr td {
            padding: 5px 10px !important;
            font-size: 15px;
            font-weight: 400;
            background-color: transparent;
        }

        .pdf-container-center-right table tr th {
            text-transform: uppercase;
        }


        .pdf-container-center-bottom table tr th {
            border: 0 !important;
            border-top: 1px solid #222 !important;
            border-bottom: 1px solid #222 !important;
            padding: 5px;
            background-color: transparent;
            font-size: 16px;
            text-align: center;
        }

        .pdf-container-center-bottom table tr td {
            border: 0;
            padding: 5px;
            background-color: transparent;
            font-size: 16px;
            text-align: center;
        }

        .min-width-200 {
            min-width: 200px;
        }

        .pdf-container-center-bottom table {
            margin-bottom: 80px;
        }

        .pdf-bottom-left p {
            font-size: 15px;
            margin: 5px 0;
            font-weight: 600;
        }

        .pdf-bottom-left p strong {
            font-weight: 600;
        }

        .pdf-bottom-left p.italic {
            font-style: italic;
        }


        .pdf-bottom-right table tr th {
            font-size: 16.5px;
            font-weight: 600;
            padding: 2px 10px !important;
            border: 0 !important;
            background-color: transparent;
        }

        .pdf-bottom-right table tr td {
            font-weight: 300 !important;
            padding: 0px !important;
            border: 0 !important;
            background-color: transparent;
            font-size: 16.5px;
        }

        .pdf-bottom-right table tr td.pdt,
        .pdf-bottom-right table tr th.pdt {
            padding-top: 35px !important;
        }

        .pdf-bottom-right table tr td .line-cs {
            height: 0.5px;
            width: 100%;
            background-color: #000;
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

        .sign-bottom-cs {
            display: flex;
            justify-content: space-between;
            align-items: Center;
            gap: 10px
        }

        .sign-bottom-cs p {
            width: 32%;
            display: flex;
            justify-content: Center;
            align-items: Center;
            padding-top: 7px;
            margin-top: 15px;
            border-top: 1px solid #222;
            font-size: 15px;
            font-weight: 600;
            color: #000;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            @php
                $biller = $quotation->workPlan?->totalGroup?->billerProfile;
            @endphp
            {{-- Header Image --}}
            <div class="flex-center pdf-top-logo"
                style=" display:flex; flex-direction:column; justify-content:center; align-items:Center;width:100%;">
                @php
                    $path = public_path(
                        'storage/' . $quotation->workPlan->totalGroup->billerProfile->quotation_header,
                    );
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                @endphp

                <img src="{{ $base64 }}" style="max-width:250px;">
                {!! $biller->address ?? 'Not Available' !!}
            </div>

            <div class="line-hr"></div>

            {{-- Title --}}
            <div class="pdf-top-title">
                <h3 class="fw-bold">QUOTATION</h3>
            </div>

            {{-- Header Info --}}
            <div class="flex-between mb-5">
                <div class="pdf-info-cs">
                    <h4 class="fw-bold mb-1">
                        {{ $quotation->workPlan->company->company_name ?? '' }}
                    </h4>
                    <p class="mb-1"> {!! $quotation->workPlan->company->address ?? '' !!}</p>
                </div>
                <div class="pdf-container-center-right">
                    <table class="table">
                        <tr>
                            <th>QT NO</th>
                            <td>: {{ $quotation->quotation_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>: {{ $quotation->quotation_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Planner</th>
                            <td>: {{ $quotation->workPlan->company->planner->name ?? 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <th>Production</th>
                            <td>:
                                {{ $quotation->workPlan->company->productionStaff->name ?? 'Not Available' }}
                            </td>
                        </tr>
                    </table>
                </div>


            </div>

            {{-- Items --}}
            <div class="pdf-container-center-bottom">
                <table>
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th class="min-width-200">Description</th>
                            <th>Qty</th>
                            <th>U Price</th>
                            {{-- <th>Discount</th> --}}
                            <th>SST %</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                {{-- <td>{{ number_format($item->discount_amount, 2) }}</td> --}}
                                <td>{{ $item->tax_percentage }}%</td>
                                <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="line-hr" style="margin-bottom:15px"></div>
            </div>


            {{-- Totals --}}
            <div class="flex-between mb-5">
                <div class="col-8">
                    <div class="pdf-bottom-left">
                        @php
                            $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                            $subTotalInWords = $formatter->format((float) $quotation->grant_total);
                        @endphp
                        <p class="mb-1">RINGGIT MALAYSIA {{ ucfirst($subTotalInWords) }} Only</p>
                        <p class="mb-1">
                            @if ($biller && $biller->quotation_tc)
                                {!! $biller->quotation_tc !!}
                            @else
                                Not Available
                            @endif
                        </p>
                        <p class="italic mb-1">This is computer generated printout, No signature required</p>
                        <p class="italic">If the above terms and fees are agreeable to you, we shall appreciate your
                            signing
                            and returning the duplicate of the quotation to us. Thank you.</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="pdf-bottom-right">

                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-start">Amount</th>
                                <td class="text-end">RM</td>
                                <td class="text-end"> {{ number_format($quotation->sub_total, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start" style="font-weight:300">SST</th>
                                <td class="text-end">RM</td>
                                <td class="text-end"> {{ number_format($quotation->tax_total, 2) }}
                                </td>
                            </tr>
                            <tr class="">
                                <th class="text-start pdt">Total</th>
                                <td class="text-end pdt">
                                    <div class="line-cs"></div>RM <div class="line-cs"></div>
                                </td>
                                <td class="text-end pdt">
                                    <div class="line-cs"></div>
                                    {{ number_format($quotation->grant_total, 2) }}
                                    <div class="line-cs"></div>
                                </td>
                            </tr>
                        </table>
                    </div>






                </div>
            </div>
            <div class="col-lg-12">
                <div class="sign-bottom-cs">
                    <p>Signature</p>
                    <p>Name</p>
                    <p>Date</p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>