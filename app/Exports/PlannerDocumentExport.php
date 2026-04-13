<?php

namespace App\Exports;

use App\Models\PlannerDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlannerDocumentExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PlannerDocument::with([
            'totalGroup',
            'companyType',
            'planner',
            'financialYear'
        ])->orderBy('created_at', 'desc')->get()->map(function ($row) {

            return [
                'Title' => $row->title ?? '-',
                'Planner' => $row->planner->name ?? '-',
                'Total Group' => $row->totalGroup->customer_name ?? '-',
                'Type' => $row->companyType->name ?? '-',
                'Financial Year' => $row->financialYear->year ?? '-',
                'Status' => ucfirst(str_replace('_', ' ', $row->status ?? 'draft')),
                'Created At' => $row->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Planner',
            'Total Group',
            'Type',
            'Financial Year',
            'Status',
            'Created At',
        ];
    }
}
