<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SendCompanyMessageMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreCompanyMessageRequest;
use App\Http\Requests\Admin\UpdateCompanyMessageRequest;

class CompanyMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyId = $request->input('company_id') ?? null;
        if ($request->ajax()) {
            $query = CompanyMessage::query();
            if ($companyId) {
                $query->where('company_id', $companyId);
            }
            $records = $query->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.company-message.partials.action', compact('row'))->render();
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
                    if ($request->filled('date')) {
                        $query->whereDate('created_at', $request->date);
                    }
                })
                ->rawColumns(['actions', 'priority'])
                ->make(true);
        }
        return view('admin.company-message.index', compact('companyId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $company_id = $request->company_id;
        return response()->json([
            'html' => view('admin.company-message.form', compact('company_id'))->render(),
            'title' => 'Send Mail',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyMessageRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $companyId = $data['company_id'];
            $subject = $data['subject'];
            $messageText = $data['message'];
            $priority = $data['priority'];


            $message = CompanyMessage::create([
                'company_id' => $companyId,
                'subject' => $subject,
                'message' => $messageText,
                'priority' => $priority
            ]);

            $company = Company::find($companyId);
            if ($company) {
                Mail::to($company->email_address)->queue(new SendCompanyMessageMail($company, $subject, $messageText));
            }
            activity()
                ->causedBy(Auth::id())
                ->performedOn($message)
                ->log('Sent a message to company: ' . $company->company_name);
        });

        return response()->json(['success' => true, 'message' => 'Mail Sended Successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyMessage $companyMessage)
    {
        return response()->json([
            'html' => view('admin.company-message.view', compact('companyMessage'))->render(),
            'title' => 'View Details',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyMessage $companyMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyMessageRequest $request, CompanyMessage $companyMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyMessage $companyMessage)
    {
        $companyMessage->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($companyMessage)
            ->log('Deleted company message for: ' . $companyMessage->company->company_name);
        return response()->json(['success' => true]);
    }
}
