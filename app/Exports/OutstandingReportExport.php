<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;

class OutstandingReportExport implements FromArray
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function array(): array
    {
        $records = Payment::orderBy('created_at', 'desc');

        if ($this->from && $this->to) {
            $records->whereBetween('created_at', [
                $this->from,
                $this->to
            ]);
        }

        $collection = $records->get();

        $rows = [];

        $totalAmount = 0;
        $totalPaidAmount = 0;

        $totalPlannerCommission = 0;
        $totalPlannerPaid = 0;

        $totalPsCommission = 0;
        $totalPsPaid = 0;


        // ---------- TOP ----------

        $rows[] = ['From Date', $this->from];
        $rows[] = ['To Date', $this->to];

        $rows[] = [];

        // ---------- TABLE HEADER ----------

        $rows[] = [
            'SL',
            'Customer',
            'Planner ID',
            'Invoice',
            'OR',
            'Amount',
            'Paid',
            'Balance',
            'Planner Commission',
            'Planner Status',
            'PS Commission',
            'PS Status',
            'Status'
        ];

        $i = 1;

        foreach ($collection as $row) {

            $amount = $row->amount ?? 0;
            $totalAmount += $amount;

            $plannerPercentage =
                optional($row->invoice)->p_bill_percentage ?? 0;

            $plannerAmount =
                $amount * ($plannerPercentage / 100);

            $totalPlannerCommission += $plannerAmount;

            if ($row->plannerPayout) {
                $totalPlannerPaid += $plannerAmount;
                $totalPaidAmount += $amount;
            }

            $psPercentage = optional(
                optional(
                    optional(
                        optional(
                            optional($row->invoice)->quotation
                        )->workPlan
                    )->company
                )->productionStaff
            )->production_c_percentage ?? 0;

            $psAmount =
                $amount * ($psPercentage / 100);

            $totalPsCommission += $psAmount;

            if ($row->productionStaffPayout) {
                $totalPsPaid += $psAmount;
                $totalPaidAmount += $amount;
            }

            $rows[] = [

                $i++,

                $row->invoice?->quotation?->workPlan?->company?->company_name,

                $row->invoice?->quotation?->workPlan?->company?->planner?->user_code,

                $row->invoice?->invoice_number,

                $row->custom_payment_id,

                $amount,

                $row->invoice?->grant_total,

                $row->invoice?->balance_amount,

                number_format($plannerAmount, 2),

                $row->plannerPayout ? 'Paid' : 'Pending',

                number_format($psAmount, 2),

                $row->productionStaffPayout ? 'Paid' : 'Pending',

                $row->status
            ];
        }


        $totalPlannerPending =
            $totalPlannerCommission - $totalPlannerPaid;

        $totalPsPending =
            $totalPsCommission - $totalPsPaid;

        $totalBalanceAmount =
            $totalAmount - $totalPaidAmount;


        // ---------- BOTTOM ----------

        $rows[] = [];

        $rows[] = ['TOTAL AMOUNT', $totalAmount];
        $rows[] = ['TOTAL PAID', $totalPaidAmount];
        $rows[] = ['TOTAL BALANCE', $totalBalanceAmount];

        $rows[] = [];

        $rows[] = ['Planner Commission', $totalPlannerCommission];
        $rows[] = ['Planner Paid', $totalPlannerPaid];
        $rows[] = ['Planner Pending', $totalPlannerPending];

        $rows[] = [];

        $rows[] = ['PS Commission', $totalPsCommission];
        $rows[] = ['PS Paid', $totalPsPaid];
        $rows[] = ['PS Pending', $totalPsPending];


        return $rows;
    }
}
