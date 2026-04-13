<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePrefixRequest;
use App\Http\Requests\Admin\UpdatePrefixRequest;
use App\Models\Prefix;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class PrefixController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'toggleStatus']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Prefix::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $records = $query->orderBy('id', 'desc')->paginate($entries);
        $lastState = Prefix::orderBy('id', 'desc')->first();
        $nextId = $lastState ? $lastState->id + 1 : 1;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.prefix.index', compact('records'))->render()
            ]);
        }
        return view('admin.settings.prefix.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrefixRequest $request)
    {
        $data = $request->validated();
        $prefix =  Prefix::create($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($prefix)
            ->log('Prefix Created');
        return redirect()->back()->with('success', 'Prefix added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prefix $prefix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prefix $prefix, Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Prefix::query();

        $records = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.prefix.index', compact('records', 'prefix'))->render()
            ]);
        }

        return view('admin.settings.prefix.index', compact('records', 'prefix'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Prefix $prefix, UpdatePrefixRequest $request)
    {
        $data = $request->validated();
        $prefix->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($prefix)
            ->log('Prefix Updated');
        return redirect()->route('admin.prefixes.index')->with('success', 'Prefix updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prefix $prefix)
    {
        $prefix->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($prefix)
            ->log('Prefix Deleted');
        return redirect()->back()->with('success', 'Prefix deleted successfully.');
    }
}
