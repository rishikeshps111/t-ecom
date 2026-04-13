<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use App\Exports\PaymentExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('or.view'), ['index']),
        ];
    }
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = Payment::orderBy('created_at', 'desc');
            if ($request->customer_id) {
                $records->whereRelation('invoice.quotation.workPlan', 'company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.payment.partials.action', compact('row'))->render();
                })
                ->addColumn('custom_payment_id', function ($row) {
                    return $row->custom_payment_id ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice->invoice_number ?? '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->invoice->companyType->name ?? '-';
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->invoice->invoice_date ?  $row->invoice->invoice_date->format('d M Y') : '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->invoice->quotation->workPlan->company->company_name ?? '-';
                })
                ->addColumn('customer', function ($row) {
                    return $row->invoice->customer->customer_name ?? '-';
                })
                ->editColumn('payment_method', function ($row) {
                    return ucfirst(str_replace('_', ' ', $row->payment_method));
                })
                ->addColumn('status', function ($row) {
                    $classes = [
                        'pending' => 'warning',
                        'closed'  => 'success'
                    ];
                    $status = $row->status ?? 'warning';
                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('year')) {
                        $query->whereYear('created_at', $request->year);
                    }
                    if ($request->filled('company')) {
                        $query->whereRelation('invoice', 'company_id',  $request->company);
                    }
                    if ($request->filled('customer')) {
                        $query->whereRelation('invoice.customer', 'customer_name', 'like', '%' . $request->customer . '%');
                    }
                    if ($request->filled('invoice_no')) {
                        $query->whereRelation('invoice', 'invoice_number', 'like', '%' . $request->invoice_no . '%');
                    }
                    if ($request->filled('total_group')) {
                        $query->whereRelation('invoice', 'total_group_id', $request->total_group);
                    }
                    if ($request->filled('type')) {
                        $query->whereRelation('invoice', 'company_type_id', $request->type);
                    }
                    if ($request->filled('receipt')) {
                        $query->searchByCustomId($request->receipt);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $companies = Company::get();
        $totalGroups = Customer::get();
        $types = CompanyType::get();
        return view('admin.payment.index', compact('companies', 'totalGroups', 'types', 'customerID'));
    }


    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new PaymentExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'payments.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }
}
