<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PlannerDocumentFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class CompanyPlannerDocumentController extends Controller implements HasMiddleware
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
            $records = PlannerDocumentFile::query()
                ->whereRelation('plannerDocument.planner', 'id', $company->planner_id)
                ->with('plannerDocument.planner')
                ->orderByDesc('id');

            return DataTables::eloquent($records)
                ->addColumn('title', function ($row) {
                    return $row->plannerDocument->title ?? '-';
                })
                ->addColumn('planner', function ($row) {
                    return $row->plannerDocument->planner->name ?? '-';
                })
                ->editColumn('type', function ($row) {
                    return $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d M Y h:i A') ?? '-';
                })
                ->addColumn('file_name', function ($row) {
                    return basename($row->document);
                })
                ->addColumn('actions', function ($row) use ($company) {
                    return view('admin.company-planner-document.partials.action', compact('row', 'company'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.company-planner-document.index', compact('company'));
    }

    public function download(Company $company, PlannerDocumentFile $plannerDocumentFile)
    {
        $relativePath = ltrim(str_replace('storage/', '', $plannerDocumentFile->document), '/');
        $filePath = storage_path('app/public/' . $relativePath);

        abort_unless(file_exists($filePath), 404);

        return response()->download($filePath, basename($plannerDocumentFile->document));
    }
}
