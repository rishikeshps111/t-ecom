<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\PlannerDocument;
use Illuminate\Support\Facades\DB;
use App\Models\PlannerDocumentFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlannerDocumentExport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Http\Requests\Admin\StorePlannerDocumentRequest;
use App\Http\Requests\Admin\UpdatePlannerDocumentRequest;

class PlannerDocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document.view'), ['index', 'show']),
            new Middleware(PermissionMiddleware::using('document.edit'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('document.edit'),  ['edit', 'update', 'statusView', 'status']),
            new Middleware(PermissionMiddleware::using('document.delete'), ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = PlannerDocument::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.planner-document.partials.action', compact('row'))->render();
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup->customer_name ?? '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->companyType->name ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->planner->name ?? '-';
                })
                ->addColumn('year', function ($row) {
                    return $row->financialYear->year ?? '-';
                })
                ->editColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date->format('d M Y')  : '-';
                })
                ->editColumn('end_date', function ($row) {
                    return $row->end_date ? $row->end_date->format('d M Y')  : '-';
                })
                ->editColumn('status', function ($row) {
                    $classes = [
                        'active'  => 'success',
                        'inactive' => 'danger'
                    ];
                    $status = $row->status ?? 'draft';
                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst(str_replace('_', ' ', $status)) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('name')) {
                        $query->where('title', 'like', '%' . $request->name . '%');
                    }
                    if ($request->filled('company')) {
                        $query->where('company_id', $request->company);
                    }
                    if ($request->filled('year')) {
                        $query->where('financial_year_id', $request->year);
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id', $request->total_group);
                    }
                    if ($request->filled('type')) {
                        $query->where('company_type_id', $request->type);
                    }
                    if ($request->filled('planner')) {
                        $query->where('planner_id', $request->planner);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $companies  = Company::get();
        $years = FinancialYear::orderBy('year', 'desc')->get(['year', 'id']);
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->get();
        $planners = User::role('Planner')->get();
        $totalGroups = Customer::get();
        return view('admin.planner-document.index', compact('companies', 'years', 'types', 'corpUsers', 'totalGroups', 'planners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies  = Company::get();
        $years = FinancialYear::orderBy('year', 'desc')->get(['year', 'id']);
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->get();
        $totalGroups = Customer::get();
        $planners = User::role('Planner')->get();

        return view('admin.planner-document.create', compact('companies', 'years', 'types', 'corpUsers', 'totalGroups', 'planners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlannerDocumentRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $plannerDocument = PlannerDocument::create($data);
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $i => $file) {
                    $path = $file->store('planner-documents', 'public');

                    $plannerDocument->files()->create([
                        'document' => 'storage/' . $path,
                        'type' => $request->document_types[$i],
                    ]);
                }
            }

            activity()
                ->causedBy(Auth::id())
                ->performedOn($plannerDocument)
                ->log('Create Planner Document');
        });

        return redirect()
            ->route('admin.planner-documents.index')
            ->with('success', 'Planner document created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlannerDocument $plannerDocument)
    {

        return response()->json([
            'html' => view('admin.planner-document.view', compact('plannerDocument'))->render(),
            'title' => 'View Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlannerDocument $plannerDocument)
    {
        $companies  = Company::get();
        $years = FinancialYear::get(['year', 'id']);
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->get();
        $totalGroups = Customer::get();
        $planners = User::role('Planner')->get();

        return view('admin.planner-document.edit', compact('companies', 'plannerDocument', 'years', 'types', 'corpUsers', 'totalGroups', 'planners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlannerDocumentRequest $request, PlannerDocument $plannerDocument)
    {
        DB::transaction(function () use ($request, $plannerDocument) {
            $data = $request->validated();
            $plannerDocument->update($data);
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $i => $file) {
                    $path = $file->store('planner-documents', 'public');
                    $plannerDocument->files()->create([
                        'document' => 'storage/' . $path,
                        'type' => $request->document_types[$i] ?? 'other',
                    ]);
                }
            }
            if ($request->filled('remove_files')) {
                $files = PlannerDocumentFile::whereIn('id', $request->remove_files)->get();

                foreach ($files as $file) {
                    $file->delete();
                }
            }

            if ($request->has('document_types_existing')) {
                foreach ($request->document_types_existing as $id => $type) {
                    $file = PlannerDocumentFile::find($id);
                    if ($file) $file->update(['type' => $type]);
                }
            }
            activity()
                ->causedBy(Auth::id())
                ->performedOn($plannerDocument)
                ->log('Update Planner Document');
        });

        return redirect()
            ->route('admin.planner-documents.index')
            ->with('success', 'Planner document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlannerDocument $plannerDocument)
    {
        $plannerDocument->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($plannerDocument)
            ->log('Delete Planner Document');

        return response()->json(['success' => true]);
    }

    public function statusView(Request $request)
    {
        $project = PlannerDocument::find($request->id);

        return response()->json([
            'html' => view('admin.planner-document.status', compact('project'))->render(),
            'title' => 'Change Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:planner_documents,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $record = PlannerDocument::findOrFail($request->project_id);
        $record->status = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Planner Document');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function export(Request $request)
    {

        return response()->streamDownload(function () {
            echo Excel::raw(new PlannerDocumentExport(), \Maatwebsite\Excel\Excel::CSV);
        }, 'planner-documents.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="planner-documents.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }
}
