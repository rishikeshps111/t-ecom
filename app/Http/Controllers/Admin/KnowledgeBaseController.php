<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\ChatCategory;
use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreKnowledgeBaseRequest;
use App\Http\Requests\Admin\UpdateKnowledgeBaseRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KnowledgeBaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('knowledge-base.view'), ['index', 'show']),
            new Middleware(PermissionMiddleware::using('knowledge-base.create'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('knowledge-base.edit'),  ['edit', 'update', 'status', 'statusView']),
            new Middleware(PermissionMiddleware::using('knowledge-base.delete'), ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = KnowledgeBase::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.knowledge.partials.action', compact('row'))->render();
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? '-';
                })
                ->addColumn('keywords', function ($row) {
                    $keywords = $row->keywords ?? [];
                    if (is_string($keywords)) {
                        $keywords = json_decode($keywords, true) ?? [];
                    }
                    $html = '';
                    foreach ($keywords as $keyword) {
                        $html .= '<span class="badge bg-info me-1">' . e($keyword) . '</span>';
                    }
                    return $html ?: '-';
                })
                ->editColumn('status', function ($row) {
                    $classes = [
                        'draft' => 'secondary',
                        'unpublished'  => 'warning',
                        'published'  => 'success'
                    ];
                    $status = $row->status ?? 'draft';
                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst(str_replace('_', ' ', $status)) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('title')) {
                        $query->where('title', 'like', '%' . $request->title . '%');
                    }
                    if ($request->filled('category')) {
                        $query->where('chat_category_id', $request->category);
                    }
                })
                ->rawColumns(['actions', 'keywords', 'status'])
                ->make(true);
        }
        $categories = ChatCategory::get(['id', 'name']);
        return view('admin.knowledge.index', compact('categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ChatCategory::where('is_active', true)->get();
        return view('admin.knowledge.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKnowledgeBaseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $record = KnowledgeBase::create($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Knowledge Base');
        });

        return redirect()
            ->route('admin.knowledge-bases.index')
            ->with('success', 'Knowledge Base created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        $categories = ChatCategory::where('is_active', true)->get();
        return view('admin.knowledge.edit', compact('categories', 'knowledgeBase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKnowledgeBaseRequest $request, KnowledgeBase $knowledgeBase)
    {
        DB::transaction(function () use ($request, $knowledgeBase) {
            $data = $request->validated();
            $knowledgeBase->update($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($knowledgeBase)
                ->log('Update Knowledge Base');
        });

        return redirect()
            ->route('admin.knowledge-bases.index')
            ->with('success', 'Knowledge Base updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($knowledgeBase)
            ->log('Delete Knowledge Base');
        return response()->json(['success' => true]);
    }

    public function statusView(Request $request)
    {
        $knowledgeBase = KnowledgeBase::find($request->id);
        return response()->json([
            'html' => view('admin.knowledge.status', compact('knowledgeBase'))->render(),
            'title' => 'Change Knowledge Base Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'knowledge_base_id' => 'required|integer|exists:knowledge_bases,id',
            'status' => 'required|string|in:draft,unpublished,published',
        ]);

        $record = KnowledgeBase::findOrFail($request->knowledge_base_id);
        $record->status = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Knowledge Base');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
