<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\WorkPlanAttachment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class CompanyWorkOrderDocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document.view'), ['index', 'download']),
        ];
    }

    public function index(Company $company, Request $request)
    {
        if ($request->ajax()) {
            $records = WorkPlanAttachment::query()
                ->whereRelation('workPlan', 'company_id', $company->id)
                ->with('workPlan')
                ->orderByDesc('id');

            if ($request->filled('work_plan')) {
                $records->where('work_plan_id', $request->work_plan);
            }

            return DataTables::eloquent($records)
                ->addColumn('work_order', function ($row) {
                    return $row->workPlan->workplan_number ?? '-';
                })
                ->editColumn('type', function ($row) {
                    return $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d M Y h:i A') ?? '-';
                })
                ->editColumn('entity', function ($row) {
                    return ucwords($row->entity ?? 'N/A');
                })
                ->addColumn('actions', function ($row) use ($company) {
                    return view('admin.company-work-order-document.partials.action', compact('row', 'company'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['actions'])
                ->make(true);
        }

        $workPlans = $company->workPlans()
            ->orderBy('workplan_number', 'asc')
            ->get(['id', 'workplan_number']);

        return view('admin.company-work-order-document.index', compact('company', 'workPlans'));
    }

    public function download(Company $company, WorkPlanAttachment $workPlanAttachment)
    {
        abort_if(optional($workPlanAttachment->workPlan)->company_id !== $company->id, 404);

        abort_unless(Storage::disk('public')->exists($workPlanAttachment->file), 404);

        $downloadName = basename($workPlanAttachment->file);

        return Storage::disk('public')->download($workPlanAttachment->file, $downloadName);
    }
}
