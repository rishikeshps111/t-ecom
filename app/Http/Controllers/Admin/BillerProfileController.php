<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BillerProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreBillerProfileRequest;
use App\Http\Requests\Admin\UpdateBillerProfileRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class BillerProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = Company::get();
        $totalGroups = Customer::withoutGlobalScope('exclude_default')->orderBy('customer_name', 'asc')->get();
        if ($request->ajax()) {
            $records = BillerProfile::with([
                'totalGroup' => function ($q) {
                    $q->withoutGlobalScope('exclude_default');
                }
            ])->orderBy('created_at', 'desc');

            return DataTables::eloquent(builder: $records)
                ->addColumn('actions', function ($row) {
                    return view('admin.biller-profile.partials.action', compact('row'))->render();
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup->customer_name ?? '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->totalGroup->is_active ?? null;
                    if ($status === 1) {
                        return '<span class="badge bg-success">Active</span>';
                    }
                    if ($status === 0) {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                    return '<span class="badge bg-secondary">-</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('company')) {
                        $query->where('company_id', $request->company);
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id', $request->total_group);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view('admin.biller-profile.index', compact('companies', 'totalGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $totalGroups = Customer::get();
        $companies = Company::get();
        return view('admin.biller-profile.create', compact('totalGroups', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillerProfileRequest $request)
    {
        $data = $request->validated();

        // Image fields
        $imageFields = [
            'invoice_header',
            'invoice_footer',
            'quotation_header',
            'quotation_footer',
            'receipt_header',
            'receipt_footer',
            'work_plan_header',
            'work_plan_footer',
            'report_header',
            'report_footer'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)
                    ->store('biller_profiles', 'public');
            }
        }

        $record =  BillerProfile::create($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Create Biller Profile');
        return redirect()
            ->route('admin.biller-profiles.index')
            ->with('success', 'Biller profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BillerProfile $billerProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BillerProfile $billerProfile)
    {
        $totalGroups = Customer::withoutGlobalScope('exclude_default')->get();
        $companies = Company::get();
        return view('admin.biller-profile.edit', compact('totalGroups', 'companies', 'billerProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillerProfileRequest $request, BillerProfile $billerProfile)
    {
        $data = $request->validated();

        $imageFields = [
            'invoice_header',
            'invoice_footer',
            'quotation_header',
            'quotation_footer',
            'receipt_header',
            'receipt_footer',
            'work_plan_header',
            'work_plan_footer',
            'report_header',
            'report_footer'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)
                    ->store('biller_profiles', 'public');
            } else {
                $data[$field] = $billerProfile->$field;
            }
        }

        $billerProfile->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($billerProfile)
            ->log('Update Biller Profile');
        return redirect()
            ->route('admin.biller-profiles.index')
            ->with('success', 'Biller profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillerProfile $billerProfile)
    {
        $billerProfile->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($billerProfile)
            ->log('Delete Biller Profile');
        return response()->json(['success' => true]);
    }
}
