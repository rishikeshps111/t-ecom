<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\State;
use App\Models\Company;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Location;
use App\Models\CompanyType;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Exports\CompanyExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WorkPlanNote;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CompanyManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'view', 'export', 'statusView', 'status', 'notes']),
        ];
    }

    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Company::query();

        $categories = Category::get();
        $subCategories = SubCategory::get();
        $corpUsers = User::role('Customer')->get();
        $companyTypes = CompanyType::get();

        // 🔍 GLOBAL SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('company_code', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('mobile_no', 'like', "%{$search}%")
                    ->orWhereHas('businessUser', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('phone')) {
            $query->where('mobile_no', $request->phone);
        }

        if ($request->filled('register_date')) {
            $query->whereDate('created_at', $request->register_date);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        if ($request->filled('business_user_id')) {
            $query->where('business_user_id', $request->business_user_id);
        }

        if ($request->filled('company_type_id')) {
            $query->where('company_type_id', $request->company_type_id);
        }

        $company = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.company.index', compact(
                    'company',
                    'categories',
                    'subCategories',
                    'corpUsers',
                    'companyTypes'
                ))->render()
            ]);
        }

        return view('admin.company.index', compact(
            'company',
            'categories',
            'subCategories',
            'corpUsers',
            'companyTypes'
        ));
    }


    public function create()
    {
        $code = 'TECOM#' . date('Y') . 'C' . str_pad(Company::count() + 1, 2, '0', STR_PAD_LEFT);
        $categories = Category::get();
        $subCategories = SubCategory::get();
        $locations = Location::get();
        $states = State::get();
        $corpUsers = User::role('Customer')->get();
        $companyTypes = CompanyType::orderBy('name', 'asc')->get();
        $customers = Customer::get();
        $planners = User::role('Planner')
            ->orderByRaw("CASE WHEN name = 'default' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc') // Optional: sorts the rest alphabetically
            ->get();
        $staffs = User::where('user_type', 'production')->orderByRaw("CASE WHEN name = 'default' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc') // Optional: sorts the rest alphabetically
            ->get();


        return view('admin.company.create', compact('code', 'categories', 'subCategories', 'locations', 'states', 'planners', 'corpUsers', 'companyTypes', 'customers', 'staffs'));
    }

    // public function store(Request $request)
    // {
    //     DB::transaction(function () use ($request) {

    //         // COMPANY
    //         $company = Company::create($request->only([
    //             'company_code',
    //             'sub_category_id',
    //             'planner_id',
    //             'planner_code',
    //             'category_id',
    //             'company_type',
    //             'company_name',
    //             'alt_company_name',
    //             'industry',
    //             'description',
    //             'status',
    //             'ssm_number',
    //             'incorporation_date',
    //             'commencement_date',
    //             'paid_up_capital',
    //             'authorized_capital',
    //             'employees',
    //             'primary_contact_name',
    //             'designation',
    //             'mobile_no',
    //             'email_address',
    //             'company_website'
    //         ]));

    //         // ADDRESS
    //         $company->address()->create([
    //             'address1' => $request->address1,
    //             'address2' => $request->address2,
    //             'city_id' => $request->city,
    //             'state_id' => $request->state,
    //             'postcode' => $request->postcode,
    //             'country' => $request->country,
    //             'office_phone' => $request->office_phone,
    //             'office_email' => $request->office_email,

    //             'business_address1' => $request->business_address1,
    //             'business_address2' => $request->business_address2,
    //             'business_city_id' => $request->business_city,
    //             'business_state_id' => $request->business_state,
    //             'business_postcode' => $request->business_postcode,
    //             'business_country' => $request->business_country,
    //         ]);

    //         // DIRECTOR
    //         $company->directors()->create([
    //             'name' => $request->director_name,
    //             'identification_type' => $request->identification_type,
    //             'identification_number' => $request->identification_number,
    //             'nationality' => $request->director_nationality,
    //             'date_of_birth' => $request->director_date,
    //             'address' => $request->director_address,
    //             'email' => $request->director_email,
    //             'mobile' => $request->director_mobile,
    //             'position' => $request->director_position,
    //             'appointment_date' => $request->director_appointment,
    //         ]);

    //         $company->shareholders()->create([
    //             'type' => $request->shareholder_type ?? null,
    //             'name' => $request->shareholder_name,
    //             'identification' => $request->shareholder_identification,
    //             'nationality' => $request->shareholder_nationality,
    //             'shares' => $request->shareholder_shares,
    //             'ownership' => $request->shareholder_ownership,
    //             'share_class' => $request->shareholder_class,
    //         ]);
    //     });

    //     return redirect()->route('admin.manage.company')
    //         ->with('success', 'Company registered successfully');
    // }

    public function edit($id)
    {
        $company = Company::findorFail($id);
        $categories = Category::get();
        $subCategories = SubCategory::get();
        $locations = Location::get();
        $states = State::get();
        $corpUsers = User::role('Customer')
            ->when(!Auth::user()->hasRole('Super Admin'), function ($query) {
                $query->where('id', Auth::id());
            })
            ->get();
        $planners = User::role('Planner')->orderByRaw("CASE WHEN name = 'default' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc') // Optional: sorts the rest alphabetically
            ->get();
        $companyTypes = CompanyType::orderBy('name', 'asc')->get();
        $customers = Customer::get();
        $staffs = User::where('user_type', 'production')->orderByRaw("CASE WHEN name = 'default' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc') // Optional: sorts the rest alphabetically
            ->get();;
        return view('admin.company.edit', compact('company', 'categories', 'subCategories', 'locations', 'states', 'planners', 'corpUsers', 'companyTypes', 'customers', 'staffs'));
    }

    // public function update(Request $request, $id)
    // {
    //     DB::transaction(function () use ($request, $id) {

    //         $company = Company::findOrFail($id);

    //         // COMPANY
    //         $company->update($request->only([
    //             'company_type',
    //             'company_name',
    //             'sub_category_id',
    //             'category_id',
    //             'planner_id',
    //             'planner_code',
    //             'alt_company_name',
    //             'industry',
    //             'description',
    //             'status',
    //             'ssm_number',
    //             'incorporation_date',
    //             'commencement_date',
    //             'paid_up_capital',
    //             'authorized_capital',
    //             'employees',
    //             'primary_contact_name',
    //             'designation',
    //             'mobile_no',
    //             'email_address',
    //             'company_website'
    //         ]));

    //         // ADDRESS
    //         $company->address()->updateOrCreate(
    //             ['company_id' => $company->id],
    //             $request->only([
    //                 'address1',
    //                 'address2',
    //                 'city_id',
    //                 'state_id',
    //                 'postcode',
    //                 'country',
    //                 'office_phone',
    //                 'office_email',
    //                 'business_address1',
    //                 'business_address2',
    //                 'business_city_id',
    //                 'business_state_id',
    //                 'business_postcode',
    //                 'business_country'
    //             ])
    //         );

    //         // DIRECTOR
    //         $company->directors()->updateOrCreate(
    //             ['company_id' => $company->id],
    //             [
    //                 'name' => $request->director_name,
    //                 'identification_type' => $request->identification_type,
    //                 'identification_number' => $request->identification_number,
    //                 'nationality' => $request->director_nationality,
    //                 'date_of_birth' => $request->director_date,
    //                 'address' => $request->director_address,
    //                 'email' => $request->director_email,
    //                 'mobile' => $request->director_mobile,
    //                 'position' => $request->director_position,
    //                 'appointment_date' => $request->director_appointment,
    //             ]
    //         );

    //         // SHAREHOLDER
    //         $company->shareholders()->updateOrCreate(
    //             ['company_id' => $company->id],
    //             [
    //                 'type' => $request->shareholder_type,
    //                 'name' => $request->shareholder_name,
    //                 'identification' => $request->shareholder_identification,
    //                 'nationality' => $request->shareholder_nationality,
    //                 'shares' => $request->shareholder_shares,
    //                 'ownership' => $request->shareholder_ownership,
    //                 'share_class' => $request->shareholder_class,
    //             ]
    //         );
    //     });

    //     return redirect()->route('admin.manage.company')->with('success', 'Company updated successfully');
    // }

    protected function companyRules($id = null)
    {
        return [
            'company_code'       => ['required', 'string', 'max:50', 'unique:companies,company_code,' . $id],
            // 'business_user_id'   => ['required', 'exists:users,id'],
            'company_type_id'    => ['required', 'exists:company_types,id'],
            'total_group_id'     => ['required', 'exists:customers,id'],
            'planner_id'     => ['required', 'exists:users,id'],
            'production_staff_id'     => ['required', 'exists:users,id'],


            'company_name'       => ['required', 'string', 'max:255'],
            'mobile_no'          => ['required', 'string', 'max:20'],
            'email_address'      => ['required', 'email', 'max:255'],
            'address'      => ['nullable', 'string', 'max:1000'],

            'category_id'        => ['nullable', 'exists:categories,id'],
            'sub_category_id'    => ['nullable', 'exists:sub_categories,id'],
            'state_id'           => ['required', 'exists:states,id'],
            'city_id'            => ['required', 'exists:locations,id'],

            'status'             => ['required', 'in:active,draft,inactive'],
        ];
    }
    public function store(Request $request)
    {
        $validated = $request->validate($this->companyRules());

        DB::transaction(function () use ($validated, $request) {

            // COMPANY
            $company = Company::create([
                'company_code'      => $validated['company_code'],
                // 'business_user_id'  => $validated['business_user_id'],
                'company_type_id'   => $validated['company_type_id'],
                'total_group_id'    => $validated['total_group_id'],
                'company_name'      => $validated['company_name'],
                'status'            => $validated['status'],
                'mobile_no'         => $validated['mobile_no'],
                'email_address'     => $validated['email_address'],
                'address'     => $validated['address'],
                'planner_id'     => $validated['planner_id'],
                'production_staff_id'     => $validated['production_staff_id'],

            ]);

            $company->address()->create([
                'state_id'          => $request->state_id,
                'city_id'           => $request->city_id,
                'business_country'  => $request->business_country ?? 'Malaysia',
            ]);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($company)
                ->log('Customer Created');
        });

        return redirect()
            ->route('admin.manage.company')
            ->with('success', 'Company registered successfully');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->companyRules($id));

        DB::transaction(function () use ($validated, $request, $id) {

            $company = Company::findOrFail($id);

            $company->update([
                // 'business_user_id'  => $validated['business_user_id'],
                'company_type_id'   => $validated['company_type_id'],
                'total_group_id'    => $validated['total_group_id'],
                'company_name'      => $validated['company_name'],
                'status'            => $validated['status'],
                'mobile_no'         => $validated['mobile_no'],
                'email_address'     => $validated['email_address'],
                'address'     => $validated['address'],
                'planner_id'     => $validated['planner_id'],
                'production_staff_id'     => $validated['production_staff_id'],
            ]);

            $company->address()->updateOrCreate(
                ['company_id' => $company->id],
                [
                    'state_id'         => $request->state_id,
                    'city_id'          => $request->city_id,
                    'business_country' => $request->business_country ?? 'Malaysia',
                ]
            );
            activity()
                ->causedBy(Auth::id())
                ->performedOn($company)
                ->log('Customer Updated');
        });

        return redirect()
            ->route('admin.manage.company')
            ->with('success', 'Company updated successfully');
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $company = Company::findOrFail($id);

            $company->address()?->delete();
            $company->directors()?->delete();
            $company->shareholders()?->delete();

            $company->delete();

            activity()
                ->causedBy(Auth::id())
                ->performedOn($company)
                ->log('Customer Deleted');
        });

        return redirect()->route('admin.manage.company')->with('success', 'Company deleted successfully');
    }

    public function view($id)
    {
        $company = Company::with([
            'address',
            'directors',
            'shareholders'
        ])->findorFail($id);
        return view('admin.company.view', compact('company'));
    }

    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new CompanyExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'membership.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="membership.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }

    public function statusView(Request $request)
    {
        $record = Company::find($request->id);

        return response()->json([
            'html' => view('admin.company.status', compact('record'))->render(),
            'title' => 'Change Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:companies,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $record = Company::findOrFail($request->id);
        $record->status = $request->status;
        $record->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Customer Status Changed');

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'type' => $record->status
        ]);
    }

    public function notes(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        $records = WorkPlanNote::whereRelation('workPlan', 'company_id', $customerID)->get();
        return  view('admin.company.notes', compact(
            'records',
        ));
    }
}
