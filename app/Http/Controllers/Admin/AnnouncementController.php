<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Http\Requests\Admin\UpdateAnnouncementRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnnouncementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('announcement.edit'), ['create', 'store']),
            new Middleware(PermissionMiddleware::using('announcement.delete'),  ['destroy']),
            new Middleware(PermissionMiddleware::using('announcement.view'),  ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = Announcement::query()->orderBy('created_at', 'desc');
            if ($request->customer_id) {
                $records->whereRelation('users.companies', 'company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.announcement.partials.action', compact('row'))->render();
                })
                ->editColumn('schedule_date', function ($row) {
                    return $row->schedule_date->format('d M Y');
                })
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->editColumn('message', function ($row) {
                    return \Illuminate\Support\Str::limit($row->message, 35, '...');
                })
                ->editColumn('priority', function ($row) {
                    $color = match (strtolower($row->priority)) {
                        'high' => 'danger',   // Red
                        'medium' => 'warning', // Orange
                        'low' => 'success',   // Green
                        default => 'secondary',
                    };

                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->priority) . '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('date')) {
                        $query->whereDate('schedule_date', $request->date);
                    }
                })
                ->rawColumns(['actions', 'priority'])
                ->make(true);
        }
        return view('admin.announcement.index', compact('customerID'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role(['Customer'])->orderBy('created_at', 'desc')->get();
        $planners = User::role(['Planner'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'html' => view('admin.announcement.form', compact('users', 'planners'))->render(),
            'title' => 'Send Announcement',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        DB::transaction(function () use ($request) {

            $data = $request->validated();
            $announcement = Announcement::create([
                'priority' => $data['priority'],
                'type'     => $data['type'],
                'subject'  => $data['subject'],
                'message'  => $data['message'],
                'schedule_date'  => $data['schedule_date'],
            ]);

            if ($announcement->type === 'public') {
                $users = User::role('Customer')->orderBy('created_at', 'desc')->get();
            } else {
                $users = User::whereIn('id', $request->user_id)->get();
                if ($users->count()) {
                    $announcement->users()->sync($users->pluck('id'));
                }
            }


            foreach ($users as $user) {
                Mail::to($user->email)->queue(new AnnouncementMail($user, $announcement->subject, $announcement->message));
            }

            $userNames = $users->pluck('name')->implode(', ');


            activity()
                ->causedBy(Auth::id())
                ->performedOn($announcement)
                ->log('Sent announcement to users');
        });
        return response()->json(['success' => true, 'message' => 'Announcement Sended Successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Announcement $announcement)
    {
        $user = auth()->user();
        if ($user) {
            $announcement->users()->syncWithoutDetaching([
                $user->id => ['read_at' => now()]
            ]);
        }

        if (!$request->ajax()) {
            return view('admin.announcement.show', compact('announcement'));
        }

        return response()->json([
            'html' => view('admin.announcement.view', compact('announcement'))->render(),
            'title' => 'View Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Get recipients (if any)
        $recipientNames = $announcement->users->pluck('name')->implode(', ');

        $announcement->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($announcement)
            ->log('Deleted announcement: "' . $announcement->subject . '" sent to: ' . ($recipientNames ?: 'No users'));

        return response()->json(['success' => true]);
    }
}
