<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FinancialYear;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FinancialYearController extends Controller implements HasMiddleware
{
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
            $records = FinancialYear::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.financial.partials.action', compact('row'))->render();
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
        return view('admin.financial.index');
    }

    public function create(Request $request)
    {
        if ($request->id) {
            $record = FinancialYear::findOrFail($request->id);
            return response()->json([
                'html' => view('admin.financial.form', compact('record'))->render(),
                'title' => 'Update Year'
            ]);
        }
        return response()->json([
            'html' => view('admin.financial.form')->render(),
            'title' => 'Add Year',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => [
                'required',
                'regex:/^\d{4}$/',
                Rule::unique('financial_years', 'year'),
            ],
        ], [
            'year.required' => 'The year field is required.',
            'year.regex' => 'The year format must be like 2023-2034.',
            'year.unique' => 'This year already exists.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $financialYear = FinancialYear::create([
            'year' => $request->year,
            'is_active' => 0,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($financialYear)
            ->log('Created Financial Year');

        return response()->json([
            'status' => 'success',
            'message' => 'Financial year added successfully.',
            'data' => $financialYear
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = FinancialYear::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'year' => [
                'required',
                'regex:/^\d{4}$/',
                Rule::unique('financial_years', 'year'),
            ],
        ], [
            'year.required' => 'The year field is required.',
            'year.regex' => 'The year format must be like 2023-2034.',
            'year.unique' => 'This year already exists.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $record->update([
            'year' => $request->year,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Updated Financial Year');

        return response()->json([
            'status' => 'success',
            'message' => 'Financial year updated successfully.',
            'data' => $record
        ]);
    }

    public function destroy($id)
    {
        $record = FinancialYear::findOrFail($id);

        if ($record->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Active financial year cannot be deleted.'
            ], 403);
        }

        $record->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Deleted Financial Year');

        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:financial_years,id',
            'status' => 'required|boolean',
        ]);

        $record = FinancialYear::findOrFail($request->id);

        if ($request->status) {
            FinancialYear::where('id', '!=', $record->id)->update(['is_active' => 0]);
        }

        $record->is_active = $request->status;
        $record->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status changed for financial year: ' . $record->year);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
