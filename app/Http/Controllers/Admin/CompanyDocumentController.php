<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDocument;
use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\FinancialYear;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class CompanyDocumentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document.view'), ['index', 'download']),
            new Middleware(PermissionMiddleware::using('document.edit'), ['store']),
            new Middleware(PermissionMiddleware::using('document.delete'), ['destroy']),
        ];
    }

    public function index(Company $company, Request $request)
    {
        if ($request->ajax()) {
            $records = CompanyDocument::where('company_id', $company->id)
                ->with(['totalGroup', 'financialYear'])
                ->orderByDesc('id');

            if ($request->filled('type')) {
                $records->where('type', $request->type);
            }

            return DataTables::eloquent($records)
                ->editColumn('title', function ($row) {
                    return $row->title ?: '-';
                })
                ->addColumn('file_name', function ($row) {
                    return $row->file_name;
                })
                ->editColumn('type', function ($row) {
                    return $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-';
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup->customer_name ?? '-';
                })
                ->addColumn('financial_year', function ($row) {
                    return $row->financialYear->year ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d M Y h:i A') ?? '-';
                })
                ->addColumn('actions', function ($row) use ($company) {
                    return view('admin.company-document.partials.action', compact('row', 'company'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['actions'])
                ->make(true);
        }

        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('type', 'asc')
            ->get();
        $totalGroups = Customer::orderBy('customer_name', 'asc')->get(['id', 'customer_name']);
        $financialYears = FinancialYear::orderBy('year', 'desc')->get(['id', 'year', 'is_active']);
        $activeFinancialYearId = $financialYears->firstWhere('is_active', true)?->id;

        return view('admin.company-document.index', compact(
            'company',
            'documentTypes',
            'totalGroups',
            'financialYears',
            'activeFinancialYearId'
        ));
    }

    public function store(Company $company, Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'total_group_id' => ['required', 'exists:customers,id'],
            'financial_year_id' => ['required', 'exists:financial_years,id'],
            'type' => ['required', 'string', 'exists:document_types,type'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('company-documents/' . $company->id, 'public');

        CompanyDocument::create([
            'company_id' => $company->id,
            'title' => $validated['title'],
            'total_group_id' => $validated['total_group_id'],
            'financial_year_id' => $validated['financial_year_id'],
            'type' => $validated['type'],
            'file' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer document uploaded successfully.',
        ]);
    }

    public function download(Company $company, CompanyDocument $companyDocument)
    {
        abort_if($companyDocument->company_id !== $company->id, 404);

        abort_unless(Storage::disk('public')->exists($companyDocument->file), 404);

        return Storage::disk('public')->download($companyDocument->file, $companyDocument->file_name);
    }

    public function destroy(Company $company, CompanyDocument $companyDocument)
    {
        abort_if($companyDocument->company_id !== $company->id, 404);

        if ($companyDocument->file && Storage::disk('public')->exists($companyDocument->file)) {
            Storage::disk('public')->delete($companyDocument->file);
        }

        $companyDocument->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer document deleted successfully.',
        ]);
    }
}
