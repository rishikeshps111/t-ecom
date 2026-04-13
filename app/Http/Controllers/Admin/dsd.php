<?php

namespace App\Http\Controllers\Admin;

class dsd
{
    public function getPlannerMonthlyInvoices(Request $request)
    {
        $year = $request->year ?? date('Y');
        // Auto-filter for Planner role
        $plannerId = Auth::user()->hasRole('Planner') ? Auth::id() : $request->planner_id;
        $companyId = $request->company_id ?? null;


        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        // Load invoices with payments and planner payouts
        $invoices = Invoice::with(['payments.plannerPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->when($plannerId, function ($query) use ($plannerId) {
                $query->whereHas('quotation.workPlan.company', function ($q) use ($plannerId) {
                    $q->where('planner_id', $plannerId);
                });
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'total_group_id', $companyId);
            })
            ->get();

        // Prepare monthly totals
        $totals = [];

        for ($month = 1; $month <= 12; $month++) {

            $monthInvoices = $invoices->filter(function ($invoice) use ($month) {
                return (int) $invoice->invoice_date->format('n') === $month;
            });

            $total = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum('amount') * $invoice->p_bill_percentage / 100;
            });

            $paid = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum(function ($payment) {
                    return $payment->plannerPayout
                        ? $payment->plannerPayout->amount
                        : 0;
                });
            });

            $totals[] = [
                'month' => $month,
                'total' => $total,
                'paid' => number_format($paid, 2),
                'unpaid' => number_format($total - $paid, 2),
            ];
        }

        return response()->json($totals);
    }
}
