<x-mail::message>
# Hello {{ $company->company_name }},

Please find attached the {{ strtolower($documentLabel) }} PDF
@if($documentNumber)
({{ $documentNumber }})
@endif
for work order {{ $workOrder->workplan_number }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
