<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\company;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Mail\SendMessageMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreMessageRequest;
use App\Http\Requests\Admin\UpdateMessageRequest;
use App\Models\Conversation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('message.edit'), ['create', 'store']),
            new Middleware(PermissionMiddleware::using('message.delete'),  ['destroy', 'store']),
            new Middleware(PermissionMiddleware::using('message.view'),  ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;

        if ($request->ajax()) {
            $records = Message::query()->orderBy('created_at', 'desc');

            if ($request->customer_id) {
                $records->whereRelation('user.companies', 'company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.message.partials.action', compact('row'))->render();
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
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
                    if ($request->filled('name')) {
                        $query->whereRelation('user', 'name', 'like', '%' . $request->name . '%');
                    }
                    if ($request->filled('date')) {
                        $query->whereDate('created_at', $request->date);
                    }
                })
                ->rawColumns(['actions', 'priority'])
                ->make(true);
        }
        return view('admin.message.index', compact('customerID'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $userId = $request->user_id ?? null;
        $userType = $request->user_type ?? null;
        $companyId =  $request->company_id ?? null;
        $selectedUsers = collect();

        if ($userId) {
            $selectedUsers = User::query()
                ->with('roles')
                ->whereIn('id', (array) $userId)
                ->get(['id', 'name', 'email']);

            if (!$userType) {
                $userType = $this->mapRoleNameToKey($selectedUsers->first()?->roles->first()?->name);
            }
        }

        return response()->json([
            'html' => view('admin.message.form', compact('selectedUsers', 'userId', 'userType', 'companyId'))->render(),
            'title' => 'Send Message',
        ]);
    }

    public function recipients(Request $request)
    {
        $request->validate([
            'user_type' => ['required', 'in:customer,planner,production_staff'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $users = $this->recipientsByTypeQuery(
            $request->user_type,
            $request->company_id
        )->get(['users.id', 'users.name', 'users.email']);

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                ];
            })->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $message = DB::transaction(function () use ($request) {
            $data = $request->validated();
            $companyId = $data['company_id'] ?? null;
            $recipientIds = $this->resolveRecipientIds($data['user_id'], $companyId);

            if (empty($recipientIds)) {
                throw ValidationException::withMessages([
                    'user_id' => 'No users found for the selected recipients.',
                ]);
            }

            $recipients = User::whereIn('id', $recipientIds)->get(['id', 'name']);

            $firstMessage = null;

            foreach ($recipients as $recipient) {
                $createdMessage = Message::create([
                    'company_id' => $companyId,
                    'user_id' => $recipient->id,
                    // 'subject' => $data['subject'] ?? null,
                    // 'priority' => $data['priority'] ?? 'low',
                ]);

                $firstMessage ??= $createdMessage;

                Conversation::create([
                    'message_id' => $createdMessage->id,
                    'message' => $data['message'],
                    'send_by' => 'admin'
                ]);

                // Mail::to($recipient->email)->queue(new SendMessageMail($recipient, $data['subject'] ?? null, $data['message']));
            }

            activity()
                ->causedBy(Auth::id())
                ->performedOn($firstMessage)
                ->log('Sent a message to users: ' . $this->formatRecipientNamesForLog($recipients));

            return $firstMessage;
        });

        return response()->json([
            'success' => true,
            'message' => 'Message Sended Successfully.',
            'url'     => route('admin.messages.conversation', $message->id),
        ]);
    }

    protected function availableRecipientsQuery($companyId = null)
    {
        return User::query()
            ->with('roles')
            ->when($companyId, function ($query) use ($companyId) {
                $query->where(function ($roleQuery) use ($companyId) {
                    $roleQuery
                        ->where(function ($customerQuery) use ($companyId) {
                            $customerQuery->role('Customer')
                                ->whereHas('companies', function ($companyQuery) use ($companyId) {
                                    $companyQuery->where('companies.id', $companyId);
                                });
                        })
                        ->orWhere(function ($plannerQuery) use ($companyId) {
                            $plannerQuery->role('Planner')
                                ->whereHas('plannerCompanies', function ($companyQuery) use ($companyId) {
                                    $companyQuery->where('companies.id', $companyId);
                                });
                        })
                        ->orWhere(function ($productionQuery) use ($companyId) {
                            $productionQuery->role('Production Staff')
                                ->whereHas('customerUsers', function ($companyQuery) use ($companyId) {
                                    $companyQuery->where('companies.id', $companyId);
                                });
                        });
                });
            }, function ($query) {
                $query->role(['Customer', 'Planner', 'Production Staff']);
            })
            ->orderBy('name', 'asc');
    }

    protected function recipientsByTypeQuery(string $userType, $companyId = null)
    {
        return match ($userType) {
            'customer' => $this->customerRecipientsQuery($companyId),
            'planner' => User::role('Planner')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('plannerCompanies', function ($companyQuery) use ($companyId) {
                        $companyQuery->where('companies.id', $companyId);
                    });
                })
                ->orderBy('name', 'asc'),
            'production_staff' => User::role('Production Staff')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->whereHas('customerUsers', function ($companyQuery) use ($companyId) {
                        $companyQuery->where('companies.id', $companyId);
                    });
                })
                ->orderBy('name', 'asc'),
            default => User::query()->whereRaw('1 = 0'),
        };
    }

    protected function customerRecipientsQuery($companyId = null)
    {
        return User::role('Customer')
            ->when(Auth::user()->hasRole('Planner'), function ($query) {
                return $query->whereHas('companies', function ($q) {
                    $q->where('planner_id', Auth::id());
                });
            })
            ->when(Auth::user()->hasRole('Production Staff'), function ($query) {
                return $query->whereHas('companies', function ($q) {
                    $q->where('production_staff_id', Auth::id());
                });
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereHas('companies', function ($q) use ($companyId) {
                    $q->where('companies.id', $companyId);
                });
            })
            ->orderBy('name', 'asc');
    }

    protected function resolveRecipientIds(array $targets, $companyId = null): array
    {
        $targets = collect($targets)->filter()->unique()->values();

        $groupRecipientIds = $targets
            ->filter(fn($target) => is_string($target) && str_starts_with($target, 'group_'))
            ->flatMap(fn($target) => $this->groupRecipientIds($target, $companyId));

        $individualRecipientIds = $targets
            ->reject(fn($target) => is_string($target) && str_starts_with($target, 'group_'))
            ->map(fn($target) => (int) $target)
            ->filter();

        return $groupRecipientIds
            ->merge($individualRecipientIds)
            ->unique()
            ->values()
            ->all();
    }

    protected function groupRecipientIds(string $groupKey, $companyId = null): array
    {
        return match ($groupKey) {
            'group_all' => $this->customerRecipientsQuery($companyId)->pluck('users.id')->all(),
            'group_planners' => User::role('Planner')->pluck('users.id')->all(),
            'group_production' => User::role('Production Staff')->pluck('users.id')->all(),
            default => [],
        };
    }

    protected function formatRecipientNamesForLog($recipients): string
    {
        $names = $recipients->pluck('name')->filter()->values();

        if ($names->isEmpty()) {
            return 'N/A';
        }

        if ($names->count() <= 5) {
            return $names->implode(', ');
        }

        return $names->take(5)->implode(', ') . ' and ' . ($names->count() - 5) . ' more';
    }

    protected function mapRoleNameToKey(?string $roleName): ?string
    {
        return match ($roleName) {
            'Customer' => 'customer',
            'Planner' => 'planner',
            'Production Staff' => 'production_staff',
            default => null,
        };
    }
    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return response()->json([
            'html' => view('admin.message.view', compact('message'))->render(),
            'title' => 'View Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {

        activity()
            ->causedBy(Auth::id())
            ->performedOn($message)
            ->log('Deleted customer message for: ' . $message->user->name);
        $message->delete();
        return response()->json(['success' => true]);
    }

    public function conversation($id)
    {
        $message = Message::find($id);
        return view('admin.message.conversation', compact('message'));
    }


    public function fetchConversations(Message $message)
    {
        $conversations = $message->conversations()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($conversations);
    }

    public function sendConversation(Request $request, Message $message)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $conversation = $message->conversations()->create([
            'message' => $request->message,
            'send_by' => Auth::user()->hasRole('Super Admin') ? 'admin' : 'user',
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }

    public function markAsRead($id)
    {
        $conversation = Conversation::find($id);
        $updated = false;

        if (auth()->user()->hasRole('Super Admin') && $conversation->send_by === 'user') {
            $conversation->update(['is_read' => true]);
            $updated = true;
        }

        if (auth()->user()->hasRole(['Customer', 'Planner', 'Production Staff']) && $conversation->send_by === 'admin') {
            $conversation->update(['is_read' => true]);
            $updated = true;
        }

        return response()->json([
            'success' => $updated
        ]);
    }
}
