<?php

namespace App\Exports;

use App\Models\WorkPlanAttachment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentCompanyExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = WorkPlanAttachment::whereIn('entity', ['OR', 'IN', 'QO']);

        if ($this->request->filled('work_plan')) {
            $query->where('work_plan_id', $this->request->work_plan);
        }

        if ($this->request->filled('entity')) {
            $query->where('entity', $this->request->entity);
        }

        if ($this->request->filled('type')) {
            $query->where('type', $this->request->type);
        }

        if ($this->request->filled('company_id')) {
            $query->whereRelation('workPlan', 'company_id', $this->request->company_id);
        }

        if ($this->request->filled('from_date') && $this->request->filled('to_date')) {
            $query->where(function ($q) {
                $q->where('created_at', '<', $this->request->from_date)
                    ->orWhere('created_at', '>', $this->request->to_date);
            });
        }

        return $query->with(['workPlan.company'])->get()->map(function ($row) {

            $map = [
                'QO' => 'Quotation',
                'IN' => 'Invoice',
                'OR' => 'Original Receipt',
            ];

            return [
                'Company' => $row->workPlan->company->company_name ?? '-',
                'Work Order' => $row->workPlan->workplan_number ?? '-',
                'Entity' => $map[$row->entity] ?? ucfirst($row->entity ?? '-'),
                'Created At' => $row->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Company',
            'Work Order',
            'Entity',
            'Created At',
        ];
    }
}
