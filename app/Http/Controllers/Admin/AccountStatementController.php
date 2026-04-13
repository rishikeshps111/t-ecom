<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CreditNote;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PlannerPayout;
use App\Models\ProductionStaffPayout;
use App\Models\User;
use App\Models\WorkPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class AccountStatementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('wo-report.view'), ['workOrders']),
            new Middleware(PermissionMiddleware::using('invoice-report.view'),  ['invoice']),
            new Middleware(PermissionMiddleware::using('or-report.view'),  ['originalReceipt']),
            new Middleware(PermissionMiddleware::using('cr-report.view'),  ['creditNote']),
            new Middleware(PermissionMiddleware::using('planner-commission-report.view'),  ['plannerCommission']),
            new Middleware(PermissionMiddleware::using('production-staff-commission-report.view'),  ['productionCommission']),
            new Middleware(PermissionMiddleware::using('tg-report.view'),  ['totalGroup']),
            new Middleware(PermissionMiddleware::using('consolidation-wo-report.view'),  ['consolidated']),
            new Middleware(PermissionMiddleware::using('monthly-report.view'),  ['monthlySummary']),

        ];
    }
    public function show($id)
    {
        $workOrder = WorkPlan::find($id) ?? null;
        return view('admin.account-statement.view', compact('workOrder'));
    }

    public function details($id)
    {
        $workOrder = WorkPlan::find($id) ?? null;
        return view('admin.account-statement.detail', compact('workOrder'));
    }
    public function workOrders(Request $request)
    {
        if ($request->ajax()) {
            $records = WorkPlan::orderBy('created_at', 'desc');

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('date', [$request->from_date, $request->to_date]);
            }

            if ($request->filled('total_group')) {
                $records->where('total_group_id', $request->total_group);
            }

            if ($request->filled('planner')) {
                $records->where('planner_id', $request->planner);
            }

            if ($request->filled('customer')) {
                $records->where('company_id', $request->customer);
            }

            if ($request->filled('status')) {
                $records->where('status', $request->status);
            }

            $totalAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->grant_total ?? 0;
                });

            return DataTables::eloquent($records)
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y ');
                })
                ->addColumn('quotation_number', function ($row) {
                    return $row->quotation->quotation_number ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->quotation?->invoice?->invoice_date?->format('d M Y') ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->quotation?->invoice?->invoice_number ?? '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->quotation?->invoice?->grant_total ?? '-';
                })
                ->addColumn('payment_status', function ($row) {
                    if (!$row->quotation || !$row->quotation->invoice || !$row->quotation->invoice->payment_status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->quotation->invoice->payment_status) {
                        'partial' => '<span class="badge bg-warning">Partial</span>',
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup?->customer_name ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->planner?->name ?? '-';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->company?->company_name ?? '-';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id',  $request->total_group);
                    }
                    if ($request->filled('planner')) {
                        $query->where('planner_id',  $request->planner);
                    }
                    if ($request->filled('customer')) {
                        $query->where('company_id',  $request->customer);
                    }
                    if ($request->filled('status')) {
                        $query->where('status',  $request->status);
                    }
                    if ($request->filled('staff')) {
                        $query->whereRelation('company', 'production_staff_id',  $request->staff);
                    }
                })
                ->rawColumns(['status', 'quotation_status', 'payment_status', 'planner_payment'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();
        $planners = User::role('Planner')->get();
        $customers = Company::get();
        $staffs = User::where('user_type', 'production')->get();


        return view('admin.account-statement.index', compact('totalGroups', 'planners', 'customers', 'staffs'));
    }

    public function exportPdf(Request $request)
    {
        $records = WorkPlan::with(['quotation.invoice', 'totalGroup', 'planner', 'company'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('total_group')) {
            $records->where('total_group_id', $request->total_group);
        }

        if ($request->filled('planner')) {
            $records->where('planner_id', $request->planner);
        }

        if ($request->filled('customer')) {
            $records->where('company_id', $request->customer);
        }

        if ($request->filled('status')) {
            $records->where('status', $request->status);
        }

        if ($request->filled('staff')) {
            $records->whereRelation('company', 'production_staff_id', $request->staff);
        }

        $data = $records->get();

        $totalAmount = $data->sum(function ($row) {
            return $row->quotation->invoice->grant_total ?? 0;
        });

        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $pdf = Pdf::loadView('admin.account-statement.pdf', [
            'records' => $data,
            'totalAmount' => $totalAmount,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'landscape');

        return $pdf->download('work-orders.pdf');
    }


    public function invoice(Request $request)
    {
        if ($request->ajax()) {
            $records = Invoice::orderBy('created_at', 'desc');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('invoice_date', [
                    $request->from_date,
                    $request->to_date
                ]);
            }

            if ($request->filled('total_group')) {
                $records->whereRelation('quotation.workPlan', 'total_group_id', $request->total_group);
            }

            if ($request->filled('planner')) {
                $records->whereRelation('quotation.workPlan', 'planner_id', $request->planner);
            }

            if ($request->filled('customer')) {
                $records->whereRelation('quotation.workPlan', 'company_id', $request->customer);
            }

            if ($request->filled('status')) {
                $records->where('payment_status', $request->status);
            }

            if ($request->filled('work_order')) {
                $records->whereRelation(
                    'quotation.workPlan',
                    'workplan_number',
                    'like',
                    '%' . $request->work_order . '%'
                );
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(fn($invoice) => $invoice->grant_total ?? 0);
            $totalPaidAmount = (clone $records)
                ->get()
                ->sum(fn($invoice) => $invoice->paid_amount ?? 0);
            $totalBalanceAmount = (clone $records)
                ->get()
                ->sum(fn($invoice) => $invoice->balance_amount ?? 0);
            return DataTables::eloquent($records)
                ->addColumn('work_order_number', function ($row) {
                    return $row->quotation?->workPlan?->workplan_number ?? '-';
                })
                ->editColumn('invoice_date', function ($row) {
                    return $row->invoice_date ? $row->invoice_date->format('d M Y') : '-';
                })
                ->addColumn('or', function ($row) {
                    if ($row->payments && $row->payments->count()) {
                        return $row->payments
                            ->where('invoice_id', $row->id)
                            ->map(function ($payment) {
                                return '<span class="badge bg-info me-1 mt-1">'
                                    . $payment->custom_payment_id .
                                    '</span>';
                            })
                            ->implode('');
                    }
                    return '-';
                })
                ->addColumn('or_amount', function ($row) {
                    return $row->payments?->sum('amount') ?? '-';
                })
                ->editColumn('payment_status', function ($row) {
                    if (!$row->payment_status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }
                    return match ($row->payment_status) {
                        'partial' => '<span class="badge bg-warning">Partial</span>',
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('cr', function ($row) {
                    if ($row->creditNotes && $row->creditNotes->count()) {
                        return $row->creditNotes
                            ->where('invoice_id', $row->id)
                            ->map(function ($creditNote) {
                                return '<span class="badge bg-info me-1 mt-1">'
                                    . $creditNote->credit_note_number .
                                    '</span>';
                            })
                            ->implode('');
                    }
                    return '-';
                })
                ->addColumn('cr_amount', function ($row) {
                    return $row->creditNotes?->sum('amount') ?? '-';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('invoice_date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('total_group')) {
                        $query->whereRelation('quotation.workPlan', 'total_group_id',  $request->total_group);
                    }
                    if ($request->filled('planner')) {
                        $query->whereRelation('quotation.workPlan', 'planner_id',  $request->planner);
                    }
                    if ($request->filled('customer')) {
                        $query->whereRelation('quotation.workPlan', 'company_id',  $request->customer);
                    }
                    if ($request->filled('status')) {
                        $query->where('payment_status',  $request->status);
                    }
                    if ($request->filled('work_order')) {
                        $query->whereRelation('quotation.workPlan', 'workplan_number', 'like', '%' . $request->work_order . '%');
                    }
                })
                ->rawColumns(['or', 'payment_status', 'cr'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2),
                    'total_paid_amount' => number_format($totalPaidAmount, 2),
                    'total_balance_amount' => number_format($totalBalanceAmount, 2),
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();
        $planners = User::role('Planner')->get();
        $customers = Company::get();

        return view('admin.account-statement.invoice', compact('totalGroups', 'planners', 'customers'));
    }

    public function invoiceExportPdf(Request $request)
    {
        $records = Invoice::with([
            'quotation.workPlan',
            'payments',
            'creditNotes'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('invoice_date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('total_group')) {
            $records->whereRelation('quotation.workPlan', 'total_group_id', $request->total_group);
        }

        if ($request->filled('planner')) {
            $records->whereRelation('quotation.workPlan', 'planner_id', $request->planner);
        }

        if ($request->filled('customer')) {
            $records->whereRelation('quotation.workPlan', 'company_id', $request->customer);
        }

        if ($request->filled('status')) {
            $records->where('payment_status', $request->status);
        }

        if ($request->filled('work_order')) {
            $records->whereRelation(
                'quotation.workPlan',
                'workplan_number',
                'like',
                '%' . $request->work_order . '%'
            );
        }

        $data = $records->get();

        $totalAmount = $data->sum('grant_total');
        $totalPaid = $data->sum('paid_amount');
        $totalBalance = $data->sum('balance_amount');
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $pdf = Pdf::loadView('admin.account-statement.invoice-pdf', [
            'records' => $data,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'planner' => $request->planner,
            'total_group' => $request->total_group,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'landscape');

        return $pdf->download('invoice-report.pdf');
    }

    public function originalReceipt(Request $request)
    {
        if ($request->ajax()) {
            $records = Payment::orderBy('created_at', 'desc');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('created_at', [
                    $request->from_date,
                    $request->to_date
                ]);
            }

            if ($request->filled('total_group')) {
                $records->whereRelation(
                    'invoice.quotation.workPlan',
                    'total_group_id',
                    $request->total_group
                );
            }

            if ($request->filled('status')) {
                $records->where('status', $request->status);
            }

            if ($request->filled('work_order')) {
                $records->whereRelation(
                    'invoice.quotation.workPlan',
                    'workplan_number',
                    'like',
                    '%' . $request->work_order . '%'
                );
            }

            if ($request->filled('invoice')) {
                $records->whereRelation(
                    'invoice',
                    'invoice_number',
                    'like',
                    '%' . $request->invoice . '%'
                );
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(fn($payment) => $payment->amount ?? 0);
            return DataTables::eloquent($records)
                ->addColumn('work_order_number', function ($row) {
                    return $row->invoice?->quotation?->workPlan?->workplan_number ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->invoice?->invoice_number ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->invoice?->grant_total ?? '-';
                })
                ->addColumn('or_number', function ($row) {
                    return $row->custom_payment_id ?? '-';
                })
                ->editColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }
                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'closed' => '<span class="badge bg-success">Closed</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('created_at', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('total_group')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'total_group_id',  $request->total_group);
                    }
                    if ($request->filled('status')) {
                        $query->where('status',  $request->status);
                    }
                    if ($request->filled('work_order')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'workplan_number', 'like', '%' . $request->work_order . '%');
                    }
                    if ($request->filled('invoice')) {
                        $query->whereRelation('invoice', 'invoice_number', 'like', '%' . $request->invoice . '%');
                    }
                })
                ->rawColumns(['status'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();

        return view('admin.account-statement.payment', compact('totalGroups'));
    }

    public function orExportPdf(Request $request)
    {
        $records = Payment::with([
            'invoice.quotation.workPlan'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('total_group')) {
            $records->whereRelation(
                'invoice.quotation.workPlan',
                'total_group_id',
                $request->total_group
            );
        }

        if ($request->filled('status')) {
            $records->where('status', $request->status);
        }

        if ($request->filled('work_order')) {
            $records->whereRelation(
                'invoice.quotation.workPlan',
                'workplan_number',
                'like',
                '%' . $request->work_order . '%'
            );
        }

        if ($request->filled('invoice')) {
            $records->whereRelation(
                'invoice',
                'invoice_number',
                'like',
                '%' . $request->invoice . '%'
            );
        }

        $data = $records->get();

        $totalAmount = $data->sum('amount');
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $pdf = Pdf::loadView('admin.account-statement.or-pdf', [
            'records' => $data,
            'totalAmount' => $totalAmount,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'total_group' => $request->total_group,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'landscape');

        return $pdf->download('or-report.pdf');
    }

    public function creditNote(Request $request)
    {
        if ($request->ajax()) {
            $records = CreditNote::orderBy('created_at', 'desc');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('date', [
                    $request->from_date,
                    $request->to_date
                ]);
            }

            if ($request->filled('total_group')) {
                $records->whereRelation(
                    'invoice.quotation.workPlan',
                    'total_group_id',
                    $request->total_group
                );
            }

            if ($request->filled('status')) {
                $records->where('status', $request->status);
            }

            if ($request->filled('work_order')) {
                $records->whereRelation(
                    'invoice.quotation.workPlan',
                    'workplan_number',
                    'like',
                    '%' . $request->work_order . '%'
                );
            }

            if ($request->filled('invoice')) {
                $records->whereRelation(
                    'invoice',
                    'invoice_number',
                    'like',
                    '%' . $request->invoice . '%'
                );
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(fn($creditNote) => $creditNote->amount ?? 0);
            return DataTables::eloquent($records)
                ->addColumn('work_order_number', function ($row) {
                    return $row->invoice?->quotation?->workPlan?->workplan_number ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->invoice?->invoice_number ?? '-';
                })
                ->editColumn('date', function ($row) {
                    return $row->date ? $row->date->format('d M Y') : '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->invoice?->grant_total ?? '-';
                })
                ->editColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }
                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'cancelled' => '<span class="badge bg-success">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('total_group')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'total_group_id',  $request->total_group);
                    }
                    if ($request->filled('status')) {
                        $query->where('status',  $request->status);
                    }
                    if ($request->filled('work_order')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'workplan_number', 'like', '%' . $request->work_order . '%');
                    }
                    if ($request->filled('invoice')) {
                        $query->whereRelation('invoice', 'invoice_number', 'like', '%' . $request->invoice . '%');
                    }
                })
                ->rawColumns(['status'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();

        return view('admin.account-statement.credit-note', compact('totalGroups'));
    }

    public function crExportPdf(Request $request)
    {
        $records = CreditNote::with([
            'invoice.quotation.workPlan'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('total_group')) {
            $records->whereRelation(
                'invoice.quotation.workPlan',
                'total_group_id',
                $request->total_group
            );
        }

        if ($request->filled('status')) {
            $records->where('status', $request->status);
        }

        if ($request->filled('work_order')) {
            $records->whereRelation(
                'invoice.quotation.workPlan',
                'workplan_number',
                'like',
                '%' . $request->work_order . '%'
            );
        }

        if ($request->filled('invoice')) {
            $records->whereRelation(
                'invoice',
                'invoice_number',
                'like',
                '%' . $request->invoice . '%'
            );
        }

        $data = $records->get();

        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }
        $totalAmount = $data->sum('amount');

        $pdf = Pdf::loadView('admin.account-statement.cr-pdf', [
            'records' => $data,
            'totalAmount' => $totalAmount,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'total_group' => $request->total_group,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'landscape');

        return $pdf->download('credit-note-report.pdf');
    }

    public function plannerCommission(Request $request)
    {
        if ($request->ajax()) {
            $records = PlannerPayout::orderBy('created_at', 'desc');
            // Auto-filter for Planner role
            if (Auth::user()->hasRole('Planner')) {
                $records->where('planner_id', Auth::id());
            } elseif ($request->filled('planner')) {
                $records->where('planner_id', $request->planner);
            }
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('created_at', [
                    $request->from_date,
                    $request->to_date
                ]);
            }
            if ($request->filled('customer')) {
                // Reaches through Payout -> Invoice -> Quotation -> WorkPlan -> Company
                $records->whereRelation('invoice.quotation.workPlan', 'company_id', $request->customer);
            }
            if ($request->filled('total')) {
                // Reaches through Payout -> Invoice -> Quotation -> WorkPlan -> Company
                $records->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(function ($row) {
                    return $row->amount ?? 0;
                });

            return DataTables::eloquent($records)
                ->addColumn('work_order', function ($row) {
                    return $row->invoice->quotation->workPlan->workplan_number ?? '-';
                })
                ->addColumn('customer', function ($row) {
                    return $row->invoice->quotation->workPlan->company->company_name ?? '-';
                })
                ->addColumn('receipt_number', function ($row) {
                    return $row->payment->custom_payment_id ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y') ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('planner')) {
                        $query->where('planner_id',  $request->planner);
                    }

                    if ($request->filled('from_date') && $request->filled('to_date')) {

                        $fromDate = Carbon::parse($request->from_date)->subDay(); // minus 1 day
                        $toDate   = Carbon::parse($request->to_date)->addDay();   // plus 1 day

                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    }
                    if ($request->filled('customer')) {
                        // Reaches through Payout -> Invoice -> Quotation -> WorkPlan -> Company
                        $query->whereRelation('invoice.quotation.workPlan', 'company_id', $request->customer);
                    }
                    if ($request->filled('total')) {
                        // Reaches through Payout -> Invoice -> Quotation -> WorkPlan -> Company
                        $query->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
                    }
                })
                ->rawColumns(['status'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $planners = User::role('Planner')->get();
        $customers = Company::orderBy('company_name', 'asc')->get();
        $totalGroups = Customer::get();

        return view('admin.account-statement.planner', compact('planners', 'customers', 'totalGroups'));
    }


    public function plannerCommissionExport(Request $request)
    {

        $records = PlannerPayout::orderBy('created_at', 'desc');

        if ($request->planner) {
            $records->where('planner_id', $request->planner);
        }

        if ($request->from_date && $request->to_date) {
            $records->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        if ($request->customer) {
            $records->whereRelation(
                'invoice.quotation.workPlan',
                'company_id',
                $request->customer
            );
        }

        if ($request->total) {
            $records->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
        }
        $records = $records->get();

        if ($records->count() == 0) {
            return back()->with('error', 'No records found');
        }

        $totalAmount = $records->sum('amount');

        // $planner = User::find($request->planner);
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $customer = null;
        if ($request->customer) {
            $customer = Company::find($request->customer);
        }

        $planner = null;
        if ($request->planner) {
            $planner = User::find($request->planner);
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $reportDate = Carbon::now()->format('d/m/y');

        $pdf = Pdf::loadView(
            'admin.account-statement.planner-pdf',
            compact(
                'records',
                'totalAmount',
                'totalGroup',
                'customer',
                'fromDate',
                'toDate',
                'reportDate',
                'planner'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download('planner_commission.pdf');
    }

    public function productionCommission(Request $request)
    {
        if ($request->ajax()) {
            $records = ProductionStaffPayout::orderBy('created_at', 'desc');
            // Auto-filter for Production Staff role
            if (Auth::user()->hasRole('Production Staff')) {
                $records->where('production_staff_id', Auth::id());
            } elseif ($request->filled('production_staff')) {
                $records->where('production_staff_id', $request->production_staff);
            }
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $fromDate = Carbon::parse($request->from_date)->subDay()->startOfDay();
                $toDate = Carbon::parse($request->to_date)->addDay()->endOfDay();

                $records->whereBetween('created_at', [$fromDate, $toDate]);
            }
            if ($request->filled('total')) {
                $records->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(function ($row) {
                    return $row->amount ?? 0;
                });

            return DataTables::eloquent($records)
                ->addColumn('work_order', function ($row) {
                    return $row->invoice->quotation->workPlan->workplan_number ?? '-';
                })
                ->addColumn('receipt_number', function ($row) {
                    return $row->payment->custom_payment_id ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y') ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('production_staff')) {
                        $query->where('production_staff_id',  $request->production_staff);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $fromDate = Carbon::parse($request->from_date)->subDay()->startOfDay();
                        $toDate = Carbon::parse($request->to_date)->addDay()->endOfDay();

                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    }
                    if ($request->total) {
                        $query->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
                    }
                })
                ->rawColumns(['status'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $staffs = User::where('user_type', 'production')->get();
        $totalGroups = Customer::get();

        return view('admin.account-statement.production', compact('staffs', 'totalGroups'));
    }

    public function productionCommissionExport(Request $request)
    {

        $records = ProductionStaffPayout::orderBy('created_at', 'desc');

        if ($request->filled('production_staff')) {
            $records->where('production_staff_id', $request->production_staff);
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->subDay()->startOfDay();
            $toDate = Carbon::parse($request->to_date)->addDay()->endOfDay();

            $records->whereBetween('created_at', [$fromDate, $toDate]);
        }

        if ($request->total) {
            $records->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
        }
        $records = $records->get();

        if ($records->count() == 0) {
            return back()->with('error', 'No records found');
        }

        $totalAmount = $records->sum('amount');

        // $planner = User::find($request->planner);
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $reportDate = Carbon::now()->format('d M Y');

        $pdf = Pdf::loadView(
            'admin.account-statement.production-pdf',
            compact(
                'records',
                'totalAmount',
                'totalGroup',
                'fromDate',
                'toDate',
                'reportDate'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download('planner_commission.pdf');
    }

    public function totalGroup(Request $request)
    {
        if ($request->ajax()) {
            $records = WorkPlan::orderBy('created_at', 'desc');
            if ($request->filled('total_group')) {
                $records->where('total_group_id', $request->total_group);
            }
            if ($request->filled('start_date') && $request->filled('end_date')) {
                // Adjust 'date' to whatever column represents the WO Date in your DB
                $records->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            $totalAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->grant_total ?? 0;
                });

            return DataTables::eloquent($records)
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y ');
                })
                ->addColumn('quotation_number', function ($row) {
                    return $row->quotation->quotation_number ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->quotation?->invoice?->invoice_date?->format('d M Y') ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->quotation?->invoice?->invoice_number ?? '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->quotation?->invoice?->grant_total ?? '-';
                })
                ->addColumn('payment_status', function ($row) {
                    if (!$row->quotation || !$row->quotation->invoice || !$row->quotation->invoice->payment_status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->quotation->invoice->payment_status) {
                        'partial' => '<span class="badge bg-warning">Partial</span>',
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup?->customer_name ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->planner?->name ?? '-';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->company?->company_name ?? '-';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id',  $request->total_group);
                    }
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        // Adjust 'date' to whatever column represents the WO Date in your DB
                        $query->whereBetween('date', [$request->start_date, $request->end_date]);
                    }
                })
                ->rawColumns(['status', 'quotation_status', 'payment_status', 'planner_payment'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2)
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();

        return view('admin.account-statement.tg', compact('totalGroups'));
    }

    public function consolidated(Request $request)
    {
        if ($request->ajax()) {
            $records = WorkPlan::orderBy('created_at', 'desc');

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('date', [$request->from_date, $request->to_date]);
            }

            if ($request->filled('total_group')) {
                $records->where('total_group_id', $request->total_group);
            }

            if ($request->filled('planner')) {
                $records->where('planner_id', $request->planner);
            }

            if ($request->filled('customer')) {
                $records->where('company_id', $request->customer);
            }

            if ($request->filled('status')) {
                $records->where('status', $request->status);
            }

            if ($request->filled('month')) {
                $month = $request->month; // format: 2026-02

                $records->whereYear('date', \Carbon\Carbon::parse($month)->year)
                    ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
            }

            $totalAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->grant_total ?? 0;
                });

            $totalQuotationAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->grant_total ?? 0;
                });

            $totalPaidAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->paid_amount ?? 0;
                });

            $totalBalanceAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    $invoice = $row->quotation?->invoice;
                    $balanceAmount = $invoice?->balance_amount ?? 0;
                    $creditTotal = $invoice?->creditNotes?->sum('amount') ?? 0;

                    return $balanceAmount != 0 ? $balanceAmount - $creditTotal : 0;
                });

            $totalReceiptAmount = (clone $records)
                ->whereHas('quotation.invoice.payments')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ->invoice
                        ->payments
                        ->sum('amount')
                );

            $totalCreditAmount = (clone $records)
                ->whereHas('quotation.invoice.creditNotes')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ?->invoice
                        ?->creditNotes
                        ?->where('type', 'credit')
                        ->sum('amount') ?? 0
                );

            $totalDebitAmount = (clone $records)
                ->whereHas('quotation.invoice.creditNotes')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ?->invoice
                        ?->creditNotes
                        ?->where('type', 'debit')
                        ->sum('amount') ?? 0
                );


            $totalPlannerAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->planner_commission ?? 0;
                });

            $totalProductionAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {

                    $grantTotal = optional($row->quotation->invoice)->grant_total ?? 0;
                    $percentage = optional($row->company->productionStaff)->production_c_percentage ?? 0;

                    return ($grantTotal * $percentage) / 100;
                });


            return DataTables::eloquent($records)
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y ');
                })
                ->addColumn('quotation_number', function ($row) {
                    return $row->quotation->quotation_number ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->quotation?->invoice?->invoice_date?->format('d M Y') ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->quotation?->invoice?->invoice_number ?? '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->quotation?->invoice?->grant_total ?? '-';
                })
                ->addColumn('payment_status', function ($row) {
                    if (!$row->quotation || !$row->quotation->invoice || !$row->quotation->invoice->payment_status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->quotation->invoice->payment_status) {
                        'partial' => '<span class="badge bg-warning">Partial</span>',
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup?->customer_name ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->planner?->name ?? '-';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->company?->company_name ?? '-';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('month')) {
                        $month = $request->month;
                        $query->whereYear('date', \Carbon\Carbon::parse($month)->year)
                            ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
                    }

                    if ($request->filled('total_group')) {
                        $query->where('total_group_id',  $request->total_group);
                    }
                    if ($request->filled('planner')) {
                        $query->where('planner_id',  $request->planner);
                    }
                    if ($request->filled('customer')) {
                        $query->where('company_id',  $request->customer);
                    }
                    if ($request->filled('status')) {
                        $query->where('status',  $request->status);
                    }
                })
                ->rawColumns(['status', 'quotation_status', 'payment_status', 'planner_payment'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2),
                    'total_quotation_amount' => number_format($totalQuotationAmount, 2),
                    'total_paid_amount' => number_format($totalPaidAmount, 2),
                    'total_balance_amount' => number_format($totalBalanceAmount, 2),
                    'total_receipt_amount' => number_format($totalReceiptAmount, 2),
                    'total_credit_amount' => number_format($totalCreditAmount, 2),
                    'total_planner_amount' => number_format($totalPlannerAmount, 2),
                    'total_production_amount' => number_format($totalProductionAmount, 2),
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();
        $planners = User::role('Planner')->get();
        $customers = Company::get();

        return view('admin.account-statement.consolidated', compact('totalGroups', 'planners', 'customers'));
    }

    public function consolidatedExportPdf(Request $request)
    {
        $records = WorkPlan::with([
            'quotation.invoice.payments',
            'quotation.invoice.creditNotes'
        ])->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $records->whereYear('date', \Carbon\Carbon::parse($month)->year)
                ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
        }

        if ($request->filled('total_group')) {
            $records->where('total_group_id', $request->total_group);
        }

        if ($request->filled('planner')) {
            $records->where('planner_id', $request->planner);
        }

        if ($request->filled('customer')) {
            $records->where('company_id', $request->customer);
        }

        // Get data once
        $data = $records->get();

        // ✅ SAFE SUMMARY
        $summary = [
            'totalAmount' => $data->sum(fn($r) => $r->quotation?->invoice?->grant_total ?? 0),

            'totalQuotation' => $data->sum(fn($r) => $r->quotation?->grant_total ?? 0),

            'totalPaid' => $data->sum(fn($r) => $r->quotation?->invoice?->paid_amount ?? 0),

            'totalBalance' => $data->sum(fn($r) => $r->quotation?->invoice?->balance_amount ?? 0),

            'totalReceipt' => $data->sum(function ($r) {
                return $r->quotation?->invoice?->payments?->sum('amount') ?? 0;
            }),

            'totalCredit' => $data->sum(function ($r) {
                return $r->quotation?->invoice?->creditNotes?->sum('amount') ?? 0;
            }),

            'totalPlanner' => $data->sum(fn($r) => $r->quotation?->invoice?->planner_commission ?? 0),
        ];

        // ✅ FIXED
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }

        $pdf = Pdf::loadView('admin.account-statement.consolidated-pdf', [
            'data' => $data, // 👈 IMPORTANT (if you need table in PDF)
            'summary' => $summary,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'month' => $request->month,
            'total_group' => $request->total_group,
            'planner' => $request->planner,
            'customer' => $request->customer,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'portrait');

        return $pdf->download('consolidated-report.pdf');
    }

    public function monthlySummary(Request $request)
    {
        if ($request->ajax()) {
            $records = WorkPlan::orderBy('created_at', 'desc');

            if ($request->filled('month')) {
                $month = $request->month; // format: 2026-02

                $records->whereYear('date', \Carbon\Carbon::parse($month)->year)
                    ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
            }

            if ($request->filled('total_group')) {
                $records->where('total_group_id', $request->total_group);
            }

            $totalAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->grant_total ?? 0;
                });

            $totalQuotationAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->grant_total ?? 0;
                });

            $totalPaidAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->paid_amount ?? 0;
                });

            $totalBalanceAmount = (clone $records)
                ->whereHas('quotation')
                ->get()
                ->sum(function ($row) {
                    $invoice = $row->quotation?->invoice;
                    $balanceAmount = $invoice?->balance_amount ?? 0;
                    $creditTotal = $invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0;
                    $debitTotal = $invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0;

                    return $balanceAmount != 0 ? ($balanceAmount - $creditTotal + $debitTotal) : 0;
                });

            $totalReceiptAmount = (clone $records)
                ->whereHas('quotation.invoice.payments')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ->invoice
                        ->payments
                        ->sum('amount')
                );

            $totalCreditAmount = (clone $records)
                ->whereHas('quotation.invoice.creditNotes')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ?->invoice
                        ?->creditNotes
                        ?->where('type', 'credit')
                        ->sum('amount') ?? 0
                );

            $totalDebitAmount = (clone $records)
                ->whereHas('quotation.invoice.creditNotes')
                ->get()
                ->sum(
                    fn($row) =>
                    $row->quotation
                        ?->invoice
                        ?->creditNotes
                        ?->where('type', 'debit')
                        ->sum('amount') ?? 0
                );


            $totalPlannerAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {
                    return $row->quotation->invoice->planner_commission ?? 0;
                });

            $totalProductionAmount = (clone $records)
                ->whereHas('quotation.invoice')
                ->get()
                ->sum(function ($row) {

                    $grantTotal = $row->quotation->invoice->grant_total ?? 0;
                    $percentage = $row->company->productionStaff->production_c_percentage ?? 0;

                    return ($grantTotal * $percentage) / 100;
                });



            return DataTables::eloquent($records)
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y ');
                })
                ->addColumn('quotation_number', function ($row) {
                    return $row->quotation->quotation_number ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('invoice_date', function ($row) {
                    return $row->quotation?->invoice?->invoice_date?->format('d M Y') ?? '-';
                })
                ->addColumn('invoice_number', function ($row) {
                    return $row->quotation?->invoice?->invoice_number ?? '-';
                })
                ->addColumn('amount', function ($row) {
                    return $row->quotation?->grant_total ?? '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->quotation?->invoice?->grant_total ?? '-';
                })
                ->addColumn('paid_amount', function ($row) {
                    return $row->quotation?->invoice?->paid_amount ?? '-';
                })
                ->addColumn('payment_status', function ($row) {
                    if (!$row->quotation || !$row->quotation->invoice || !$row->quotation->invoice->payment_status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }

                    return match ($row->quotation->invoice->payment_status) {
                        'partial' => '<span class="badge bg-warning">Partial</span>',
                        'paid' => '<span class="badge bg-success">Paid</span>',
                        'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup?->customer_name ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->planner?->name ?? '-';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->company?->company_name ?? '-';
                })
                ->addColumn('credit_note_amount', function ($row) {
                    return $row->quotation?->invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0;
                })
                ->addColumn('debit_note_amount', function ($row) {
                    return $row->quotation?->invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0;
                })
                ->addColumn('balance_amount', function ($row) {
                    $invoice = $row->quotation?->invoice;
                    $balanceAmount = $invoice?->balance_amount ?? 0;
                    $creditTotal = $invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0;
                    $debitTotal = $invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0;

                    return $balanceAmount != 0 ? ($balanceAmount - $creditTotal + $debitTotal) : 0;
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('month')) {
                        $month = $request->month;
                        $query->whereYear('date', \Carbon\Carbon::parse($month)->year)
                            ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id', $request->total_group);
                    }
                })
                ->rawColumns(['status', 'quotation_status', 'payment_status', 'planner_payment'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2),
                    'total_quotation_amount' => number_format($totalQuotationAmount, 2),
                    'total_paid_amount' => number_format($totalPaidAmount, 2),
                    'total_balance_amount' => number_format($totalBalanceAmount, 2),
                    'total_receipt_amount' => number_format($totalReceiptAmount, 2),
                    'total_credit_amount' => number_format($totalCreditAmount, 2),
                    'total_debit_amount' => number_format($totalDebitAmount, 2),
                    'total_planner_amount' => number_format($totalPlannerAmount, 2),
                    'total_production_amount' => number_format($totalProductionAmount, 2),

                ])
                ->make(true);
        }
        $totalGroups = Customer::get();

        return view('admin.account-statement.monthly-summary', compact('totalGroups'));
    }

    public function monthlySummaryPdf(Request $request)
    {
        $records = WorkPlan::with([
            'quotation.invoice.payments',
            'quotation.invoice.creditNotes'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('month')) {
            $month = $request->month;
            $records->whereYear('date', \Carbon\Carbon::parse($month)->year)
                ->whereMonth('date', \Carbon\Carbon::parse($month)->month);
        }

        if ($request->filled('total_group')) {
            $records->where('total_group_id', $request->total_group);
        }

        $data = $records->get();

        // ✅ SAFE totals
        $summary = [
            'totalAmount' => $data->sum(fn($r) => $r->quotation?->invoice?->grant_total ?? 0),
            'totalQuotation' => $data->sum(fn($r) => $r->quotation?->grant_total ?? 0),
            'totalPaid' => $data->sum(fn($r) => $r->quotation?->invoice?->paid_amount ?? 0),
            'totalBalance' => $data->sum(function ($r) {
                $invoice = $r->quotation?->invoice;
                $balanceAmount = $invoice?->balance_amount ?? 0;
                $creditTotal = $invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0;
                $debitTotal = $invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0;

                return $balanceAmount != 0 ? ($balanceAmount - $creditTotal + $debitTotal) : 0;
            }),
            'totalReceipt' => $data->sum(fn($r) => $r->quotation?->invoice?->payments?->sum('amount') ?? 0),
            'totalCredit' => $data->sum(fn($r) => $r->quotation?->invoice?->creditNotes?->where('type', 'credit')->sum('amount') ?? 0),
            'totalDebit' => $data->sum(fn($r) => $r->quotation?->invoice?->creditNotes?->where('type', 'debit')->sum('amount') ?? 0),
            'totalPlanner' => $data->sum(fn($r) => $r->quotation?->invoice?->planner_commission ?? 0),
            'totalProduction' => $data->sum(function ($r) {
                $commission = $r->quotation?->invoice?->grant_total ?? 0;
                $percentage = $r->company?->productionStaff?->production_c_percentage ?? 0;

                return ($commission * $percentage) / 100;
            }),
        ];
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }
        $pdf = Pdf::loadView('admin.account-statement.monthly-summary-pdf', [
            'data' => $data,
            'summary' => $summary,
            'month' => $request->month,
            'total_group' => $request->total_group,
            'totalGroup' => $totalGroup
        ])->setPaper('a4', 'portrait');

        return $pdf->download('monthly-summary.pdf');
    }

    public function outstandingReport(Request $request)
    {
        if ($request->ajax()) {
            $records = Payment::orderBy('created_at', 'desc');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $records->whereBetween('created_at', [
                    $request->from_date,
                    $request->to_date
                ]);
            }
            $totalAmount = (clone $records)
                ->get()
                ->sum(fn($payment) => $payment->amount ?? 0);

            $collection = (clone $records)->get();

            $totalAmount = $collection->sum(fn($p) => $p->amount ?? 0);

            $totalPlannerCommission = 0;
            $totalPlannerPaid = 0;

            $totalPsCommission = 0;
            $totalPsPaid = 0;

            foreach ($collection as $row) {

                $plannerPercentage = optional($row->invoice)->p_bill_percentage ?? 0;

                $plannerAmount =
                    ($row->amount ?? 0) * ($plannerPercentage / 100);

                $totalPlannerCommission += $plannerAmount;

                if ($row->plannerPayout) {
                    $totalPlannerPaid += $plannerAmount;
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
                    ($row->amount ?? 0) * ($psPercentage / 100);

                $totalPsCommission += $psAmount;

                if ($row->productionStaffPayout) {
                    $totalPsPaid += $psAmount;
                }
            }

            $totalPlannerPending = $totalPlannerCommission - $totalPlannerPaid;
            $totalPsPending = $totalPsCommission - $totalPsPaid;
            $totalPaidAmount = $collection->sum(fn($p) => $p->plannerPayout || $p->productionStaffPayout ? $p->amount ?? 0 : 0);
            $totalBalanceAmount = $totalAmount - $totalPaidAmount;
            $totalPendingInvoices = Invoice::whereIn('payment_status', ['unpaid', 'partial'])->count();

            return DataTables::eloquent($records)
                ->addColumn('company_name', function ($row) {
                    return $row->invoice?->quotation?->workPlan?->company?->company_name ?? '-';
                })
                ->addColumn('planner_id', function ($row) {
                    return $row->invoice?->quotation?->workPlan?->company?->planner?->user_code ?? '-';
                })
                ->addColumn('invoice', function ($row) {
                    return $row->invoice?->invoice_number ?? '-';
                })
                ->addColumn('invoice_amount', function ($row) {
                    return $row->invoice?->grant_total ?? '-';
                })
                ->addColumn('balance_amount', function ($row) {
                    return $row->invoice?->balance_amount ?? '-';
                })
                ->addColumn('or_number', function ($row) {
                    return $row->custom_payment_id ?? '-';
                })
                ->addColumn('planner_commission', function ($row) {
                    $percentage =
                        optional($row->invoice)->p_bill_percentage ?? 0;

                    $amount = ($row->amount ?? 0) * ($percentage / 100);

                    return number_format($amount, 2);
                })
                ->addColumn('planner_commission_status', function ($row) {

                    if ($row->plannerPayout) {
                        return '<span class="badge bg-success">Paid</span>';
                    }

                    return '<span class="badge bg-warning">Pending</span>';
                })
                ->addColumn('ps_commission', function ($row) {

                    $percentage = optional(
                        optional(
                            optional(
                                optional(
                                    optional($row->invoice)->quotation
                                )->workPlan
                            )->company
                        )->productionStaff
                    )->production_c_percentage ?? 0;

                    $amount = ($row->amount ?? 0) * ($percentage / 100);

                    return number_format($amount, 2);
                })
                ->addColumn('ps_commission_status', function ($row) {

                    if ($row->productionStaffPayout) {
                        return '<span class="badge bg-success">Paid</span>';
                    }

                    return '<span class="badge bg-warning">Pending</span>';
                })
                ->editColumn('status', function ($row) {
                    if (!$row->status) {
                        return '<span class="badge bg-secondary">-</span>';
                    }
                    return match ($row->status) {
                        'pending' => '<span class="badge bg-warning">Not Paid</span>',
                        'closed' => '<span class="badge bg-success">Paid</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                })
                ->addColumn('action', function ($row) {

                    $plannerPaid = $row->plannerPayout ? true : false;
                    $psPaid = $row->productionStaffPayout ? true : false;

                    $buttons = '';

                    if (!$plannerPaid) {
                        $buttons .= '<button 
            class="btn btn-sm btn-primary me-1 settle-planner-btn"
            data-id="' . $row->id . '">
            Settle Planner
        </button>';
                    }

                    if (!$psPaid) {
                        $buttons .= '<button 
            class="btn btn-sm btn-info me-1 mt-1 settle-ps-btn"
            data-id="' . $row->id . '">
            Settle PS
        </button>';
                    }

                    if ($plannerPaid && $psPaid) {
                        return '<span class="badge bg-success">All Settled</span>';
                    }

                    return $buttons;
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('created_at', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                    if ($request->filled('company')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'company_id', $request->company);
                    }

                    // Planner filter
                    if ($request->filled('planner')) {
                        $query->whereRelation('invoice.quotation.workPlan.company', 'planner_id', $request->planner);
                    }

                    if ($request->filled('total')) {
                        $query->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
                    }

                    if ($request->filled('customer')) {
                        $query->whereHas(
                            'invoice.quotation.workPlan.company.userCompanies',
                            function ($q) use ($request) {
                                $q->where('user_id', $request->customer);
                            }
                        );
                    }
                })
                ->rawColumns(['planner_commission_status', 'ps_commission_status', 'status', 'action'])
                ->with([
                    'total_amount' => number_format($totalAmount, 2),
                    'pending_invoices' => $totalPendingInvoices,

                    'total_planner_commission' => number_format($totalPlannerCommission, 2),
                    'total_planner_paid' => number_format($totalPlannerPaid, 2),
                    'total_planner_pending' => number_format($totalPlannerPending, 2),

                    'total_ps_commission' => number_format($totalPsCommission, 2),
                    'total_ps_paid' => number_format($totalPsPaid, 2),
                    'total_ps_pending' => number_format($totalPsPending, 2),

                    'total_amount_dynamic' => number_format($totalAmount, 2),
                    'total_paid_dynamic' => number_format($totalPaidAmount, 2),
                    'total_balance_dynamic' => number_format($totalBalanceAmount, 2),
                ])
                ->make(true);
        }
        $totalGroups = Customer::get();
        $companies = Company::get(['id', 'company_name']);
        $cusUsers = User::role('Customer')->get(['id', 'name']);
        $planners = User::role('Planner')->get(['id', 'name']);
        $productions = User::role('Production Staff')->get(['id', 'name']);

        return view('admin.account-statement.outstanding', compact('totalGroups', 'companies', 'cusUsers', 'planners', 'productions'));
    }

    public function plannerMonthlyReport(Request $request)
    {
        $planners = User::role('Planner')->get();
        $totalGroups = Customer::with('billerProfile')->get();

        return view('admin.account-statement.planner-monthly', compact('planners', 'totalGroups'));
    }

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

    public function outstandingExport(Request $request)
    {
        $records = Payment::orderBy('created_at', 'desc');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        if ($request->filled('company')) {
            $records->whereRelation('invoice.quotation.workPlan', 'company_id', $request->company);
        }

        // Planner filter
        if ($request->filled('planner')) {
            $records->whereRelation('invoice.quotation.workPlan.company', 'planner_id', $request->planner);
        }

        if ($request->filled('customer')) {
            $records->whereHas(
                'invoice.quotation.workPlan.company.userCompanies',
                function ($q) use ($request) {
                    $q->where('user_id', $request->customer);
                }
            );
        }

        $collection = $records->get();

        $totalAmount = 0;
        $totalPlannerCommission = 0;
        $totalPlannerPaid = 0;

        $totalPsCommission = 0;
        $totalPsPaid = 0;

        $totalPaidAmount = 0;

        foreach ($collection as $row) {

            $amount = $row->amount ?? 0;
            $totalAmount += $amount;

            $plannerPercentage =
                optional($row->invoice)->p_bill_percentage ?? 0;

            $plannerAmount = $amount * ($plannerPercentage / 100);

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

            $psAmount = $amount * ($psPercentage / 100);

            $totalPsCommission += $psAmount;

            if ($row->productionStaffPayout) {
                $totalPsPaid += $psAmount;
                $totalPaidAmount += $amount;
            }
        }

        $totalPlannerPending = $totalPlannerCommission - $totalPlannerPaid;
        $totalPsPending = $totalPsCommission - $totalPsPaid;
        $totalBalanceAmount = $totalAmount - $totalPaidAmount;

        $pendingInvoices = Invoice::whereIn(
            'payment_status',
            ['unpaid', 'partial']
        )->count();


        // CSV download

        $filename = "outstanding-report.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use (
            $collection,
            $request,
            $totalAmount,
            $totalPaidAmount,
            $totalBalanceAmount,
            $totalPlannerCommission,
            $totalPlannerPaid,
            $totalPlannerPending,
            $totalPsCommission,
            $totalPsPaid,
            $totalPsPending,
            $pendingInvoices
        ) {

            $file = fopen('php://output', 'w');

            // ===== TOP INFO =====


            fputcsv($file, ['To Date', $request->to_date]);
            fputcsv($file, ['From Date', $request->from_date]);
            fputcsv($file, ['Total Outstanding', $totalAmount]);
            fputcsv($file, ['Pending Invoice', $pendingInvoices]);

            fputcsv($file, []);


            // ===== TABLE HEADER =====

            fputcsv($file, [
                'SL',
                'Customer',
                'Planner ID',
                'Invoice',
                'OR',
                'Amount',
                'Paid',
                'Balance',
                'Comm',
                // 'Planner Status',
                'Production',
                // 'PS Status',
                // 'Status'
            ]);

            $i = 1;

            foreach ($collection as $row) {

                $amount = $row->amount ?? 0;

                $plannerPercentage =
                    optional($row->invoice)->p_bill_percentage ?? 0;

                $plannerAmount =
                    $amount * ($plannerPercentage / 100);

                $psPercentage = optional(
                    optional(
                        optional(
                            optional(
                                optional($row->invoice)->quotation
                            )->workPlan
                        )->company
                    )->productionStaff
                )->production_c_percentage ?? 0;

                $psAmount = $amount * ($psPercentage / 100);

                fputcsv($file, [

                    $i++,

                    $row->invoice?->quotation?->workPlan?->company?->company_name,

                    $row->invoice?->quotation?->workPlan?->company?->planner?->user_code,

                    $row->invoice?->invoice_number,

                    $row->custom_payment_id,

                    $row->invoice?->grant_total,

                    $amount,

                    $row->invoice?->balance_amount,

                    number_format($plannerAmount, 2),

                    // $row->plannerPayout ? 'Paid' : 'Pending',

                    number_format($psAmount, 2),

                    // $row->productionStaffPayout ? 'Paid' : 'Pending',

                    // $row->status

                ]);
            }


            // ===== BOTTOM TOTALS =====

            fputcsv($file, []);
            fputcsv($file, ['TOTAL AMOUNT', $totalAmount]);
            fputcsv($file, ['TOTAL PAID', $totalPaidAmount]);
            fputcsv($file, ['TOTAL BALANCE', $totalBalanceAmount]);

            fputcsv($file, []);
            fputcsv($file, ['Planner Commission', number_format($totalPlannerCommission, 2)]);
            fputcsv($file, ['Planner Paid', number_format($totalPlannerPaid, 2)]);
            fputcsv($file, ['Planner Pending', number_format($totalPlannerPending, 2)]);

            fputcsv($file, []);
            fputcsv($file, ['PS Commission', number_format($totalPsCommission, 2)]);
            fputcsv($file, ['PS Paid', number_format($totalPsPaid, 2)]);
            fputcsv($file, ['PS Pending', number_format($totalPsPending, 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function outstandingPdf(Request $request)
    {
        $records = Payment::orderBy('created_at', 'desc');
        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }
        $company = $request->company ? Customer::find($request->company) : null;
        $planner = $request->planner ? User::find($request->planner) : null;
        $cusUser = $request->customer ? User::find($request->customer) : null;




        if ($request->filled('from_date') && $request->filled('to_date')) {
            $records->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        if ($request->filled('company')) {
            $records->whereRelation('invoice.quotation.workPlan', 'company_id', $request->company);
        }

        if ($request->filled('total')) {
            $records->whereRelation('invoice.quotation.workPlan', 'total_group_id', $request->total);
        }

        // Planner filter
        if ($request->filled('planner')) {
            $records->whereRelation('invoice.quotation.workPlan.company', 'planner_id', $request->planner);
        }

        if ($request->filled('customer')) {
            $records->whereHas(
                'invoice.quotation.workPlan.company.userCompanies',
                function ($q) use ($request) {
                    $q->where('user_id', $request->customer);
                }
            );
        }


        $collection = $records->get();

        $totalAmount = 0;
        $totalPlannerCommission = 0;
        $totalPlannerPaid = 0;

        $totalPsCommission = 0;
        $totalPsPaid = 0;

        $totalPaidAmount = 0;

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
        }

        $totalPlannerPending =
            $totalPlannerCommission - $totalPlannerPaid;

        $totalPsPending =
            $totalPsCommission - $totalPsPaid;

        $totalBalanceAmount =
            $totalAmount - $totalPaidAmount;

        $pendingInvoices = Invoice::whereIn(
            'payment_status',
            ['unpaid', 'partial']
        )->count();


        $pdf = Pdf::loadView(
            'admin.account-statement.outstanding-pdf',
            compact(
                'collection',
                'request',
                'totalAmount',
                'totalPaidAmount',
                'totalBalanceAmount',
                'totalPlannerCommission',
                'totalPlannerPaid',
                'totalPlannerPending',
                'totalPsCommission',
                'totalPsPaid',
                'totalPsPending',
                'pendingInvoices',
                'totalGroup',
                'company',
                'planner',
                'cusUser'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download('outstanding-report.pdf');

        return view('admin.account-statement.outstanding-pdf', compact(
            'collection',
            'request',
            'totalAmount',
            'totalPaidAmount',
            'totalBalanceAmount',
            'totalPlannerCommission',
            'totalPlannerPaid',
            'totalPlannerPending',
            'totalPsCommission',
            'totalPsPaid',
            'totalPsPending',
            'pendingInvoices',
        ));
    }

    public function plannerMonthlyExport(Request $request)
    {
        $year = $request->year;
        $plannerId = $request->planner_id;
        $companyId = $request->company_id ?? null;
        if (!$year || !$plannerId) {
            abort(404);
        }

        // Fetch invoices with payments and planner payouts for the FY
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $invoices = Invoice::with(['payments.plannerPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->whereHas('quotation.workPlan.company', fn($q) => $q->where('planner_id', $plannerId))
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'total_group_id', $companyId);
            })
            ->get();

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = ($i + 7) <= 12 ? $i + 7 : $i - 5; // July = 7
            $monthInvoices = $invoices->filter(fn($inv) => (int)$inv->invoice_date->format('n') === $month);

            $total = $monthInvoices->sum(fn($inv) => $inv->payments->sum('amount') * $inv->p_bill_percentage / 100);
            $paid = $monthInvoices->sum(
                fn($inv) =>
                $inv->payments->sum(fn($p) => $p->plannerPayout ? $p->plannerPayout->amount : 0)
            );

            $months[$month] = [
                'total' => $total,
                'paid' => $paid,
                'unpaid' => $total - $paid,
            ];
        }

        $planner = User::find($plannerId);
        $filename = "planner-yearly-report.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($months, $year, $planner) {
            $file = fopen('php://output', 'w');

            // Header info
            fputcsv($file, ['Planner Yearly Report']);
            fputcsv($file, ['Year', $year]);
            fputcsv($file, ['Planner', $planner->name]);
            fputcsv($file, []);

            // Table headers
            fputcsv($file, ['Month', 'Paid', 'Unpaid', 'Total']);

            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            $grandTotal = 0;
            $grandPaid = 0;
            $grandUnpaid = 0;

            foreach ($monthNames as $num => $name) {
                $data = $months[$num] ?? ['total' => 0, 'paid' => 0, 'unpaid' => 0];

                fputcsv($file, [
                    $name,
                    number_format($data['paid'], 2),
                    number_format($data['unpaid'], 2),
                    number_format($data['total'], 2),
                ]);

                $grandTotal += $data['total'];
                $grandPaid += $data['paid'];
                $grandUnpaid += $data['unpaid'];
            }

            // Grand totals
            fputcsv($file, []);
            fputcsv($file, ['Total', number_format($grandPaid, 2), number_format($grandUnpaid, 2), number_format($grandTotal, 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function plannerMonthlyPdf(Request $request)
    {
        $year = $request->year;
        $plannerId = $request->planner_id;
        $companyId = $request->company_id ?? null;
        $company = $companyId ? Customer::find($companyId) : null;
        if (!$year || !$plannerId) {
            abort(404);
        }

        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $invoices = Invoice::with(['payments.plannerPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->whereHas('quotation.workPlan.company', function ($q) use ($plannerId) {
                $q->where('planner_id', $plannerId);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'total_group_id', $companyId);
            })
            ->get();

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $rows = [];

        $grandTotal = 0;
        $grandPaid = 0;
        $grandUnpaid = 0;

        foreach ($monthNames as $monthNum => $monthName) {

            $monthInvoices = $invoices->filter(function ($invoice) use ($monthNum) {
                return (int) $invoice->invoice_date->format('n') === $monthNum;
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

            $unpaid = $total - $paid;

            $rows[] = [
                'month' => $monthName,
                'paid' => number_format($paid, 2),
                'unpaid' => number_format($unpaid, 2),
                'total' => number_format($total, 2),
            ];

            $grandTotal += $total;
            $grandPaid += $paid;
            $grandUnpaid += $unpaid;
        }

        $planner = User::find($plannerId);

        $fyLabel = "FY $year";

        $pdf = Pdf::loadView(
            'admin.account-statement.planner-monthly-pdf',
            compact(
                'rows',
                'year',
                'planner',
                'grandTotal',
                'grandPaid',
                'grandUnpaid',
                'fyLabel',
                'company'
            )
        )->setPaper('a4', 'portrait');

        return $pdf->download('planner-yearly-report.pdf');
    }

    public function monthlyInvoicesDetails(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $plannerId = $request->planner_id;
        $companyId = $request->company_id;

        $type = $request->type;

        $fyStart = $year . '-01-01';
        $fyEnd = ($year + 1) . '-12-31';

        $monthStart = date('Y-m-d', strtotime("$fyStart +" . ($month - 1) . " months"));
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $payments = Payment::with(['invoice.customer', 'plannerPayout'])
            ->whereHas('invoice', function ($q) use ($monthStart, $monthEnd, $plannerId) {
                $q->whereBetween('invoice_date', [$monthStart, $monthEnd])
                    ->when($plannerId, fn($q2) => $q2->whereRelation('quotation.workPlan.company', 'planner_id', $plannerId));
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('invoice.quotation.workPlan', 'total_group_id', $companyId);
            });

        if ($type == 'paid') {
            $payments->whereHas('plannerPayout');
        } else {
            $payments->whereDoesntHave('plannerPayout');
        }

        $payments = $payments->get();

        $invoices = $payments->groupBy(fn($p) => $p->invoice_id)
            ->values() // Reset keys before mapping to ensure consistent index
            ->map(function ($paymentsForInvoice, $index) use ($type) {
                $invoice = $paymentsForInvoice->first()->invoice;
                $psPercentage = $invoice->p_bill_percentage ?? 0;

                return [
                    'sl_no' => $index + 1, // Added Serial Number
                    'invoice_number' => $invoice->invoice_number ?? '',
                    'work_plan' => $invoice->quotation->workPlan->workplan_number ?? '',
                    'type' => $type,
                    'payments' => $paymentsForInvoice->map(fn($p) => [
                        'or' => $p->custom_payment_id ?? '',
                        'amount' => $p->amount * $psPercentage / 100
                    ])->values()
                ];
            })->values();

        return response()->json($invoices);
    }


    public function psMonthlyReport(Request $request)
    {
        $planners = User::role('Production Staff')->get();
        $totalGroups = Customer::get();
        return view('admin.account-statement.ps-monthly', compact('planners', 'totalGroups'));
    }

    public function getpsMonthlyInvoices(Request $request)
    {
        $year = $request->year ?? date('Y');
        // Auto-filter for Production Staff role
        $plannerId = Auth::user()->hasRole('Production Staff') ? Auth::id() : $request->planner_id;
        $companyId = $request->company_id ?? null;

        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        // Load invoices with payments and planner payouts
        $invoices = Invoice::with(['payments.productionStaffPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->when($plannerId, function ($query) use ($plannerId) {
                $query->whereRelation('quotation.workPlan.company', 'production_staff_id', $plannerId);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'total_group_id', $companyId);
            })
            ->get();

        // Prepare monthly totals
        $totals = [];

        for ($i = 0; $i < 12; $i++) {

            // Map July as 1, June as 12
            $month = ($i + 7) <= 12 ? $i + 7 : $i - 5;

            $monthInvoices = $invoices->filter(function ($invoice) use ($month) {
                return (int) $invoice->invoice_date->format('n') === $month;
            });

            $total = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum('amount')
                    * $invoice->quotation->workPlan->company->productionStaff->production_c_percentage / 100;
            });

            $paid = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum(function ($payment) {
                    return $payment->productionStaffPayout
                        ? $payment->productionStaffPayout->amount
                        : 0;
                });
            });

            $totals[] = [
                'month' => $month, // ✅ FIX
                'total' => $total,
                'paid' => number_format($paid, 2),
                'unpaid' => number_format($total - $paid, 2),
            ];
        }

        return response()->json($totals);
    }

    public function monthlyPSInvoicesDetails(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $plannerId = $request->planner_id;
        $type = $request->type;
        $companyId = $request->company_id ?? null;

        $fyStart = $year . '-01-01';
        $fyEnd = ($year + 1) . '-12-31';

        $monthStart = date('Y-m-d', strtotime("$fyStart +" . ($month - 1) . " months"));
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $payments = Payment::with(['invoice.customer', 'productionStaffPayout'])
            ->whereHas('invoice', function ($q) use ($monthStart, $monthEnd, $plannerId) {
                $q->whereBetween('invoice_date', [$monthStart, $monthEnd])
                    ->when($plannerId, fn($q2) => $q2->whereRelation('quotation.workPlan.company', 'production_staff_id', $plannerId));
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('invoice.quotation.workPlan', 'total_group_id', $companyId);
            });

        if ($type == 'paid') {
            $payments->whereHas('productionStaffPayout');
        } else {
            $payments->whereDoesntHave('productionStaffPayout');
        }

        $payments = $payments->get();

        // Group payments by invoice
        $invoices = $payments->groupBy(fn($p) => $p->invoice_id)
            ->map(function ($paymentsForInvoice, $index) use ($type) {
                $invoice = $paymentsForInvoice->first()->invoice;
                $psPercentage = $invoice->quotation->workPlan->company->productionStaff->production_c_percentage ?? 0;

                return [
                    'sl_no' => $index + 1, // Added Serial Number
                    'invoice_number' => $invoice->invoice_number ?? '',
                    'work_plan' => $invoice->quotation->workPlan->workplan_number ?? '',
                    'type' => $type,
                    'payments' => $paymentsForInvoice->map(fn($p) => [
                        'or' => $p->custom_payment_id ?? '',
                        'amount' => $p->amount * $psPercentage / 100
                    ])->values()
                ];
            })->values();

        return response()->json($invoices);
    }

    public function psMonthlyExport(Request $request)
    {
        $year = $request->year;
        $plannerId = $request->planner_id;
        $companyId = $request->company_id ?? null;


        if (!$year || !$plannerId) {
            abort(404);
        }

        // Fetch invoices with payments and planner payouts for the FY
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $invoices = Invoice::with(['payments.productionStaffPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->whereHas('quotation.workPlan.company', fn($q) => $q->where('production_staff_id', $plannerId))
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'company_id', $companyId);
            })
            ->get();

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = ($i + 7) <= 12 ? $i + 7 : $i - 5; // July = 7
            $monthInvoices = $invoices->filter(fn($inv) => (int)$inv->invoice_date->format('n') === $month);

            $total = $monthInvoices->sum(fn($inv) => $inv->payments->sum('amount') * $inv->quotation->workPlan->company->productionStaff->production_c_percentage / 100);
            $paid = $monthInvoices->sum(
                fn($inv) =>
                $inv->payments->sum(fn($p) => $p->productionStaffPayout ? $p->productionStaffPayout->amount : 0)
            );

            $months[$month] = [
                'total' => $total,
                'paid' => $paid,
                'unpaid' => $total - $paid,
            ];
        }

        $planner = User::find($plannerId);
        $filename = "ps-yearly-report.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($months, $year, $planner) {
            $file = fopen('php://output', 'w');

            // Header info
            fputcsv($file, ['Production Staff Yearly Report']);
            fputcsv($file, ['Year', $year]);
            fputcsv($file, ['Planner', $planner->name]);
            fputcsv($file, []);

            // Table headers
            fputcsv($file, ['Month', 'Paid', 'Unpaid', 'Total']);

            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            $grandTotal = 0;
            $grandPaid = 0;
            $grandUnpaid = 0;

            foreach ($monthNames as $num => $name) {
                $data = $months[$num] ?? ['total' => 0, 'paid' => 0, 'unpaid' => 0];

                fputcsv($file, [
                    $name,
                    number_format($data['paid'], 2),
                    number_format($data['unpaid'], 2),
                    number_format($data['total'], 2),
                ]);

                $grandTotal += $data['total'];
                $grandPaid += $data['paid'];
                $grandUnpaid += $data['unpaid'];
            }

            // Grand totals
            fputcsv($file, []);
            fputcsv($file, ['Total', number_format($grandPaid, 2), number_format($grandUnpaid, 2), number_format($grandTotal, 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function psMonthlyPdf(Request $request)
    {
        $year = $request->year;
        $plannerId = $request->planner_id;
        $companyId = $request->company_id ?? null;
        $company = $companyId ? Customer::find($companyId) : null;
        if (!$year || !$plannerId) {
            abort(404);
        }

        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $invoices = Invoice::with(['payments.productionStaffPayout'])
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->whereHas('quotation.workPlan.company', function ($q) use ($plannerId) {
                $q->where('production_staff_id', $plannerId);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereRelation('quotation.workPlan', 'total_group_id', $companyId);
            })
            ->get();

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $rows = [];

        $grandTotal = 0;
        $grandPaid = 0;
        $grandUnpaid = 0;

        foreach ($monthNames as $monthNum => $monthName) {

            $monthInvoices = $invoices->filter(function ($invoice) use ($monthNum) {
                return (int) $invoice->invoice_date->format('n') === $monthNum;
            });

            $total = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum('amount') * $invoice->quotation->workPlan->company->productionStaff->production_c_percentage / 100;
            });

            $paid = $monthInvoices->sum(function ($invoice) {
                return $invoice->payments->sum(function ($payment) {
                    return $payment->productionStaff
                        ? $payment->productionStaff->amount
                        : 0;
                });
            });

            $unpaid = $total - $paid;

            $rows[] = [
                'month' => $monthName,
                'paid' => number_format($paid, 2),
                'unpaid' => number_format($unpaid, 2),
                'total' => number_format($total, 2),
            ];

            $grandTotal += $total;
            $grandPaid += $paid;
            $grandUnpaid += $unpaid;
        }

        $planner = User::find($plannerId);

        $fyLabel = "FY $year";

        $title = "Production Staff";

        $pdf = Pdf::loadView(
            'admin.account-statement.planner-monthly-pdf',
            compact(
                'rows',
                'year',
                'planner',
                'grandTotal',
                'grandPaid',
                'grandUnpaid',
                'fyLabel',
                'company',
                'title'
            )
        )->setPaper('a4', 'portrait');

        return $pdf->download('ps-monthly-report.pdf');
    }

    public function exportTotalGroupPdf(Request $request)
    {
        $records = WorkPlan::with(['quotation.invoice', 'company.productionStaff'])
            ->whereHas('quotation.invoice');

        if ($request->total_group) {
            $records->where('total_group_id', $request->total_group);
        }

        if ($request->start_date && $request->end_date) {
            $records->whereBetween('date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $records = $records->get();

        $totalGroup = null;
        if ($request->total_group) {
            $totalGroup = Customer::find($request->total_group);
        } else {
            $totalGroup = Customer::withoutGlobalScope('exclude_default')->where('customer_name', 'Default')->first();
        }
        $startDate = $request->start_date;
        $endDate = $request->end_date;


        $pdf = Pdf::loadView(
            'admin.account-statement.tg-pdf',
            compact('records', 'totalGroup', 'startDate', 'endDate')
        );

        return $pdf->stream('total-group.pdf');
    }
}
