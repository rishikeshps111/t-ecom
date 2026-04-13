<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompanyExport implements FromCollection
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Company::whereIn('id', $this->ids)
            ->get();
    }

    public function headings(): array
    {
        return [
            'SL NO',
            'Company ID',
            'Company Name',
            'Registration Number',
            'Email',
            'Phone',
            'Category',
            'Sub Category',
            'Company Website',
            'Industry',
            'Company Type',
            'Status'
        ];
    }

    public function map($record): array
    {
        return [
            $record->id,
            $record->custom_company_id ?? '-',
            $record->company_name ?? '-',
            $record->ssm_number ?? '-',
            $record->email_address ?? '-',
            $record->mobile_no ?? '-',
            $record->category->name ?? '-',
            $record->subCategory->name ?? '-',
            $record->company_website ?? '-',
            $record->industry ?? '-',
            $record->company_type ?? '-',
            $record->status === 'active'
                ? 'Active'
                : ($record->status === 'inactive'
                    ? 'Inactive'
                    : '-'),
        ];
    }
}
