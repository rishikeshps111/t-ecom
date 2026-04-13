<div class="modal-card">
    @if ($business_user->companies->count() > 0)
        <table class="table table-bordered table-striped tble-cstm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company Code</th>
                    <th>Company Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Mobile</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($business_user->companies as $index => $company)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $company->company_code }}</td>
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->companyType?->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($company->status) }}</td>
                        <td>{{ $company->mobile_no }}</td>
                        <td>{{ $company->email_address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No Companies Assigned</p>
    @endif
</div>