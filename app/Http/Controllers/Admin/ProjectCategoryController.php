<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreProjectCategoryRequest;
use App\Http\Requests\Admin\UpdateProjectCategoryRequest;

class ProjectCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = ProjectCategory::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.project_category.partials.action', compact('row'))->render();
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
        return view('admin.project_category.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.project_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $record = ProjectCategory::create($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Project Category');
        });

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', 'Project Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectCategory $projectCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectCategory $projectCategory)
    {
        return view('admin.project_category.edit', compact('projectCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectCategoryRequest $request, ProjectCategory $projectCategory)
    {
        DB::transaction(function () use ($request, $projectCategory) {
            $data = $request->validated();
            $projectCategory->update($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($projectCategory)
                ->log('Update Project Category');
        });

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', 'Project Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($projectCategory)
            ->log('Delete Project Category');
        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:project_categories,id',
            'status' => 'required|boolean',
        ]);

        $record = ProjectCategory::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Project Category');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
