<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNoteTypeRequest;
use App\Http\Requests\Admin\UpdateNoteTypeRequest;
use App\Models\NoteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NoteTypeController extends Controller implements HasMiddleware
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
            $records = NoteType::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.note-type.partials.action', compact('row'))->render();
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
        return view('admin.note-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->id) {
            $record = NoteType::findOrFail($request->id);
            return response()->json([
                'html' => view('admin.note-type.form', compact('record'))->render(),
                'title' => 'Update Note Type'
            ]);
        }
        return response()->json([
            'html' => view('admin.note-type.form')->render(),
            'title' => 'Add Note Type',
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteTypeRequest $request)
    {
        $data = $request->validated();
        $companyType = NoteType::create($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyType)
            ->log('Created Note Type');

        return response()->json([
            'message' => 'Note Type created successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(NoteType $noteType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NoteType $noteType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteTypeRequest $request, NoteType $noteType)
    {
        $data = $request->validated();
        $noteType->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($noteType)
            ->log('Updated Note Type');

        return response()->json([
            'message' => 'Note Type updated successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoteType $noteType)
    {
        $noteType->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($noteType)
            ->log('Deleted note Type');

        return response()->json([
            'success' => true
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:note_types,id',
            'status' => 'required|boolean',
        ]);

        $record = NoteType::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Note Type');
        return response()->json([
            'message' => 'Note Type status updated successfully',
            'status' => true,
        ]);
    }
}
