<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreCompanyTypeRequest;
use App\Http\Requests\Admin\UpdateCompanyTypeRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompanyTypeController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'update', 'destroy', 'status']),
        ];
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = CompanyType::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.company-type.partials.action', compact('row'))->render();
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        return '<button class="btn btn-sm toggleStatus text-success" data-id="' . $row->id . '" data-status="1">
                            <i class="fa-solid fa-toggle-on fa-lg"></i>
                        </button>';
                    } else {
                        return '<button class="btn btn-sm toggleStatus text-secondary" data-id="' . $row->id . '" data-status="0">
                            <i class="fa-solid fa-toggle-off fa-lg"></i>
                        </button>';
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view('admin.company-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->id) {
            $record = CompanyType::findOrFail($request->id);
            return response()->json([
                'html' => view('admin.company-type.form', compact('record'))->render(),
                'title' => 'Update Company Type'
            ]);
        }
        return response()->json([
            'html' => view('admin.company-type.form')->render(),
            'title' => 'Add Company Type',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyTypeRequest $request)
    {
        $data = $request->validated();
        $companyType = CompanyType::create($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyType)
            ->log('Created Company Type');

        return response()->json([
            'message' => 'Company Type created successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyType $companyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyType $companyType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyTypeRequest $request, CompanyType $companyType)
    {
        $data = $request->validated();
        $companyType->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyType)
            ->log('Updated Company Type');

        return response()->json([
            'message' => 'Company Type updated successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyType $companyType)
    {
        $companyType->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyType)
            ->log('Deleted Company Type');

        return response()->json([
            'success' => true
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:company_types,id',
            'status' => 'required|boolean',
        ]);

        $record = CompanyType::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Company Type');
        return response()->json([
            'message' => 'Company Type status updated successfully',
            'status' => true,
        ]);
    }
}
