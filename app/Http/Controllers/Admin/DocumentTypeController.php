<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentTypeRequest;
use App\Http\Requests\Admin\UpdateDocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentTypeController extends Controller implements HasMiddleware
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
            $records = DocumentType::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.document-type.partials.action', compact('row'))->render();
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
        return view('admin.document-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->id) {
            $record = DocumentType::findOrFail($request->id);
            return response()->json([
                'html' => view('admin.document-type.form', compact('record'))->render(),
                'title' => 'Update Document Type'
            ]);
        }
        return response()->json([
            'html' => view('admin.document-type.form')->render(),
            'title' => 'Add Document Type',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentTypeRequest $request)
    {
        $data = $request->validated();
        $companyType = DocumentType::create($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyType)
            ->log('Created Document Type');

        return response()->json([
            'message' => 'Document Type created successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        $data = $request->validated();
        $documentType->update($data);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($documentType)
            ->log('Updated Document Type');

        return response()->json([
            'message' => 'Document Type updated successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($documentType)
            ->log('Deleted Document Type');

        return response()->json([
            'success' => true
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:document_types,id',
            'status' => 'required|boolean',
        ]);

        $record = DocumentType::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Document Type');
        return response()->json([
            'message' => 'Document Type status updated successfully',
            'status' => true,
        ]);
    }
}
