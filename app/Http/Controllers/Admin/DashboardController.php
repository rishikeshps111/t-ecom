<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PlannerDocument;
use App\Models\Quotation;
use App\Models\User;
use App\Models\WorkPlan;
use App\Models\WorkPlanAttachment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $totalCustomers = User::role('Customer')->count();
        $totalCompanies = Company::count();
        $totalInvoices = Invoice::count();
        $totalQuotations = Quotation::count();
        $totalPayments = Payment::count();
        $totalDocuments = WorkPlanAttachment::count();
        $totalPlannerDocuments = PlannerDocument::count();
        $totalWorkOrders = WorkPlan::count();



        return view('admin.dashboard', compact('totalCustomers', 'totalCompanies', 'totalInvoices', 'totalQuotations', 'totalPayments', 'totalDocuments', 'totalPlannerDocuments', 'totalWorkOrders'));
    }

    public function getCounts(Request $request)
    {
        $toDate = $request->to_date ? $request->to_date : now()->format('Y-m-d');
        $fromDate = $request->from_date ? $request->from_date : now()->subDays(30)->format('Y-m-d');
        $toDateEnd = $toDate . ' 23:59:59';

        $data = [
            'totalCustomers' => User::role('Corp User')->whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
            'totalCompanies' => Company::whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
            'totalInvoices' => Invoice::whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
            'totalQuotations' => Quotation::whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
            'totalPayments' => Payment::whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
            'totalWorkOrders' => WorkPlan::whereBetween('created_at', [$fromDate, $toDateEnd])->count(),
        ];

        return response()->json($data);
    }
}
