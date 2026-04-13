<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Project::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.project.partials.action', compact('row'))->render();
                })
                ->addColumn('custom_project_id', function ($row) {
                    return $row->custom_project_id ?? '-';
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->editColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date->format('d M Y')  : '-';
                })
                ->editColumn('end_date', function ($row) {
                    return $row->end_date ? $row->end_date->format('d M Y')  : '-';
                })
                ->editColumn('status', function ($row) {
                    $classes = [
                        'open' => 'secondary',
                        'in_progress'  => 'warning',
                        'completed'  => 'success',
                        'on_hold' => 'danger'
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
                    if ($request->filled('name')) {
                        $query->where('name', 'like', '%' . $request->name . '%');
                    }
                    if ($request->filled('category')) {
                        $query->where('project_category_id', $request->category);
                    }
                    if ($request->filled('company')) {
                        $query->where('company_id', $request->company);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $categories = ProjectCategory::where('is_active', true)->get();
        $companies  = Company::get();
        return view('admin.project.index', compact('categories', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProjectCategory::where('is_active', true)->get();
        $companies  = Company::get();
        return view('admin.project.create', compact('categories', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $record = Project::create($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Project');
        });

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $categories = ProjectCategory::where('is_active', true)->get();
        $companies  = Company::get();
        return view('admin.project.edit', compact('categories', 'companies', 'project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        DB::transaction(function () use ($request, $project) {
            $data = $request->validated();
            $project->update($data);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($project)
                ->log('Update Project');
        });

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($project)
            ->log('Delete Project');

        return response()->json(['success' => true]);
    }

    public function statusView(Request $request)
    {
        $project = Project::find($request->id);

        return response()->json([
            'html' => view('admin.project.status', compact('project'))->render(),
            'title' => 'Change Project Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'required|string|in:open,in_progress,completed,on_hold',
        ]);

        $record = Project::findOrFail($request->project_id);
        $record->status = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Project');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
