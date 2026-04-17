<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDocument;
use App\Models\Payment;
use App\Models\WorkPlanAttachment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class CompanyAllFileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document.view'), ['index', 'downloadZip', 'exportSelected']),
        ];
    }

    public function index(Company $company)
    {
        $companyDocuments = CompanyDocument::where('company_id', $company->id)
            ->latest('id')
            ->get();

        $workOrderDocuments = WorkPlanAttachment::query()
            ->whereHas('workPlan', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->with('workPlan.quotation.invoice')
            ->latest('id')
            ->get()
            ->groupBy(function ($item) {
                return $item->workPlan->workplan_number ?? 'Unknown Work Order';
            });

        return view('admin.company-all-file.index', compact(
            'company',
            'companyDocuments',
            'workOrderDocuments'
        ));
    }

    public function downloadZip(Company $company)
    {
        $directory = storage_path('app/temp');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!is_dir($directory) || !is_writable($directory)) {
            abort(500, 'ZIP directory is not writable.');
        }

        $zipFileName = 'customer-files-' . ($company->company_code ?: $company->id) . '-' . now()->format('YmdHis') . '.zip';
        $zipPath = $directory . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive();
        $result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result !== true) {
            abort(500, 'Unable to create ZIP file. Error code: ' . $result);
        }

        CompanyDocument::where('company_id', $company->id)
            ->get()
            ->each(function ($document) use ($zip) {
                $relativePath = $this->normalizePublicPath($document->file);

                if (!$relativePath || !Storage::disk('public')->exists($relativePath)) {
                    return;
                }

                $fileName = $this->safeFileName($document->file_name ?: basename($relativePath));

                $zip->addFile(
                    Storage::disk('public')->path($relativePath),
                    'Customer Documents/' . $this->safeFolder($document->type ?: 'General') . '/' . $fileName
                );
            });

        WorkPlanAttachment::query()
            ->whereHas('workPlan', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->with('workPlan.quotation.invoice')
            ->get()
            ->each(function ($document) use ($zip) {
                $workOrder = $document->workPlan->workplan_number ?? 'Unknown Work Order';
                $folder = 'Work Order Documents/' . $this->safeFolder($workOrder) . '/';

                if ($document->entity === 'QO' && optional($document->workPlan?->quotation)->custom_quotation_id) {
                    $pdfPath = $this->ensureQuotationPdf($document->workPlan->quotation);

                    if ($pdfPath && file_exists($pdfPath)) {
                        $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                        return;
                    }
                }

                if ($document->entity === 'IN' && optional($document->workPlan?->quotation?->invoice)->custom_invoice_id) {
                    $pdfPath = $this->ensureInvoicePdf($document->workPlan->quotation->invoice);

                    if ($pdfPath && file_exists($pdfPath)) {
                        $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                        return;
                    }
                }

                if ($document->entity === 'OR' && $document->payment_id) {
                    $payment = Payment::find($document->payment_id);

                    if ($payment && $payment->custom_payment_id) {
                        $pdfPath = $this->ensureReceiptPdf($payment);

                        if ($pdfPath && file_exists($pdfPath)) {
                            $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                            return;
                        }
                    }
                }

                $relativePath = $this->normalizePublicPath($document->file);

                if (!$relativePath || !Storage::disk('public')->exists($relativePath)) {
                    return;
                }

                $name = $this->safeFileName(basename($document->file));

                $zip->addFile(
                    Storage::disk('public')->path($relativePath),
                    $folder . $name
                );
            });

        $closed = $zip->close();

        if ($closed === false || !file_exists($zipPath)) {
            Log::error('ZIP close failed', [
                'zip_path' => $zipPath,
                'directory_exists' => is_dir($directory),
                'directory_writable' => is_writable($directory),
            ]);

            abort(500, 'Failed to finalize ZIP file.');
        }

        return response()->download(
            $zipPath,
            'customer-files-' . ($company->company_code ?: $company->id) . '.zip'
        )->deleteFileAfterSend(true);
    }

    public function exportSelected(Request $request, Company $company)
    {
        $selectedFiles = $request->input('selected_files', []);

        if (empty($selectedFiles) || !is_array($selectedFiles)) {
            return redirect()
                ->back()
                ->with('error', 'Please select at least one file to export.');
        }

        $directory = storage_path('app/temp');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!is_dir($directory) || !is_writable($directory)) {
            abort(500, 'ZIP directory is not writable.');
        }

        $zipFileName = 'selected-customer-files-' . ($company->company_code ?: $company->id) . '-' . now()->format('YmdHis') . '.zip';
        $zipPath = $directory . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive();
        $result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result !== true) {
            abort(500, 'Unable to create ZIP file. Error code: ' . $result);
        }

        $addedFiles = 0;

        foreach ($selectedFiles as $selectedFile) {
            [$type, $id] = array_pad(explode(':', (string) $selectedFile, 2), 2, null);

            if (!$type || !$id) {
                continue;
            }

            if ($type === 'company_document') {
                $document = CompanyDocument::where('company_id', $company->id)->find($id);

                if ($document) {
                    $relativePath = $this->normalizePublicPath($document->file);

                    if ($relativePath && Storage::disk('public')->exists($relativePath)) {
                        $fileName = $this->safeFileName($document->file_name ?: basename($relativePath));

                        $zip->addFile(
                            Storage::disk('public')->path($relativePath),
                            'Customer Documents/' . $this->safeFolder($document->type ?: 'General') . '/' . $fileName
                        );

                        $addedFiles++;
                    }
                }
            }

            if ($type === 'work_order_document') {
                $document = WorkPlanAttachment::with('workPlan.quotation.invoice')->find($id);

                if (!$document || optional($document->workPlan)->company_id !== $company->id) {
                    continue;
                }

                $workOrder = $document->workPlan->workplan_number ?? 'Unknown Work Order';
                $folder = 'Work Order Documents/' . $this->safeFolder($workOrder) . '/';

                if ($document->entity === 'QO' && optional($document->workPlan?->quotation)->custom_quotation_id) {
                    $pdfPath = $this->ensureQuotationPdf($document->workPlan->quotation);

                    if ($pdfPath && file_exists($pdfPath)) {
                        $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                        $addedFiles++;
                        continue;
                    }
                }

                if ($document->entity === 'IN' && optional($document->workPlan?->quotation?->invoice)->custom_invoice_id) {
                    $pdfPath = $this->ensureInvoicePdf($document->workPlan->quotation->invoice);

                    if ($pdfPath && file_exists($pdfPath)) {
                        $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                        $addedFiles++;
                        continue;
                    }
                }

                if ($document->entity === 'OR' && $document->payment_id) {
                    $payment = Payment::find($document->payment_id);

                    if ($payment && $payment->custom_payment_id) {
                        $pdfPath = $this->ensureReceiptPdf($payment);

                        if ($pdfPath && file_exists($pdfPath)) {
                            $zip->addFile($pdfPath, $folder . $this->safeFileName(basename($pdfPath)));
                            $addedFiles++;
                            continue;
                        }
                    }
                }

                $relativePath = $this->normalizePublicPath($document->file);

                if ($relativePath && Storage::disk('public')->exists($relativePath)) {
                    $zip->addFile(
                        Storage::disk('public')->path($relativePath),
                        $folder . $this->safeFileName(basename($document->file))
                    );

                    $addedFiles++;
                }
            }
        }

        if ($addedFiles === 0) {
            $zip->close();

            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }

            return redirect()
                ->back()
                ->with('error', 'No valid files were available for export.');
        }

        $closed = $zip->close();

        if ($closed === false || !file_exists($zipPath)) {
            Log::error('Selected ZIP close failed', [
                'zip_path' => $zipPath,
                'directory_exists' => is_dir($directory),
                'directory_writable' => is_writable($directory),
            ]);

            abort(500, 'Failed to finalize selected ZIP file.');
        }

        return response()->download(
            $zipPath,
            'selected-customer-files-' . ($company->company_code ?: $company->id) . '.zip'
        )->deleteFileAfterSend(true);
    }

    private function normalizePublicPath(?string $path): string
    {
        return ltrim(str_replace('storage/', '', (string) $path), '/');
    }

    private function safeFolder(string $value): string
    {
        $value = preg_replace('/[\\\\\\/:"*?<>|]+/', '-', $value);
        $value = trim($value);

        return $value !== '' ? $value : 'Folder';
    }

    private function safeFileName(string $value): string
    {
        $value = preg_replace('/[\\\\\\/:"*?<>|]+/', '-', $value);
        $value = trim($value);

        return $value !== '' ? $value : 'file';
    }

    private function ensureQuotationPdf($quotation): ?string
    {
        if (!$quotation || !$quotation->custom_quotation_id) {
            return null;
        }

        $pdfPath = public_path('storage/quotations/' . str_replace('#', '_', $quotation->custom_quotation_id) . '.pdf');

        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        try {
            app(QuotationController::class)->downloadPdf($quotation);
        } catch (\Throwable $e) {
            Log::warning('Failed to generate quotation PDF for ZIP', [
                'quotation_id' => $quotation->id ?? null,
                'message' => $e->getMessage(),
            ]);
        }

        return file_exists($pdfPath) ? $pdfPath : null;
    }

    private function ensureInvoicePdf($invoice): ?string
    {
        if (!$invoice || !$invoice->custom_invoice_id) {
            return null;
        }

        $pdfPath = public_path('storage/invoice/' . str_replace('#', '_', $invoice->custom_invoice_id) . '.pdf');

        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        try {
            app(InvoiceController::class)->downloadPdf($invoice);
        } catch (\Throwable $e) {
            Log::warning('Failed to generate invoice PDF for ZIP', [
                'invoice_id' => $invoice->id ?? null,
                'message' => $e->getMessage(),
            ]);
        }

        return file_exists($pdfPath) ? $pdfPath : null;
    }

    private function ensureReceiptPdf(Payment $payment): ?string
    {
        if (!$payment->custom_payment_id) {
            return null;
        }

        $safeFileName = str_replace(['#', '/', '\\'], '_', $payment->custom_payment_id) . '.pdf';
        $nestedFolder = $payment->type_path ?? '';
        $pdfPath = public_path('storage/receipt' . ($nestedFolder ? '/' . $nestedFolder : '') . '/' . $safeFileName);

        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        try {
            app(ReceiptController::class)->downloadPdf($payment);
        } catch (\Throwable $e) {
            Log::warning('Failed to generate receipt PDF for ZIP', [
                'payment_id' => $payment->id ?? null,
                'message' => $e->getMessage(),
            ]);
        }

        return file_exists($pdfPath) ? $pdfPath : null;
    }
}
