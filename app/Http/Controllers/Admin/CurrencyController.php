<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreCurrencyRequest;
use App\Http\Requests\Admin\UpdateCurrencyRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CurrencyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'update', 'destroy', 'status']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Currency::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.currency.partials.action', compact('row'))->render();
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
        return view('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->id) {
            $record = Currency::findOrFail($request->id);
            return response()->json([
                'html' => view('admin.currency.form', compact('record'))->render(),
                'title' => 'Update Currency'
            ]);
        }
        return response()->json([
            'html' => view('admin.currency.form')->render(),
            'title' => 'Add Currency',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request)
    {
        $data = $request->validated();
        $currency = Currency::create($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($currency)
            ->log('Created Currency');

        return response()->json([
            'message' => 'Currency created successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $data = $request->validated();
        $currency->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($currency)
            ->log('Updated Currency');
        return response()->json([
            'message' => 'Currency updated successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($currency)
            ->log('Deleted Currency');

        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:currencies,id',
            'status' => 'required|boolean',
        ]);

        $record = Currency::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Currency');
        return response()->json([
            'message' => 'Currency status updated successfully',
            'status' => true,
        ]);
    }
}
