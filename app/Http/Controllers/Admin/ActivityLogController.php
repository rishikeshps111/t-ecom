<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActivityLogController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'bulkDelete']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Activity::with('causer')
                ->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('user', function ($row) {
                    return $row->causer->name ?? 'System';
                })
                ->addColumn('event', function ($row) {
                    return $row->description;
                })
                ->addColumn('subject', function ($row) {
                    // Show type of model (e.g., Customer, Invoice)
                    return class_basename($row->subject_type);
                })
                ->addColumn('time', function ($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->rawColumns(['checkbox'])
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('user')) {
                        $query->whereHas('causer', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->user . '%');
                        });
                    }

                    if ($request->filled('module')) {
                        $query->where('subject_type', 'like', '%' . $request->module . '%');
                    }

                    if ($request->filled('from')) {
                        $query->whereDate('created_at', '>=', $request->from);
                    }

                    if ($request->filled('to')) {
                        $query->whereDate('created_at', '<=', $request->to);
                    }
                })
                ->make(true);
        }

        return view('admin.activity-log.index');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);

        Activity::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true]);
    }
}
