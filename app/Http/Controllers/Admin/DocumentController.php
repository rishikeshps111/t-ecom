<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DocumentCompanyExport;
use App\Exports\DocumentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Customer;
use App\Models\Document;
use App\Models\FinancialYear;
use App\Models\Project;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\WorkPlan;
use App\Models\WorkPlanAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

use function Symfony\Component\String\u;

class DocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document.view'), ['index', 'show']),
            new Middleware(PermissionMiddleware::using('document.edit'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('document.edit'),  ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('document.delete'), ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $companyId = $request->input('company_id') ?? null;
        if ($request->ajax()) {
            $query = WorkPlanAttachment::whereNotIn('entity', ['OR', 'IN', 'QO']);
            $records = $query->orderBy('created_at', 'desc');

            // Auto-filter for Customer role to show only their company's documents
            if (Auth::user()->hasRole('Customer')) {
                $customerCompanyIds = UserCompany::where('user_id', Auth::id())
                    ->pluck('company_id')
                    ->toArray();

                if (!empty($customerCompanyIds)) {
                    $records->whereRelation('workPlan', fn($q) => $q->whereIn('company_id', $customerCompanyIds));
                }
            } elseif ($request->company_id) {
                $records->whereRelation('workPlan', 'company_id', $request->company_id);
            }
            return DataTables::eloquent($records)
                ->editColumn('type', function ($row) {
                    return $row->type ? ucfirst($row->type) : '-';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? '-';
                })
                ->editColumn('year', function ($row) {
                    return $row->year ?? '-';
                })
                ->addColumn('service_type', function ($row) {
                    return $row->serviceType->name ?? '-';
                })
                ->addColumn('work_plan', function ($row) use ($companyId) {
                    return $row->workPlan->workplan_number ?? '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->workPlan->company->company_name ?? '-';
                })
                ->editColumn('name', function ($row) {
                    return ucfirst(str_replace(
                        ['_', '-'],
                        ' ',
                        pathinfo($row->name, PATHINFO_FILENAME)
                    ));
                })
                ->addColumn('actions', function ($row) use ($companyId) {
                    return view('admin.document.partials.action', compact('row',  'companyId'))->render();
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('work_plan')) {
                        $query->where('work_plan_id', $request->work_plan);
                    }
                    if ($request->filled('entity')) {
                        $query->where('entity', $request->entity);
                    }
                    if ($request->filled('type')) {
                        $query->where('type', $request->type);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $from = Carbon::parse($request->from_date)->subDay()->startOfDay();
                        $to = Carbon::parse($request->to_date)->addDay()->endOfDay();

                        $query->whereBetween('created_at', [$from, $to]);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $workPlans = WorkPlan::get();
        $companies = Company::orderBy('company_name', 'asc')->get();
        return view('admin.document.index', compact('workPlans', 'companyId', 'companies'));
    }

    public function company(Request $request)
    {
        $companyId = $request->input('company_id') ?? null;
        if ($request->ajax()) {
            $query = WorkPlanAttachment::whereIn('entity', ['OR', 'IN', 'QO']);
            $records = $query->orderBy('created_at', 'desc');

            // Auto-filter for Customer role to show only their company's documents
            if (Auth::user()->hasRole('Customer')) {
                $customerCompanyIds = UserCompany::where('user_id', Auth::id())
                    ->pluck('company_id')
                    ->toArray();

                if (!empty($customerCompanyIds)) {
                    $records->whereRelation('workPlan', fn($q) => $q->whereIn('company_id', $customerCompanyIds));
                }
            } elseif ($request->company_id) {
                $records->whereRelation('workPlan', 'company_id', $request->company_id);
            }
            return DataTables::eloquent($records)
                ->editColumn('type', function ($row) {
                    return $row->type ? ucfirst($row->type) : '-';
                })
                ->editColumn('entity', function ($row) {

                    $map = [
                        'QO' => 'Quotation',
                        'IN' => 'Invoice',
                        'OR' => 'Original Receipt',
                    ];

                    return $map[$row->entity] ?? ucfirst($row->entity ?? '-');
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('work_plan', function ($row) use ($companyId) {
                    return $row->workPlan->workplan_number ?? '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->workPlan->company->company_name ?? '-';
                })
                ->editColumn('name', function ($row) {
                    return ucfirst(str_replace(
                        ['_', '-'],
                        ' ',
                        pathinfo($row->name, PATHINFO_FILENAME)
                    ));
                })
                ->addColumn('actions', function ($row) use ($companyId) {
                    return view('admin.document.partials.action', compact('row',  'companyId'))->render();
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('work_plan')) {
                        $query->where('work_plan_id', $request->work_plan);
                    }
                    if ($request->filled('entity')) {
                        $query->where('entity', $request->entity);
                    }
                    if ($request->filled('type')) {
                        $query->where('type', $request->type);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $from = Carbon::parse($request->from_date)->subDay()->startOfDay();
                        $to = Carbon::parse($request->to_date)->addDay()->endOfDay();

                        $query->whereBetween('created_at', [$from, $to]);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $workPlans = WorkPlan::get();
        $companies = Company::orderBy('company_name', 'asc')->get();
        return view('admin.document.company', compact('workPlans', 'companyId', 'companies'));
    }

    // public function index(Request $request)
    // {
    //     $companyId = $request->input('company_id') ?? null;
    //     if ($request->ajax()) {
    //         $query = Document::query();

    //         // if ($request->filled('type')) {
    //         //     $query->where('type', $request->type);
    //         // }

    //         if ($request->filled('company_id')) {
    //             $query->where('company_id', $request->company_id);
    //         }

    //         if ($request->filled('project_id')) {
    //             $query->where('project_id', $request->project_id);
    //         }

    //         $records = $query->orderBy('created_at', 'desc');

    //         return DataTables::eloquent($records)
    //             ->editColumn('type', function ($row) {
    //                 return $row->type ? ucfirst($row->type) : '-';
    //             })
    //             ->addColumn('actions', function ($row) use ($companyId) {
    //                 return view('admin.document.partials.action', compact('row',  'companyId'))->render();
    //             })
    //             ->editColumn('valid_from', function ($row) {
    //                 return $row->valid_from
    //                     ? $row->valid_from->format('d M Y')
    //                     : '-';
    //             })
    //             ->editColumn('valid_to', function ($row) {
    //                 return $row->valid_to
    //                     ? $row->valid_to->format('d M Y')
    //                     : '-';
    //             })
    //             ->addColumn('total_group', function ($row) {
    //                 return $row->totalGroup->customer_name ?? '-';
    //             })
    //             ->editColumn('type', function ($row) {
    //                 return $row->companyType->name ?? '-';
    //             })
    //             ->addColumn('company', function ($row) {
    //                 return $row->company ? $row->company->company_name : '-';
    //             })
    //             ->addColumn('project', function ($row) {
    //                 return $row->project ? $row->project->name : '-';
    //             })
    //             ->addColumn('year', function ($row) {
    //                 return $row->financialYear->year ?? '-';
    //             })
    //             ->addColumn('status', function ($row) {

    //                 $classes = [
    //                     'expired' => 'danger',
    //                     'active'  => 'success',
    //                     'inactive'  => 'danger',
    //                 ];

    //                 $status = $row->status ?? 'draft';

    //                 return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
    //                     . ucfirst($status) .
    //                     '</span>';
    //             })
    //             ->addIndexColumn()
    //             ->filter(function ($query) use ($request) {
    //                 if ($request->filled('status')) {
    //                     $query->where('status', $request->status);
    //                 }
    //                 if ($request->filled('title')) {
    //                     $query->where('title', 'like', '%' . u($request->title)->trim() . '%');
    //                 }
    //                 // if ($request->filled('document_type')) {
    //                 //     $query->where('type', $request->document_type);
    //                 // }
    //                 if ($request->filled('company')) {
    //                     $query->where('company_id', $request->company);
    //                 }
    //                 if ($request->filled('project')) {
    //                     $query->where('project_id', $request->project);
    //                 }
    //                 if ($request->filled('year')) {
    //                     $query->where('financial_year_id', $request->year);
    //                 }
    //                 if ($request->filled('total_group')) {
    //                     $query->where('total_group_id', $request->total_group);
    //                 }
    //                 if ($request->filled('type')) {
    //                     $query->where('company_type_id', $request->type);
    //                 }
    //             })
    //             ->rawColumns(['actions', 'status'])
    //             ->make(true);
    //     }
    //     $companies = Company::get(['company_name', 'id']);
    //     $projects = Project::get(['name', 'id']);
    //     $years = FinancialYear::orderBy('year', 'desc')->get(['year', 'id']);
    //     $types = CompanyType::get();
    //     $corpUsers = User::role('Corp User')->get();
    //     $totalGroups = Customer::get();
    //     return view('admin.document.index', compact('companyId',  'companies',  'years', 'types', 'corpUsers', 'totalGroups'));
    // }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $companyId = $request->input('company_id') ?? null;
        $companies = Company::get(['company_name', 'id']);
        $projects = Project::get(['name', 'id']);
        $years = FinancialYear::orderBy('year', 'desc')->get(['year', 'id']);
        $types = CompanyType::get();
        $corpUsers = User::role('Corp User')->get();
        $totalGroups = Customer::get();

        return view('admin.document.create', compact('companyId',  'companies',  'years', 'types', 'corpUsers', 'totalGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store(
                'documents/' . $request->company_id . '/' . ($request->project_id ?? 'general'),
                'public'
            );
            $data['document'] = $path;
        }

        $document = Document::create($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($document)
            ->log('Create Document');

        $params = [];
        if ($request->has('redirect')) {
            $params = array_filter([
                'company_id' => $request->company_id
            ]);
        }
        return redirect()
            ->route('admin.documents.index', $params)
            ->with('success', 'Document created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return response()->json([
            'html' => view('admin.document.view', compact('document'))->render(),
            'title' => 'View Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document, Request $request)
    {
        $companyId = $request->input('company_id') ?? null;
        $companies = Company::get(['company_name', 'id']);
        $years = FinancialYear::get(['year', 'id']);
        $types = CompanyType::get();
        $corpUsers = User::role('Corp User')->get();
        $totalGroups = Customer::get();

        return view('admin.document.edit', compact('companyId',  'document', 'companies', 'years', 'types', 'corpUsers', 'totalGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $data = $request->validated();

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store(
                'documents/' . $request->company_id . '/' . ($request->project_id ?? 'general'),
                'public'
            );

            $data['document'] = $path;
        }

        $document->update($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($document)
            ->log('Update Document');

        $params = [];
        if ($request->has('redirect')) {
            $params = array_filter([
                'company_id' => $request->company_id
            ]);
        }

        return redirect()
            ->route('admin.documents.index', $params)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($document)
            ->log('Delete Document');
        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        return Excel::download(new DocumentExport($request), 'documents.xlsx');;
    }

    public function exportCompany(Request $request)
    {
        return Excel::download(new DocumentCompanyExport($request), 'documents.xlsx');;
    }
}
