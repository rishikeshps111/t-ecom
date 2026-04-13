<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreChatCategoryRequest;
use App\Http\Requests\Admin\UpdateChatCategoryRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ChatCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('knowledge-base-category.view'), ['index', 'show']),
            new Middleware(PermissionMiddleware::using('knowledge-base-category.create'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('knowledge-base-category.edit'),  ['edit', 'update', 'status']),
            new Middleware(PermissionMiddleware::using('knowledge-base-category.delete'), ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = ChatCategory::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.chat_category.partials.action', compact('row'))->render();
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
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('is_active', (bool) $request->status);
                    }
                    if ($request->filled('name')) {
                        $query->where('name', 'like', '%' . $request->name . '%');
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view('admin.chat_category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.chat_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $record = ChatCategory::create($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Knowledge Base Category');
        });

        return redirect()
            ->route('admin.chat-categories.index')
            ->with('success', 'Knowledge Base Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatCategory $chatCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatCategory $chatCategory)
    {
        return view('admin.chat_category.edit', compact('chatCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatCategoryRequest $request, ChatCategory $chatCategory)
    {
        DB::transaction(function () use ($request, $chatCategory) {
            $data = $request->validated();
            $chatCategory->update($data);
            activity()
                ->causedBy(Auth::id())
                ->performedOn($chatCategory)
                ->log('Update Knowledge Base Category');
        });

        return redirect()
            ->route('admin.chat-categories.index')
            ->with('success', 'Knowledge Base Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatCategory $chatCategory)
    {
        $chatCategory->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($chatCategory)
            ->log('Delete Knowledge Base Category');
        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:chat_categories,id',
            'status' => 'required|boolean',
        ]);

        $record = ChatCategory::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Knowledge Base Category');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
